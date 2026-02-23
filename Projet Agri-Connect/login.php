<?php
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers le tableau de bord
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Utilisateurs temporaires (sans base de données)
$temp_users = [
    'admin@agriconnect.tg' => [
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'name' => 'Administrateur',
        'user_type' => 'admin'
    ],
    'agriculteur@agriconnect.tg' => [
        'password' => password_hash('agri123', PASSWORD_DEFAULT),
        'name' => 'Jean Agriculteur',
        'user_type' => 'agriculteur'
    ],
    'acheteur@agriconnect.tg' => [
        'password' => password_hash('acheteur123', PASSWORD_DEFAULT),
        'name' => 'Marie Acheteuse',
        'user_type' => 'acheteur'
    ]
];

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];
    
    // Vérification dans les utilisateurs temporaires
    if (isset($temp_users[$email_or_phone]) && password_verify($password, $temp_users[$email_or_phone]['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = 1;
        $_SESSION['user_name'] = $temp_users[$email_or_phone]['name'];
        $_SESSION['user_email'] = $email_or_phone;
        $_SESSION['user_type'] = $temp_users[$email_or_phone]['user_type'];
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AGRI-CONNECT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <!-- Côté gauche - Formulaire de connexion -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <!-- Logo -->
                <div class="logo-section">
                    <h1 class="brand-name">AGRI-CONNECT</h1>
                </div>
                
                <!-- Message de succès -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Message d'erreur -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Formulaire -->
                <div class="form-section">
                    <h2 class="form-title">Bon retour parmi nous !</h2>
                    <p class="form-subtitle">Connectez-vous pour accéder à votre espace.</p>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="text" 
                                       class="form-control" 
                                       name="email_or_phone" 
                                       placeholder="Email ou Téléphone" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" 
                                       class="form-control" 
                                       name="password" 
                                       id="password" 
                                       placeholder="Mot de passe" 
                                       required>
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            Se Connecter
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    
                    <div class="signup-link">
                        <span>Vous n'avez pas de compte ?</span>
                        <a href="register.php">S'inscrire</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Côté droit - Section promotionnelle -->
        <div class="promo-section">
            <div class="promo-content">
                <div class="promo-image">
                    <img src="assets/ebff1412b0338fbdea06ceab65fc648b.jpg" alt="Culture agricole">
                </div>
                
                <div class="promo-text">
                    <h3>Ensemble pour une agriculture forte.</h3>
                    <p>Accédez au marché national et international sans intermédiaire. Sécurisez vos transactions avec AGRI-CONNECT.</p>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <h4>2k+</h4>
                            <p>Coopératives</p>
                        </div>
                        <div class="stat-item">
                            <h4>50+</h4>
                            <p>Acheteurs Gros</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bouton de chat flottant -->
            <div class="chat-button">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
