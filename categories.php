<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Vérifier si l'utilisateur est connecté et a les permissions
requireRole(['admin', 'editor']);

$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];
$db = getDB();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Créer une catégorie
    if (isset($_POST['create_category'])) {
        $cat_name = trim($_POST['cat_name']);
        $description = trim($_POST['description']);
        
        if (!empty($cat_name)) {
            $stmt = $db->prepare("INSERT INTO category (cat_name, description) VALUES (?, ?)");
            if ($stmt->execute([$cat_name, $description])) {
                redirect('categories.php', 'Catégorie créée avec succès!');
            }
        }
    }
    
    // Modifier une catégorie
    if (isset($_POST['update_category'])) {
        $cat_id = $_POST['cat_id'];
        $cat_name = trim($_POST['cat_name']);
        $description = trim($_POST['description']);
        
        if (!empty($cat_name)) {
            $stmt = $db->prepare("UPDATE category SET cat_name = ?, description = ? WHERE cat_id = ?");
            if ($stmt->execute([$cat_name, $description, $cat_id])) {
                redirect('categories.php', 'Catégorie modifiée avec succès!');
            }
        }
    }
    
    // Supprimer une catégorie
    if (isset($_POST['delete_category']) && hasRole('admin')) {
        $cat_id = $_POST['cat_id'];
        
        // Vérifier s'il y a des articles dans cette catégorie
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM article WHERE cat_id = ?");
        $stmt->execute([$cat_id]);
        $count = $stmt->fetch()['count'];
        
        if ($count > 0) {
            redirect('categories.php', 'Impossible de supprimer cette catégorie car elle contient des articles.', 'error');
        } else {
            $stmt = $db->prepare("DELETE FROM category WHERE cat_id = ?");
            if ($stmt->execute([$cat_id])) {
                redirect('categories.php', 'Catégorie supprimée avec succès!');
            }
        }
    }
}

// Récupérer toutes les catégories avec le nombre d'articles
$stmt = $db->query("
    SELECT c.*, COUNT(a.article_id) as article_count
    FROM category c
    LEFT JOIN article a ON c.cat_id = a.cat_id
    GROUP BY c.cat_id
    ORDER BY c.cat_name
");
$categories = $stmt->fetchAll();

// Catégorie à éditer
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM category WHERE cat_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

$flash = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Catégories</title>
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
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Catégories</h2>
                    <div class="flex items-center gap-4">
                        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle catégorie
                        </button>
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
                    
                    <!-- Edit Form -->
                    <?php if ($editCategory): ?>
                    <div id="editForm" class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Modifier la catégorie</h3>
                        <form method="POST" class="space-y-4">
                            <input type="hidden" name="cat_id" value="<?= $editCategory['cat_id'] ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie</label>
                                    <input type="text" name="cat_name" value="<?= e($editCategory['cat_name']) ?>" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?= e($editCategory['description']) ?></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-2">
                                <a href="categories.php" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Annuler
                                </a>
                                <button type="submit" name="update_category" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- Categories List -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800">Liste des catégories (<?= count($categories) ?>)</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($categories as $category): ?>
                            <div class="px-6 py-4 hover:bg-gray-50 flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900"><?= e($category['cat_name']) ?></h4>
                                        <p class="text-sm text-gray-500 mt-1"><?= e($category['description']) ?></p>
                                        <p class="text-xs text-gray-500 mt-1"><?= $category['article_count'] ?> article(s)</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 ml-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        Active
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <a href="?edit=<?= $category['cat_id'] ?>#editForm" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <?php if (hasRole('admin')): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                            <input type="hidden" name="cat_id" value="<?= $category['cat_id'] ?>">
                                            <button type="submit" name="delete_category" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($categories)): ?>
                            <div class="px-6 py-8 text-center text-gray-500">
                                Aucune catégorie trouvée.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-2xl mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Nouvelle catégorie</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la catégorie</label>
                        <input type="text" name="cat_name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ex: Technologie, Sport, Culture...">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Description de la catégorie..."></textarea>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" name="create_category" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Créer la catégorie
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
