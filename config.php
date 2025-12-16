<?php
// =============================================================
// DATABASE CONFIGURATION
// =============================================================

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'base');
define('DB_USER', 'root');
define('DB_PASS', '1234');
define('DB_CHARSET', 'utf8mb4');

// Création de la connexion PDO
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // En production, ne pas afficher l'erreur brute
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Fonction pour obtenir la connexion PDO
function getDB() {
    global $pdo;
    return $pdo;
}
?>
