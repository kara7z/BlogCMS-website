<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <h2 class="text-2xl font-bold">BlogCMS</h2>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="index.php" class="flex items-center gap-3 p-3 rounded-lg bg-blue-600 text-white">
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

                <a href="categories.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
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
                    <h2 class="text-xl font-semibold text-gray-800">Tableau de bord</h2>
                    <div class="flex items-center gap-4">
                        <input 
                            type="text" 
                            placeholder="Rechercher..."
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
                    
                    <!-- Statistiques -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <!-- Card 1 -->
                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Articles</p>
                                    <p class="text-3xl font-bold text-gray-800">45</p>
                                </div>
                                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Commentaires</p>
                                    <p class="text-3xl font-bold text-gray-800">128</p>
                                </div>
                                <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Utilisateurs</p>
                                    <p class="text-3xl font-bold text-gray-800">23</p>
                                </div>
                                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Catégories</p>
                                    <p class="text-3xl font-bold text-gray-800">8</p>
                                </div>
                                <div class="p-3 bg-orange-100 text-orange-600 rounded-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Articles récents et Commentaires -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Articles récents -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Articles récents</h3>
                            <div class="space-y-3">
                                
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Introduction à PHP 8</h4>
                                        <p class="text-sm text-gray-600">Ahmed El Amrani • 01/12/2024</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Publié
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-800">Guide Tailwind CSS</h4>
                                        <p class="text-sm text-gray-600">Fatima Zahra • 02/12/2024</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Brouillon
                                    </span>
                                </div>

                            </div>
                        </div>

                        <!-- Commentaires en attente -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Commentaires en attente</h3>
                            <div class="space-y-3">
                                
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-gray-800">Karim</span>
                                        <span class="text-xs text-gray-500">02/12/2024</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">Excellent article, très informatif!</p>
                                    <div class="flex gap-2">
                                        <button class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-medium hover:bg-green-200">
                                            Approuver
                                        </button>
                                        <button class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-medium hover:bg-red-200">
                                            Rejeter
                                        </button>
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