<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit();
}

// Récupérer le nom et le prénom du technicien depuis la session
$nom = htmlspecialchars($_SESSION['nom']);
$prenom = htmlspecialchars($_SESSION['prenom']);

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    // Définir le mode d'erreur PDO à exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Initialise la variable $nombreTicketsOuverts
$nombreTicketsOuverts = 0;

// Requête SQL pour compter les tickets ouverts
$sql = "SELECT COUNT(*) AS total_tickets_ouverts FROM tickets";

try {
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Vérifie si la requête a retourné des résultats
    if ($row) {
        $nombreTicketsOuverts = $row['total_tickets_ouverts'];
    }
} catch(PDOException $e) {
    // Gérer l'erreur si la requête échoue
    $nombreTicketsOuverts = "Erreur lors du calcul";
}

// Fermer la connexion à la base de données
$conn = null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="style/dashboardtechnici.css">
    <style>
        .ticket-container {
            text-align: center;
            margin: 100px auto;
            max-width: 400px;
            background-color: rgba(0, 0, 0, 0.555);
            padding: 20px;
            border-radius: 10px;
            margin-left: 450px;
            margin-top: 100px;
            width: 800px; 
            height: 200px; 
            display: flex;                  
            flex-direction: column;        
            justify-content: center;       
            align-items: center;   
            color: #FFF;
            font-weight: bold;

}    
    </style>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>


<div class="sidebar">
    <a href="intervention.php" id="rendez-vous">intervention</a>
    <a href="ticket.php" id="patient">Ticket</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboardtechnici.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
</div>

<div class="ticket-container">
    <h2>Nombre de tickets ouverts : <?php echo $nombreTicketsOuverts; ?></h2>
</div>

<div class="doctor-label">
    <?php echo 'Technicien : ' . $nom . ' ' . $prenom; ?>
</div>
</body>
</html>
