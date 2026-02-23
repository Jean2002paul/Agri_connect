<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'agriconnect_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Configuration du site
define('SITE_NAME', 'AGRI-CONNECT');
define('SITE_URL', 'http://localhost/agri-connect/');
define('ADMIN_EMAIL', 'info@agriconnect.tg');

// Démarrage de la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonctions utilitaires
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function get_user_data($user_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        return false;
    }
}
?>
