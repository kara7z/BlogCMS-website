<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
requireRole('admin');

$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];
$db = getDB();

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_user'])) {
        $new_username = trim($_POST['username']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['user_role'];
        
        try {
            $stmt = $db->prepare("INSERT INTO user (username, first_name, last_name, email, password, user_role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$new_username, $first_name, $last_name, $email, $password, $role]);
            redirect('users.php', 'Utilisateur créé!');
        } catch (PDOException $e) {
            redirect('users.php', 'Erreur: cet email ou username existe déjà.', 'error');
        }
    }
    
    if (isset($_POST['delete_user'])) {
        $del_username = $_POST['username'];
        if ($del_username !== $username) {
            // Supprimer les commentaires
            $stmt = $db->prepare("DELETE FROM comment WHERE username = ?");
            $stmt->execute([$del_username]);
            // Réassigner ou supprimer les articles
            $stmt = $db->prepare("UPDATE article SET username = NULL WHERE username = ?");
            $stmt->execute([$del_username]);
            // Supprimer l'utilisateur
            $stmt = $db->prepare("DELETE FROM user WHERE username = ?");
            $stmt->execute([$del_username]);
            redirect('users.php', 'Utilisateur supprimé!');
        }
    }
}

// Récupérer tous les utilisateurs
$stmt = $db->query("
    SELECT u.*, COUNT(DISTINCT a.article_id) as article_count
    FROM user u
    LEFT JOIN article a ON u.username = a.username
    GROUP BY u.username
    ORDER BY u.creation_date DESC
");
$users = $stmt->fetchAll();

// Statistiques par rôle
$roleStats = [];
foreach ($users as $user) {
    $role = $user['user_role'];
    $roleStats[$role] = ($roleStats[$role] ?? 0) + 1;
}

$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Utilisateurs</title>
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
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Utilisateurs</h2>
                    <div class="flex items-center gap-4">
                        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvel utilisateur
                        </button>
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
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Total Utilisateurs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= count($users) ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Administrateurs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $roleStats['admin'] ?? 0 ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Éditeurs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $roleStats['editor'] ?? 0 ?></p>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <p class="text-sm text-gray-600 mb-1">Auteurs</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $roleStats['author'] ?? 0 ?></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date inscription</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br <?= getAvatarColor($user['username']) ?> rounded-full flex items-center justify-center text-white font-bold text-lg mr-3">
                                            <?= getInitial($user['first_name'] ?: $user['username']) ?>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></div>
                                            <div class="text-sm text-gray-500"><?= e($user['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?= getRoleBadgeClass($user['user_role']) ?>">
                                        <?= getRoleNameFr($user['user_role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= formatDate($user['creation_date']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $user['article_count'] ?> articles</td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <?php if ($user['username'] !== $username): ?>
                                    <form method="POST" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        <input type="hidden" name="username" value="<?= e($user['username']) ?>">
                                        <button type="submit" name="delete_user" class="text-red-600 hover:text-red-900">Supprimer</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Nouvel utilisateur</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom d'utilisateur</label>
                        <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                        <input type="text" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                        <input type="text" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
                        <select name="user_role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="subscriber">Abonné</option>
                            <option value="author">Auteur</option>
                            <option value="editor">Éditeur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Annuler</button>
                    <button type="submit" name="create_user" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Créer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
