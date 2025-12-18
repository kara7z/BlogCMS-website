<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$db = getDB();
$isLoggedIn = isLoggedIn();
$username = $_SESSION['username'] ?? null;

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$article_id) {
    redirect('index.php', 'Article introuvable.', 'error');
}

// Ajouter un commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $comment_content = trim($_POST['comment_content']);
    
    if (!empty($comment_content)) {
        $comment_username = $isLoggedIn ? $username : null;
        $stmt = $db->prepare("INSERT INTO comment (comment_content, username, article_id) VALUES (?, ?, ?)");
        $stmt->execute([$comment_content, $comment_username, $article_id]);
        redirect("article_view.php?id=$article_id", 'Commentaire ajouté avec succès!');
    } else {
        redirect("article_view.php?id=$article_id", 'Le commentaire ne peut pas être vide.', 'error');
    }
}

// Supprimer un commentaire
if (isset($_POST['delete_comment']) && hasAnyRole(['admin', 'editor'])) {
    $comment_id = $_POST['comment_id'];
    $stmt = $db->prepare("DELETE FROM comment WHERE comment_id = ?");
    $stmt->execute([$comment_id]);
    redirect("article_view.php?id=$article_id", 'Commentaire supprimé!');
}

// Récupérer l'article
$stmt = $db->prepare("
    SELECT a.*, u.first_name, u.last_name, c.cat_name
    FROM article a
    LEFT JOIN user u ON a.username = u.username
    LEFT JOIN category c ON a.cat_id = c.cat_id
    WHERE a.article_id = ?
");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    redirect('index.php', 'Article introuvable.', 'error');
}

// Récupérer les commentaires
$stmt = $db->prepare("
    SELECT c.*, u.first_name, u.last_name
    FROM comment c
    LEFT JOIN user u ON c.username = u.username
    WHERE c.article_id = ?
    ORDER BY c.creation_date DESC
");
$stmt->execute([$article_id]);
$comments = $stmt->fetchAll();

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($article['title']) ?> - BlogCMS</title>
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
                        <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="index.php" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux articles
        </a>

        <?php if ($flash): ?>
        <div class="mb-6 bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-50 border-l-4 border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-500 p-4 rounded-r">
            <p class="text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700"><?= e($flash['message']) ?></p>
        </div>
        <?php endif; ?>

        <!-- Article -->
        <article class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-4 py-2 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">
                        <?= e($article['cat_name']) ?>
                    </span>
                    <span class="text-sm text-gray-500"><?= formatDate($article['creation_date']) ?></span>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-6"><?= e($article['title']) ?></h1>

                <div class="flex items-center gap-3 mb-8 pb-6 border-b">
                    <div class="w-12 h-12 bg-gradient-to-br <?= getAvatarColor($article['username']) ?> rounded-full flex items-center justify-center text-white font-bold text-lg">
                        <?= getInitial($article['first_name']) ?>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900"><?= e($article['first_name'] . ' ' . $article['last_name']) ?></p>
                        <p class="text-sm text-gray-500">Publié le <?= formatDateTime($article['creation_date']) ?></p>
                    </div>
                </div>

                <div class="prose prose-lg max-w-none">
                    <?= nl2br(e($article['content'])) ?>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Commentaires (<?= count($comments) ?>)
            </h2>

            <!-- Add Comment Form -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <?= $isLoggedIn ? 'Ajouter un commentaire' : 'Commenter en tant qu\'invité' ?>
                </h3>
                <form method="POST" class="space-y-4">
                    <?php if (!$isLoggedIn): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            💡 <strong>Astuce :</strong> Vous commentez en tant qu'invité. 
                            <a href="login.php" class="underline hover:text-blue-900">Connectez-vous</a> pour que votre nom apparaisse.
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <textarea name="comment_content" rows="4" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="<?= $isLoggedIn ? 'Écrivez votre commentaire...' : 'Écrivez votre commentaire en tant qu\'invité...' ?>"></textarea>
                    
                    <button type="submit" name="add_comment" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        <?= $isLoggedIn ? 'Publier le commentaire' : 'Publier en tant qu\'invité' ?>
                    </button>
                </form>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
                <?php foreach ($comments as $comment): ?>
                <div class="border-l-4 border-indigo-200 bg-gray-50 p-6 rounded-r-lg">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br <?= getAvatarColor($comment['username'] ?? 'Guest') ?> rounded-full flex items-center justify-center text-white font-bold">
                                <?php if ($comment['username']): ?>
                                    <?= getInitial($comment['first_name'] ?: $comment['username']) ?>
                                <?php else: ?>
                                    I
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    <?php if ($comment['username']): ?>
                                        <?php if ($comment['first_name'] && $comment['last_name']): ?>
                                            <?= e($comment['first_name'] . ' ' . $comment['last_name']) ?>
                                        <?php else: ?>
                                            <?= e($comment['username']) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Invité
                                    <?php endif; ?>
                                </p>
                                <p class="text-sm text-gray-500"><?= formatDateTime($comment['creation_date']) ?></p>
                            </div>
                        </div>
                        <?php if (hasAnyRole(['admin', 'editor'])): ?>
                        <form method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                            <button type="submit" name="delete_comment" class="text-red-600 hover:text-red-800 text-sm">
                                Supprimer
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <p class="text-gray-700 leading-relaxed"><?= nl2br(e($comment['comment_content'])) ?></p>
                </div>
                <?php endforeach; ?>

                <?php if (empty($comments)): ?>
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
