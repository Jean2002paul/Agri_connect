<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Traitement de la déconnexion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    // Détruire toutes les variables de session
    $_SESSION = array();
    
    // Détruire le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Détruire la session
    session_destroy();
    
    // Rediriger vers la page de connexion
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - AGRI-CONNECT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .dashboard-container {
            padding: 2rem;
        }
        
        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1B5E20, #4CAF50);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            color: #1B5E20;
            margin-bottom: 1rem;
        }
        
        .logout-btn {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: #f44336;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <button class="logout-btn" onclick="logout()">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </button>
    
    <div class="dashboard-container">
        <div class="welcome-card">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
                <div>
                    <h1>Bienvenue, <?php echo $_SESSION['user_name']; ?> !</h1>
                    <p class="text-muted">Type: <?php echo ucfirst($_SESSION['user_type']); ?> | Email: <?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <h3>Produits</h3>
                <p class="text-muted">Gérez vos produits agricoles</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Marché</h3>
                <p class="text-muted">Accédez au marché</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Coopératives</h3>
                <p class="text-muted">Réseaux et partenariats</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Statistiques</h3>
                <p class="text-muted">Vos performances</p>
            </div>
        </div>
    </div>
    
    <script>
        function logout() {
            if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                // Détruire la session
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'logout=true'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            }
        }
    </script>
</body>
</html>
