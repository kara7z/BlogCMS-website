<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté
requireLogin();

$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];
$db = getDB();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Créer un article
    if (isset($_POST['create_article']) && hasAnyRole(['admin', 'editor', 'author'])) {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $cat_id = $_POST['cat_id'];
        
        if (!empty($title) && !empty($content)) {
            $stmt = $db->prepare("INSERT INTO article (title, content, username, cat_id) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$title, $content, $username, $cat_id])) {
                redirect('articles.php', 'Article créé avec succès!');
            }
        }
    }
    
    // Modifier un article
    if (isset($_POST['update_article'])) {
        $article_id = $_POST['article_id'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $cat_id = $_POST['cat_id'];
        
        // Vérifier les permissions
        $stmt = $db->prepare("SELECT username FROM article WHERE article_id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch();
        
        if ($article && (hasRole('admin') || hasRole('editor') || $article['username'] === $username)) {
            $stmt = $db->prepare("UPDATE article SET title = ?, content = ?, cat_id = ? WHERE article_id = ?");
            if ($stmt->execute([$title, $content, $cat_id, $article_id])) {
                redirect('articles.php', 'Article modifié avec succès!');
            }
        }
    }
    
    // Supprimer un article
    if (isset($_POST['delete_article'])) {
        $article_id = $_POST['article_id'];
        
        // Vérifier les permissions
        $stmt = $db->prepare("SELECT username FROM article WHERE article_id = ?");
        $stmt->execute([$article_id]);
        $article = $stmt->fetch();
        
        if ($article && (hasRole('admin') || hasRole('editor') || $article['username'] === $username)) {
            // Supprimer d'abord les commentaires
            $stmt = $db->prepare("DELETE FROM comment WHERE article_id = ?");
            $stmt->execute([$article_id]);
            
            // Supprimer l'article
            $stmt = $db->prepare("DELETE FROM article WHERE article_id = ?");
            if ($stmt->execute([$article_id])) {
                redirect('articles.php', 'Article supprimé avec succès!');
            }
        }
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;

// Filtrer par catégorie
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Recherche
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

// Si pas admin/editor, voir seulement ses articles
if (!hasAnyRole(['admin', 'editor'])) {
    $whereClause[] = "a.username = ?";
    $params[] = $username;
}

$whereSQL = $whereClause ? 'WHERE ' . implode(' AND ', $whereClause) : '';

// Compter le total
$stmt = $db->prepare("SELECT COUNT(*) as total FROM article a $whereSQL");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

$pagination = paginate($total, $perPage, $page);

// Récupérer les articles
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

// Récupérer les catégories pour le formulaire
$stmt = $db->query("SELECT * FROM category ORDER BY cat_name");
$categories = $stmt->fetchAll();

// Article à éditer
$editArticle = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM article WHERE article_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editArticle = $stmt->fetch();
    
    // Vérifier les permissions
    if ($editArticle && !hasAnyRole(['admin', 'editor']) && $editArticle['username'] !== $username) {
        $editArticle = null;
    }
}

$flash = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Articles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        
        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Articles</h2>
                    <div class="flex items-center gap-4">
                        <?php if (hasAnyRole(['admin', 'editor', 'author'])): ?>
                        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvel article
                        </button>
                        <?php endif; ?>
                        
                        <form method="GET" class="flex gap-2">
                            <input 
                                type="text" 
                                name="search"
                                value="<?= e($search) ?>"
                                placeholder="Rechercher un article..."
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-64"
                            />
                            <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                Rechercher
                            </button>
                        </form>
                        
                        <div class="w-10 h-10 bg-gradient-to-br <?= getAvatarColor($username) ?> rounded-full flex items-center justify-center text-white font-bold">
                            <?= getInitial($_SESSION['first_name'] ?: $username) ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="flex-1 overflow-y-auto p-6">
                
                <?php if ($flash): ?>
                <div class="mb-6 bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-50 border-l-4 border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-500 p-4 rounded-r">
                    <p class="text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700"><?= e($flash['message']) ?></p>
                </div>
                <?php endif; ?>

                <div class="space-y-6">
                    
                    <!-- Filter Bar -->
                    <div class="flex flex-wrap items-center justify-between bg-white p-4 rounded-xl shadow-sm">
                        <div class="flex items-center gap-4">
                            <a href="articles.php" class="px-4 py-2 <?= !$categoryFilter ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' ?> rounded-lg font-medium">
                                Tous (<?= $total ?>)
                            </a>
                            <?php foreach ($categories as $cat): ?>
                            <a href="?category=<?= $cat['cat_id'] ?>" class="px-4 py-2 <?= $categoryFilter == $cat['cat_id'] ? 'bg-blue-100 text-blue-700' : 'hover:bg-gray-100' ?> rounded-lg font-medium">
                                <?= e($cat['cat_name']) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Articles Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Titre
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Auteur
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catégorie
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Commentaires
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($articles as $article): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900"><?= e($article['title']) ?></div>
                                        <div class="text-sm text-gray-500"><?= e(truncate($article['content'], 60)) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br <?= getAvatarColor($article['username']) ?> rounded-full flex items-center justify-center text-white font-bold mr-2 text-sm">
                                                <?= getInitial($article['first_name']) ?>
                                            </div>
                                            <span class="text-sm"><?= e($article['first_name'] . ' ' . $article['last_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= e($article['cat_name']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= formatDate($article['creation_date']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $article['comment_count'] ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <?php if (hasAnyRole(['admin', 'editor']) || $article['username'] === $username): ?>
                                            <a href="?edit=<?= $article['article_id'] ?>#editForm" class="text-blue-600 hover:text-blue-900">Éditer</a>
                                            <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">
                                                <input type="hidden" name="article_id" value="<?= $article['article_id'] ?>">
                                                <button type="submit" name="delete_article" class="text-red-600 hover:text-red-900">Supprimer</button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($articles)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Aucun article trouvé.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <?php if ($pagination['total_pages'] > 1): ?>
                        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4">
                            <div class="text-sm text-gray-700">
                                Affichage de <span class="font-medium"><?= ($pagination['offset'] + 1) ?></span> à 
                                <span class="font-medium"><?= min($pagination['offset'] + $pagination['per_page'], $pagination['total']) ?></span> sur 
                                <span class="font-medium"><?= $pagination['total'] ?></span> articles
                            </div>
                            <div class="flex items-center gap-2">
                                <?php if ($pagination['has_prev']): ?>
                                <a href="?page=<?= $page - 1 ?><?= $categoryFilter ? '&category=' . $categoryFilter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Précédent
                                </a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <?php if ($i == 1 || $i == $pagination['total_pages'] || abs($i - $page) <= 2): ?>
                                    <a href="?page=<?= $i ?><?= $categoryFilter ? '&category=' . $categoryFilter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                                       class="px-3 py-1 <?= $i == $page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50' ?> rounded">
                                        <?= $i ?>
                                    </a>
                                    <?php elseif (abs($i - $page) == 3): ?>
                                    <span class="px-2">...</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['has_next']): ?>
                                <a href="?page=<?= $page + 1 ?><?= $categoryFilter ? '&category=' . $categoryFilter : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Suivant
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Edit Form -->
                    <?php if ($editArticle): ?>
                    <div id="editForm" class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Modifier l'article</h3>
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="article_id" value="<?= $editArticle['article_id'] ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                                <input type="text" name="title" value="<?= e($editArticle['title']) ?>" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                                <textarea name="content" rows="6" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?= e($editArticle['content']) ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                                <select name="cat_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Sélectionner une catégorie</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['cat_id'] ?>" <?= $editArticle['cat_id'] == $cat['cat_id'] ? 'selected' : '' ?>>
                                        <?= e($cat['cat_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <a href="articles.php" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Annuler
                                </a>
                                <button type="submit" name="update_article" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </main>

        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Nouvel article</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Titre de l'article">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
                    <textarea name="content" rows="8" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Contenu de l'article..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="cat_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner une catégorie</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['cat_id'] ?>"><?= e($cat['cat_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" name="create_article" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Créer l'article
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
