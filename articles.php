<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Articles</title>
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

                <a href="articles.php" class="flex items-center gap-3 p-3 rounded-lg bg-blue-600 text-white">
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
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Articles</h2>
                    <div class="flex items-center gap-4">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvel article
                        </button>
                        <input 
                            type="text" 
                            placeholder="Rechercher un article..."
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
                    
                    <!-- Filter Bar -->
                    <div class="flex flex-wrap items-center justify-between bg-white p-4 rounded-xl shadow-sm">
                        <div class="flex items-center gap-4">
                            <button class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium">
                                Tous (45)
                            </button>
                            <button class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium">
                                Publiés (38)
                            </button>
                            <button class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium">
                                Brouillons (7)
                            </button>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>Trier par: Date</option>
                                <option>Titre</option>
                                <option>Auteur</option>
                                <option>Popularité</option>
                            </select>
                        </div>
                    </div>

                    <!-- Articles Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </th>
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
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <!-- Article 1 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Introduction à PHP 8</div>
                                                <div class="text-sm text-gray-500">Vues: 1,245</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold mr-2">
                                                A
                                            </div>
                                            <span>Ahmed El Amrani</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Programmation
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        01/12/2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Publié
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <button class="text-blue-600 hover:text-blue-900">Éditer</button>
                                            <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Article 2 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600 mr-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Guide Tailwind CSS</div>
                                                <div class="text-sm text-gray-500">Vues: 892</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold mr-2">
                                                F
                                            </div>
                                            <span>Fatima Zahra</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Design
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        02/12/2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Brouillon
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <button class="text-blue-600 hover:text-blue-900">Éditer</button>
                                            <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Article 3 -->
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 mr-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">SEO pour débutants</div>
                                                <div class="text-sm text-gray-500">Vues: 1,567</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 font-bold mr-2">
                                                K
                                            </div>
                                            <span>Karim Benjelloun</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Marketing
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        30/11/2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Publié
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <button class="text-blue-600 hover:text-blue-900">Éditer</button>
                                            <button class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4">
                            <div class="text-sm text-gray-700">
                                Affichage de <span class="font-medium">1</span> à <span class="font-medium">3</span> sur <span class="font-medium">45</span> articles
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Précédent
                                </button>
                                <button class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">2</button>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">3</button>
                                <span class="px-2">...</span>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">8</button>
                                <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">
                                    Suivant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

</body>
</html>