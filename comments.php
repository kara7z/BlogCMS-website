<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Commentaires</title>
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

                <a href="categories.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span class="font-medium">Catégories</span>
                </a>

                <a href="comments.php" class="flex items-center gap-3 p-3 rounded-lg bg-blue-600 text-white">
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
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Commentaires</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                Total: 128
                            </span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                En attente: 3
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                Approuvés: 125
                            </span>
                        </div>
                        <input 
                            type="text" 
                            placeholder="Rechercher un commentaire..."
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
                    <div class="flex flex-wrap items-center gap-4 bg-white p-4 rounded-xl shadow-sm">
                        <button class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium">
                            Tous (128)
                        </button>
                        <button class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium flex items-center gap-2">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            En attente (3)
                        </button>
                        <button class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            Approuvés (125)
                        </button>
                        <button class="px-4 py-2 hover:bg-gray-100 rounded-lg font-medium flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            Rejetés (0)
                        </button>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-4">
                        
                        <!-- Comment 1 (Pending) -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
                                        K
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Karim</h4>
                                        <p class="text-sm text-gray-500">karim@example.com</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">02/12/2024 14:30</span>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        En attente
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-700 mb-2">Excellent article, très informatif! J'ai particulièrement apprécié la section sur les nouvelles fonctionnalités de PHP 8.</p>
                                <div class="text-sm text-gray-500">
                                    Sur l'article: <a href="#" class="text-blue-600 hover:underline">Introduction à PHP 8</a>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approuver
                                    </button>
                                    <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rejeter
                                    </button>
                                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                                        Répondre
                                    </button>
                                </div>
                                <button class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </div>
                        </div>

                        <!-- Comment 2 (Approved) -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-lg">
                                        S
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Sara</h4>
                                        <p class="text-sm text-gray-500">sara@example.com</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">01/12/2024 10:15</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Approuvé
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-700 mb-2">Merci pour ce guide complet sur Tailwind CSS. Les exemples sont très clairs et pratiques.</p>
                                <div class="text-sm text-gray-500">
                                    Sur l'article: <a href="#" class="text-blue-600 hover:underline">Guide Tailwind CSS</a>
                                </div>
                            </div>
                            
                            <!-- Reply -->
                            <div class="ml-12 mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">
                                        A
                                    </div>
                                    <span class="font-medium text-sm">Admin</span>
                                    <span class="text-xs text-gray-500">02/12/2024 09:00</span>
                                </div>
                                <p class="text-sm text-gray-700">Merci Sara pour votre retour! N'hésitez pas à suggérer d'autres sujets que vous aimeriez voir couverts.</p>
                            </div>
                            
                            <div class="flex items-center justify-end mt-4">
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Masquer la réponse
                                </button>
                            </div>
                        </div>

                        <!-- Comment 3 (Pending) -->
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-lg">
                                        M
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Mohamed</h4>
                                        <p class="text-sm text-gray-500">mohamed@example.com</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500">03/12/2024 16:45</span>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                        En attente
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-700 mb-2">Je cherche des informations plus avancées sur les optimisations SEO techniques. Est-ce que vous prévoyez un article là-dessus?</p>
                                <div class="text-sm text-gray-500">
                                    Sur l'article: <a href="#" class="text-blue-600 hover:underline">SEO pour débutants</a>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                        Approuver
                                    </button>
                                    <button class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                        Rejeter
                                    </button>
                                    <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                                        Répondre
                                    </button>
                                </div>
                                <button class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm">
                        <div class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">1</span> à <span class="font-medium">3</span> sur <span class="font-medium">128</span> commentaires
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
            </main>

        </div>
    </div>

</body>
</html>