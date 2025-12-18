<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
requireLogin();

$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];
$db = getDB();

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_comment'])) {
        $comment_id = $_POST['comment_id'];
        
        $stmt = $db->prepare("SELECT username FROM comment WHERE comment_id = ?");
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch();
        
        if ($comment && (hasAnyRole(['admin', 'editor']) || $comment['username'] === $username)) {
            $stmt = $db->prepare("DELETE FROM comment WHERE comment_id = ?");
            $stmt->execute([$comment_id]);
            redirect('comments.php', 'Commentaire supprimé!');
        } else {
            redirect('comments.php', 'Vous ne pouvez pas supprimer ce commentaire.', 'error');
        }
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 15;

$whereClause = '';
$params = [];

if (!hasAnyRole(['admin', 'editor'])) {
    $whereClause = 'WHERE c.username = ?';
    $params[] = $username;
}

$stmt = $db->prepare("SELECT COUNT(*) as total FROM comment c $whereClause");
$stmt->execute($params);
$total = $stmt->fetch()['total'];

$pagination = paginate($total, $perPage, $page);

$stmt = $db->prepare("
    SELECT c.*, u.first_name, u.last_name, a.title as article_title
    FROM comment c
    LEFT JOIN user u ON c.username = u.username
    LEFT JOIN article a ON c.article_id = a.article_id
    $whereClause
    ORDER BY c.creation_date DESC
    LIMIT {$pagination['per_page']} OFFSET {$pagination['offset']}
");
$stmt->execute($params);
$comments = $stmt->fetchAll();

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Commentaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'includes/sidebar.php'; ?>
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <?= hasAnyRole(['admin', 'editor']) ? 'Gestion des Commentaires' : 'Mes Commentaires' ?>
                    </h2>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            <?= hasAnyRole(['admin', 'editor']) ? "Total: $total" : "Mes commentaires: $total" ?>
                        </span>
                        <div class="w-10 h-10 bg-gradient-to-br <?= getAvatarColor($username) ?> rounded-full flex items-center justify-center text-white font-bold">
                            <?= getInitial($_SESSION['first_name'] ?: $username) ?>
                        </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-6">
                <?php if ($flash): ?>
                <div class="mb-6 bg-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-50 border-l-4 border-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-500 p-4 rounded-r">
                    <p class="text-<?= $flash['type'] === 'success' ? 'green' : 'red' ?>-700"><?= e($flash['message']) ?></p>
                </div>
                <?php endif; ?>
                <div class="space-y-4">
                    <?php foreach ($comments as $comment): ?>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br <?= getAvatarColor($comment['username'] ?? 'Guest') ?> rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    <?php if ($comment['username']): ?>
                                        <?= getInitial($comment['first_name'] ?: $comment['username']) ?>
                                    <?php else: ?>
                                        I
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">
                                        <?php if ($comment['username']): ?>
                                            <?php if ($comment['first_name'] && $comment['last_name']): ?>
                                                <?= e($comment['first_name'] . ' ' . $comment['last_name']) ?>
                                            <?php else: ?>
                                                <?= e($comment['username']) ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            Invité
                                        <?php endif; ?>
                                    </h4>
                                    <p class="text-sm text-gray-500"><?= e($comment['username'] ?? 'Anonyme') ?></p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500"><?= formatDate($comment['creation_date']) ?></span>
                        </div>
                        <div class="mb-4">
                            <p class="text-gray-700 mb-2"><?= e($comment['comment_content']) ?></p>
                            <div class="text-sm text-gray-500">
                                Sur l'article: <a href="article_view.php?id=<?= $comment['article_id'] ?>" class="text-blue-600 hover:underline">
                                    <?= e($comment['article_title']) ?>
                                </a>
                            </div>
                        </div>
                        <?php if (hasAnyRole(['admin', 'editor']) || $comment['username'] === $username): ?>
                        <div class="flex items-center justify-end">
                            <form method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                <button type="submit" name="delete_comment" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($comments)): ?>
                    <div class="bg-white rounded-xl shadow-md p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 text-lg">
                            <?= hasAnyRole(['admin', 'editor']) ? 'Aucun commentaire pour le moment.' : 'Vous n\'avez pas encore de commentaires.' ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ($pagination['total_pages'] > 1): ?>
                <div class="mt-6 flex items-center justify-center gap-2">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Précédent</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <?php if ($i == 1 || $i == $pagination['total_pages'] || abs($i - $page) <= 2): ?>
                        <a href="?page=<?= $i ?>" class="px-3 py-1 <?= $i == $page ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50' ?> rounded"><?= $i ?></a>
                        <?php elseif (abs($i - $page) == 3): ?><span class="px-2">...</span><?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Suivant</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>