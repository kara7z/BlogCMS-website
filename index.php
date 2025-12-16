<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$db = getDB();

// Récupérer les informations de l'utilisateur si connecté
$isLoggedIn = isLoggedIn();
$username = $_SESSION['username'] ?? null;
$user_role = $_SESSION['user_role'] ?? 'visitor';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9;

// Filtrer par catégorie
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Construire la requête
$whereClause = [];
$params = [];

if ($categoryFilter) {
    $whereClause[] = "a.cat_id = ?";
    $params[] = $categoryFilter;
}

if ($search) {
    $whereClause[] = "(a.title LIKE ? OR a.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSQL = $whereClause ? 'WHERE ' . implode(' AND ', $whereClause) : '';

$stmt = $db->prepare("SELECT COUNT(*) as total FROM article a $whereSQL");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

$pagination = paginate($total, $perPage, $page);

$stmt = $db->prepare("
    SELECT a.*, u.first_name, u.last_name, c.cat_name,
           (SELECT COUNT(*) FROM comment WHERE article_id = a.article_id) as comment_count
    FROM article a
    LEFT JOIN user u ON a.username = u.username
    LEFT JOIN category c ON a.cat_id = c.cat_id
    $whereSQL
    ORDER BY a.creation_date DESC
    LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
");
$stmt->execute($params);
$articles = $stmt->fetchAll();

$stmt = $db->query("SELECT * FROM category ORDER BY cat_name");
$categories = $stmt->fetchAll();

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <h1 class="text-2xl font-bold text-indigo-600">BlogCMS</h1>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-indigo-600 font-medium">Accueil</a>
                    <?php if ($isLoggedIn && hasAnyRole(['admin', 'editor', 'author'])): ?>
                    <a href="dashboard.php" class="text-gray-700 hover:text-indigo-600 font-medium">Dashboard</a>
                    <?php endif; ?>
                </nav>
                <div class="flex items-center gap-4">
                    <?php if ($isLoggedIn): ?>
                        <span class="text-sm text-gray-700">Bonjour, <strong><?= e($_SESSION['first_name'] ?? $username) ?></strong></span>
                        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if ($flash): ?>
        <div class="mb-6 bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-50 border-l-4 border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-500 p-4 rounded-r">
            <p class="text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700"><?= e($flash['message']) ?></p>
        </div>
        <?php endif; ?>
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="<?= e($search) ?>" placeholder="Rechercher..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"/>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Rechercher</button>
            </form>
            <div class="flex flex-wrap gap-2 mt-4">
                <a href="index.php" class="px-4 py-2 <?= !$categoryFilter ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded-lg">Toutes</a>
                <?php foreach ($categories as $cat): ?>
                <a href="?category=<?= $cat['cat_id'] ?>" class="px-4 py-2 <?= $categoryFilter == $cat['cat_id'] ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded-lg"><?= e($cat['cat_name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($articles as $article): ?>
            <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full"><?= e($article['cat_name']) ?></span>
                        <span class="text-sm text-gray-500"><?= formatDate($article['creation_date']) ?></span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-3">
                        <a href="article_view.php?id=<?= $article['article_id'] ?>" class="hover:text-indigo-600"><?= e($article['title']) ?></a>
                    </h2>
                    <p class="text-gray-600 mb-4"><?= e(truncate($article['content'], 150)) ?></p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br <?= getAvatarColor($article['username']) ?> rounded-full flex items-center justify-center text-white text-sm font-bold">
                                <?= getInitial($article['first_name']) ?>
                            </div>
                            <span class="text-sm text-gray-700"><?= e($article['first_name'] . ' ' . $article['last_name']) ?></span>
                        </div>
                        <div class="flex items-center gap-1 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="text-sm"><?= $article['comment_count'] ?></span>
                        </div>
                    </div>
                    <a href="article_view.php?id=<?= $article['article_id'] ?>" class="mt-4 block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Lire la suite</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="flex justify-center gap-2">
            <?php if ($pagination['has_prev']): ?>
            <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 bg-white border rounded-lg">Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= min($pagination['total_pages'], 5); $i++): ?>
            <a href="?page=<?= $i ?>" class="px-4 py-2 <?= $i == $page ? 'bg-indigo-600 text-white' : 'bg-white border' ?> rounded-lg"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($pagination['has_next']): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-white border rounded-lg">Suivant</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
