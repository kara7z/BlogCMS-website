<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-96 border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">BlogCMS</h1>
            <p class="text-gray-500 mt-2">Connectez-vous à votre espace</p>
        </div>

        <form action="index.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input 
                    type="email" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="admin@blogcms.com"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input 
                    type="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="••••••••"
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-600 cursor-pointer">
                    <input type="checkbox" class="mr-2 rounded text-blue-600 focus:ring-blue-500">
                    Se souvenir de moi
                </label>
                <a href="#" class="text-blue-600 hover:underline">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                Se connecter
            </button>
        </form>
    </div>

</body>
</html>