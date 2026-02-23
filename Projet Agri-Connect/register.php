<?php
session_start();

// Si l'utilisateur est déjà connecté, le rediriger vers le tableau de bord
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];
    $address = trim($_POST['address']);
    $region = trim($_POST['region']);
    $cooperative = trim($_POST['cooperative']);
    
    // Validation
    $errors = [];
    
    if (empty($name)) $errors[] = "Le nom est obligatoire";
    if (empty($email)) $errors[] = "L'email est obligatoire";
    if (empty($phone)) $errors[] = "Le téléphone est obligatoire";
    if (empty($password)) $errors[] = "Le mot de passe est obligatoire";
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }
    
    // Simulation de vérification d'email/téléphone (sans base de données)
    $existing_emails = ['admin@agriconnect.tg', 'agriculteur@agriconnect.tg', 'acheteur@agriconnect.tg'];
    if (in_array($email, $existing_emails)) {
        $errors[] = "Cet email est déjà utilisé";
    }
    
    if (empty($errors)) {
        // Simulation d'inscription réussie (sans base de données)
        $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - AGRI-CONNECT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <style>
        .register-form {
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .user-type-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .user-type-option {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--border-gray);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type-option:hover {
            border-color: var(--primary-green);
        }
        
        .user-type-option.selected {
            border-color: var(--primary-green);
            background: rgba(27,94,32,0.1);
        }
        
        .user-type-option input[type="radio"] {
            display: none;
        }
        
        .user-type-option i {
            font-size: 2rem;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }
        
        .register-form::-webkit-scrollbar {
            width: 6px;
        }
        
        .register-form::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .register-form::-webkit-scrollbar-thumb {
            background: var(--primary-green);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Côté gauche - Formulaire d'inscription -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <!-- Logo -->
                <div class="logo-section">
                    <h1 class="brand-name">AGRI-CONNECT</h1>
                </div>
                
                <!-- Message d'erreur -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Formulaire -->
                <div class="form-section register-form">
                    <h2 class="form-title">Rejoignez AGRI-CONNECT</h2>
                    <p class="form-subtitle">Créez votre compte pour accéder au marché agricole.</p>
                    
                    <form method="POST" action="">
                        <!-- Type d'utilisateur -->
                        <div class="form-group">
                            <label class="form-label">Je suis un :</label>
                            <div class="user-type-selector">
                                <label class="user-type-option">
                                    <input type="radio" name="user_type" value="agriculteur" checked>
                                    <i class="fas fa-seedling"></i>
                                    <div>Agriculteur</div>
                                </label>
                                <label class="user-type-option">
                                    <input type="radio" name="user_type" value="acheteur">
                                    <i class="fas fa-shopping-cart"></i>
                                    <div>Acheteur</div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Nom et Email -->
                        <div class="form-row">
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           name="name" 
                                           placeholder="Nom complet" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" 
                                           class="form-control" 
                                           name="email" 
                                           placeholder="Email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                           required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Téléphone -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" 
                                       class="form-control" 
                                       name="phone" 
                                       placeholder="Téléphone" 
                                       value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="form-row">
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
                            
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" 
                                           class="form-control" 
                                           name="confirm_password" 
                                           id="confirmPassword" 
                                           placeholder="Confirmer le mot de passe" 
                                           required>
                                    <button type="button" class="toggle-password" onclick="toggleConfirmPassword()">
                                        <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adresse et Région -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" 
                                       class="form-control" 
                                       name="address" 
                                       placeholder="Adresse complète" 
                                       value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="fas fa-globe-africa input-icon"></i>
                                    <select class="form-control" name="region">
                                        <option value="">Sélectionner une région</option>
                                        <option value="Maritime" <?php echo isset($_POST['region']) && $_POST['region'] == 'Maritime' ? 'selected' : ''; ?>>Maritime</option>
                                        <option value="Plateaux" <?php echo isset($_POST['region']) && $_POST['region'] == 'Plateaux' ? 'selected' : ''; ?>>Plateaux</option>
                                        <option value="Centrale" <?php echo isset($_POST['region']) && $_POST['region'] == 'Centrale' ? 'selected' : ''; ?>>Centrale</option>
                                        <option value="Kara" <?php echo isset($_POST['region']) && $_POST['region'] == 'Kara' ? 'selected' : ''; ?>>Kara</option>
                                        <option value="Savanes" <?php echo isset($_POST['region']) && $_POST['region'] == 'Savanes' ? 'selected' : ''; ?>>Savanes</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="text" 
                                           class="form-control" 
                                           name="cooperative" 
                                           placeholder="Coopérative (optionnel)" 
                                           value="<?php echo isset($_POST['cooperative']) ? htmlspecialchars($_POST['cooperative']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            S'inscrire
                            <i class="fas fa-user-plus"></i>
                        </button>
                    </form>
                    
                    <div class="signup-link">
                        <span>Vous avez déjà un compte ?</span>
                        <a href="login.php">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Côté droit - Section promotionnelle -->
        <div class="promo-section">
            <div class="promo-content">
                <div class="promo-image">
                    <img src="assets/5168c6e914238ee90034d59cbf43ec5b.jpg" alt="Communauté agricole" style="width: 90%; max-width: 300px;">
                </div>
                
                <div class="promo-text">
                    <h3>Rejoignez notre communauté.</h3>
                    <p>Connectez-vous avec des milliers d'agriculteurs et d'acheteurs. Développez votre activité avec AGRI-CONNECT.</p>
                    
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
        
        function toggleConfirmPassword() {
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const toggleIcon = document.getElementById('toggleConfirmIcon');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Gestion du sélecteur de type d'utilisateur
        document.querySelectorAll('.user-type-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.user-type-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
        
        // Sélectionner par défaut le premier type
        document.querySelector('.user-type-option').classList.add('selected');
    </script>
</body>
</html>
