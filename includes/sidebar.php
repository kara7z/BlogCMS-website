<aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-2xl font-bold">BlogCMS</h2>
        <p class="text-sm text-gray-400 mt-1"><?= getRoleNameFr($user_role) ?></p>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="index.php" class="flex items-center gap-3 p-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="articles.php" class="flex items-center gap-3 p-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'articles.php' ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="font-medium">Articles</span>
        </a>

        <?php if (hasAnyRole(['admin', 'editor'])): ?>
        <a href="categories.php" class="flex items-center gap-3 p-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span class="font-medium">Catégories</span>
        </a>
        <?php endif; ?>

        <a href="comments.php" class="flex items-center gap-3 p-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'comments.php' ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="font-medium">Commentaires</span>
        </a>

        <?php if (hasRole('admin')): ?>
        <a href="users.php" class="flex items-center gap-3 p-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-blue-600 text-white' : 'hover:bg-gray-700' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="font-medium">Utilisateurs</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="p-4 border-t border-gray-700">
        <a href="?logout" class="flex items-center gap-3 p-3 hover:bg-gray-700 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>