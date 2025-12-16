<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
requireLogin();

$username = $_SESSION['username'];
$email = $_SESSION['email'];
$first_name = $_SESSION['first_name'] ?? 'Utilisateur';
$last_name = $_SESSION['last_name'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'subscriber';

$db = getDB();

// Stats
$stmt = $db->query("SELECT COUNT(*) as total FROM article");
$totalArticles = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM user");
$totalUsers = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM comment");
$totalComments = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM category");
$totalCategories = $stmt->fetch()['total'];

// Articles récents
$stmt = $db->prepare("
    SELECT a.*, u.first_name, u.last_name, c.cat_name,
           (SELECT COUNT(*) FROM comment WHERE article_id = a.article_id) as comment_count
    FROM article a
    LEFT JOIN user u ON a.username = u.username
    LEFT JOIN category c ON a.cat_id = c.cat_id
    ORDER BY a.creation_date DESC
    LIMIT 5
");
$stmt->execute();
$recentArticles = $stmt->fetchAll();

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Dashboard</title>
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
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="flex items-center gap-4">
                        <a href="index.php" class="text-indigo-600 hover:text-indigo-800">Voir le site</a>
                        <span class="text-gray-700">Bonjour, <strong><?= e($first_name . ' ' . $last_name) ?></strong></span>
                        <div class="w-10 h-10 bg-gradient-to-br <?= getAvatarColor($username) ?> rounded-full flex items-center justify-center text-white font-bold">
                            <?= getInitial($first_name ?: $username) ?>
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
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Articles</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalArticles ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalUsers ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Commentaires</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalComments ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Catégories</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $totalCategories ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Articles récents</h3>
                        <a href="articles.php" class="text-sm text-blue-600 hover:text-blue-800">Voir tout</a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <?php foreach ($recentArticles as $article): ?>
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <h4 class="font-medium text-gray-900 mb-1"><?= e($article['title']) ?></h4>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Par <?= e($article['first_name'] . ' ' . $article['last_name']) ?></span>
                                <span><?= formatDate($article['creation_date']) ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
