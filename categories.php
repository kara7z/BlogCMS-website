<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Catégories</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        
        <!-- SIDEBAR (Same as dashboard) -->
        <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold">BlogCMS</h2>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="articles.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Articles</span>
                </a>

                <a href="categories.php" class="flex items-center gap-3 p-3 rounded-lg bg-blue-600 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="font-medium">Catégories</span>
                </a>

                <a href="comments.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span class="font-medium">Commentaires</span>
                </a>

                <a href="users.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-medium">Utilisateurs</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <a href="logout.php" class="flex items-center gap-3 p-3 hover:bg-gray-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Catégories</h2>
                    <div class="flex items-center gap-4">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle catégorie
                        </button>
                        <input 
                            type="text" 
                            placeholder="Rechercher une catégorie..."
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-64"
                        />
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                            A
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="space-y-6">
                    
                    <!-- Create Category Form -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Ajouter une nouvelle catégorie</h3>
                        <form class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom de la catégorie
                                    </label>
                                    <input type="text" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Ex: Programmation, Design, Marketing..."
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Slug (URL)
                                    </label>
                                    <input type="text" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Ex: programmation"
                                    >
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                    rows="3"
                                    placeholder="Description de la catégorie..."
                                ></textarea>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Créer la catégorie
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories List -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800">Liste des catégories (8)</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            <!-- Category 1 -->
                            <div class="px-6 py-4 hover:bg-gray-50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Programmation</h4>
                                        <p class="text-sm text-gray-500">12 articles • Slug: programmation</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        Active
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Category 2 -->
                            <div class="px-6 py-4 hover:bg-gray-50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Design</h4>
                                        <p class="text-sm text-gray-500">8 articles • Slug: design</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Active
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">Éditer</button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Supprimer</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Category 3 -->
                            <div class="px-6 py-4 hover:bg-gray-50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Marketing</h4>
                                        <p class="text-sm text-gray-500">15 articles • Slug: marketing</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Active
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">Éditer</button>
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>
</html>