<?php
// =============================================================
// PARTIE PHP : TRAITEMENT ADAPTÉ À VOTRE BASE DE DONNÉES
// =============================================================
session_start();

// Configuration Base de données
$host = 'localhost';
$db   = 'base';
$user = 'root';
$pass = '1234';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$error_message = '';
$email_value = '';

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Erreur de connexion DB.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    
    $email_value = trim($_POST['email']);
    $password_input = $_POST['password'];

    // Validation PHP côté serveur (Sécurité supplémentaire)
    if (!filter_var($email_value, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Le format de l'email est invalide.";
    } elseif (strlen($password_input) < 6) {
        $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Recherche de l'utilisateur dans la table 'user' avec la colonne 'email'
        $stmt = $pdo->prepare("SELECT username, email, password, first_name, last_name, user_role FROM user WHERE email = ?");
        $stmt->execute([$email_value]);
        $user = $stmt->fetch();

        if ($user) {
            // Vérification du mot de passe avec password_verify
            if (password_verify($password_input, $user['password'])) {
                // Connexion réussie - Stocker les informations en session
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['logged_in'] = true;
                
                // Redirection selon le rôle de l'utilisateur
                switch($user['user_role']) {
                    case 'admin':
                        header('Location: index.php');
                        break;
                    case 'editor':
                        header('Location: index.php');
                        break;
                    case 'author':
                        header('Location: index.php');
                        break;
                    case 'subscriber':
                        header('Location: index.php');
                        break;
                    default:
                        header('Location: index.php');
                        break;
                }
                exit;
            } else {
                $error_message = "Identifiants incorrects. Vérifiez votre email et mot de passe.";
            }
        } else {
            $error_message = "Aucun compte n'existe avec cet email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogCMS - Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Animation de secousse pour les erreurs */
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 to-indigo-800 flex items-center justify-center h-screen w-full">

    <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md border border-gray-100 <?php echo $error_message ? 'shake' : ''; ?>">
        
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">BlogCMS</h1>
            <p class="text-gray-500 mt-2 text-sm">Bienvenue, connectez-vous pour continuer.</p>
        </div>

        <?php if ($error_message): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r text-sm shadow-sm" role="alert">
                <p class="font-bold">Erreur</p>
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6" id="loginForm" novalidate>
            
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm"
                        placeholder="nom@exemple.com"
                        value="<?= htmlspecialchars($email_value) ?>"
                        required
                    >
                </div>
                <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition shadow-sm"
                        placeholder="••••••••"
                        required
                    >
                </div>
                <p id="passwordError" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-600 cursor-pointer hover:text-gray-800 transition">
                    <input type="checkbox" name="remember" class="mr-2 rounded text-indigo-600 focus:ring-indigo-500">
                    Se souvenir de moi
                </label>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Oublié ?</a>
            </div>

            <button 
                type="submit" 
                name="login"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Pas encore de compte ? <a href="register.php" class="text-indigo-600 hover:text-indigo-800 font-medium">S'inscrire</a></p>
        </div>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const form = document.getElementById('loginForm');

        // Regex pour l'email (Format standard RFC 5322)
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        
        // Regex pour le mot de passe (minimum 6 caractères)
        const passwordRegex = /^.{6,}$/;

        // Fonction générique pour afficher l'erreur
        function validateInput(input, regex, errorElement, errorMessage) {
            if (input.value === '') {
                input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                input.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                errorElement.classList.add('hidden');
                return false;
            }
            
            if (!regex.test(input.value)) {
                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                input.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                errorElement.textContent = errorMessage;
                errorElement.classList.remove('hidden');
                return false;
            } else {
                input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                input.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                errorElement.classList.add('hidden');
                return true;
            }
        }

        // Validation en temps réel pendant la frappe
        emailInput.addEventListener('input', () => {
            if (emailInput.value !== '') {
                validateInput(emailInput, emailRegex, emailError, "Veuillez entrer une adresse email valide.");
            }
        });

        passwordInput.addEventListener('input', () => {
            if (passwordInput.value !== '') {
                validateInput(passwordInput, passwordRegex, passwordError, "Le mot de passe doit contenir au moins 6 caractères.");
            }
        });

        // Vérification quand l'utilisateur quitte le champ (blur)
        emailInput.addEventListener('blur', () => {
            validateInput(emailInput, emailRegex, emailError, "Veuillez entrer une adresse email valide.");
        });

        passwordInput.addEventListener('blur', () => {
            validateInput(passwordInput, passwordRegex, passwordError, "Le mot de passe doit contenir au moins 6 caractères.");
        });

        // Vérification avant l'envoi du formulaire
        form.addEventListener('submit', (e) => {
            let isValid = true;

            // Vérification de l'email
            if (emailInput.value === '') {
                emailError.textContent = "L'email est requis.";
                emailError.classList.remove('hidden');
                emailInput.classList.add('border-red-500');
                isValid = false;
            } else {
                const isEmailValid = validateInput(emailInput, emailRegex, emailError, "Veuillez entrer une adresse email valide.");
                if (!isEmailValid) isValid = false;
            }

            // Vérification du mot de passe
            if (passwordInput.value === '') {
                passwordError.textContent = "Le mot de passe est requis.";
                passwordError.classList.remove('hidden');
                passwordInput.classList.add('border-red-500');
                isValid = false;
            } else {
                const isPasswordValid = validateInput(passwordInput, passwordRegex, passwordError, "Le mot de passe doit contenir au moins 6 caractères.");
                if (!isPasswordValid) isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); // Empêche l'envoi si validation échoue
            }
        });
    </script>
</body>
</html>
