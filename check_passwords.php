<?php
// =============================================================
// SCRIPT DE VÉRIFICATION ET MISE À JOUR DES MOTS DE PASSE
// =============================================================

require_once 'config.php';

echo "<h1>Vérification des mots de passe</h1>";

// Le hash existant dans votre base de données
$existing_hash = '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW';

echo "<h2>Test 1: Vérification du hash existant</h2>";
echo "Hash dans la DB: <code>$existing_hash</code><br>";

// Tester plusieurs mots de passe possibles
$test_passwords = ['secret', 'password', '123456', 'admin', 'Secret', 'SECRET'];

foreach ($test_passwords as $password) {
    if (password_verify($password, $existing_hash)) {
        echo "<p style='color: green;'>✅ Le hash correspond au mot de passe: <strong>$password</strong></p>";
    } else {
        echo "<p style='color: red;'>❌ Le hash ne correspond PAS à: $password</p>";
    }
}

echo "<hr>";
echo "<h2>Test 2: Générer un nouveau hash pour 'secret'</h2>";
$new_hash = password_hash('secret', PASSWORD_DEFAULT);
echo "Nouveau hash généré: <code>$new_hash</code><br>";

if (password_verify('secret', $new_hash)) {
    echo "<p style='color: green;'>✅ Le nouveau hash fonctionne correctement avec 'secret'</p>";
}

echo "<hr>";
echo "<h2>Test 3: Vérifier les utilisateurs dans la base de données</h2>";

try {
    $db = getDB();
    $stmt = $db->query("SELECT username, email, password FROM user LIMIT 5");
    $users = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Username</th><th>Email</th><th>Hash actuel</th><th>Test 'secret'</th></tr>";
    
    foreach ($users as $user) {
        $test_result = password_verify('secret', $user['password']) ? 
            "<span style='color: green;'>✅ OK</span>" : 
            "<span style='color: red;'>❌ FAIL</span>";
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td><code>" . substr($user['password'], 0, 30) . "...</code></td>";
        echo "<td>$test_result</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Solution: Mettre à jour TOUS les mots de passe</h2>";
echo "<p>Si les tests ci-dessus montrent que 'secret' ne fonctionne pas, cliquez sur le bouton ci-dessous pour mettre à jour tous les mots de passe.</p>";

if (isset($_POST['update_passwords'])) {
    try {
        $db = getDB();
        $new_password_hash = password_hash('secret', PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("UPDATE user SET password = ?");
        $stmt->execute([$new_password_hash]);
        
        $affected = $stmt->rowCount();
        echo "<p style='color: green; font-weight: bold;'>✅ $affected utilisateurs mis à jour avec le mot de passe 'secret'</p>";
        echo "<p>Vous pouvez maintenant vous connecter avec:</p>";
        echo "<ul>";
        echo "<li>Email: admin@blogcms.com</li>";
        echo "<li>Mot de passe: <strong>secret</strong></li>";
        echo "</ul>";
        echo "<p><a href='login.php' style='padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>Aller à la page de connexion</a></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erreur lors de la mise à jour: " . $e->getMessage() . "</p>";
    }
}

if (!isset($_POST['update_passwords'])) {
    echo "<form method='POST'>";
    echo "<button type='submit' name='update_passwords' style='padding: 10px 20px; background: red; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>";
    echo "⚠️ METTRE À JOUR TOUS LES MOTS DE PASSE → 'secret'";
    echo "</button>";
    echo "</form>";
    echo "<p style='color: red;'><small>⚠️ Attention: Cette action modifiera le mot de passe de TOUS les utilisateurs!</small></p>";
}

echo "<hr>";
echo "<h2>Test 4: Tester la connexion manuellement</h2>";

if (isset($_POST['test_login'])) {
    $test_email = trim($_POST['test_email']);
    $test_password = $_POST['test_password'];
    
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT username, email, password, user_role FROM user WHERE email = ?");
        $stmt->execute([$test_email]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "<p>✅ Utilisateur trouvé: " . htmlspecialchars($user['username']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
            echo "<p>Rôle: " . htmlspecialchars($user['user_role']) . "</p>";
            
            if (password_verify($test_password, $user['password'])) {
                echo "<p style='color: green; font-weight: bold;'>✅ MOT DE PASSE CORRECT!</p>";
                echo "<p>Vous pouvez vous connecter avec ces identifiants.</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ MOT DE PASSE INCORRECT!</p>";
                echo "<p>Hash dans la DB: <code>" . $user['password'] . "</code></p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Aucun utilisateur trouvé avec cet email.</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
    }
}

echo "<form method='POST'>";
echo "<p><label>Email: <input type='email' name='test_email' value='admin@blogcms.com' style='padding: 5px; width: 300px;'></label></p>";
echo "<p><label>Mot de passe: <input type='text' name='test_password' value='secret' style='padding: 5px; width: 300px;'></label></p>";
echo "<button type='submit' name='test_login' style='padding: 10px 20px; background: green; color: white; border: none; border-radius: 5px; cursor: pointer;'>Tester la connexion</button>";
echo "</form>";
?>
