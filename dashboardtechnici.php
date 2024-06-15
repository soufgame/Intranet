<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit();
}

// Récupérer le nom d'utilisateur depuis la session
$username = $_SESSION['username'];
$technicienId = $_SESSION['id']; 

// Exemple : supposons que vous stockez le nom et le prénom du technicien dans la session
if (isset($_SESSION['nom']) && isset($_SESSION['prenom'])) {
    $nom = htmlspecialchars($_SESSION['nom']);
    $prenom = htmlspecialchars($_SESSION['prenom']);
} else {
    // Si les informations ne sont pas disponibles dans la session
    $nom = "Nom non défini";
    $prenom = "Prénom non défini";
}


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

// Initialise la variable $nombreTicketsSansIntervention
$nombreTicketsSansIntervention = 0;

// Requête SQL pour compter les tickets sans intervention
$sql = "SELECT COUNT(*) AS total_tickets_sans_intervention 
        FROM tickets t 
        WHERE NOT EXISTS (
            SELECT 1 
            FROM intervention i 
            WHERE i.ticketID = t.Ticketid
        )";

try {
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Vérifie si la requête a retourné des résultats
    if ($row) {
        $nombreTicketsSansIntervention = $row['total_tickets_sans_intervention'];
    }
} catch(PDOException $e) {
    // Gérer l'erreur si la requête échoue
    $nombreTicketsSansIntervention = "Erreur lors du calcul";
}



// Initialise la variable $nombreTicketsenIntervention
$nombreTicketsenIntervention = 0;

// Requête SQL pour compter les tickets en intervention pour le technicien spécifique
$sql = "SELECT COUNT(*) AS total_intervention 
        FROM intervention 
        WHERE technicienID = :technicienId";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':technicienId', $technicienId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // Vérifie si la requête a retourné des résultats
    if ($row) {
        $nombreTicketsenIntervention = $row['total_intervention'];
    }
} catch(PDOException $e) {
    // Gérer l'erreur si la requête échoue
    $nombreTicketsenIntervention = "Erreur lors du calcul";
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
    <a href="intervention.php" id="rendez-vous">Intervention</a>
    <a href="ticket.php" id="patient">Ticket</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboardtechnici.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
</div>

<div class="ticket-container">
    <h2>Nombre de tickets ouvert : <?php echo $nombreTicketsSansIntervention; ?></h2>
</div>


<div class="intervention-container">
    <h2>Nombre de tickets en intervention : <?php echo $nombreTicketsenIntervention; ?></h2>
</div>

<div class="doctor-label">
<?php echo 'Technicien : ' . $nom . ' ' . $prenom; ?>
</div>
<style>
.intervention-container {
    text-align: center;
    max-width: 700px; /* Largeur maximum */
    width: 450px; /* Largeur fixée pour que le texte soit centré sur une ligne */
    height: 180px; /* Hauteur fixée */
    margin-top: -16.5%;
    margin-left: 60%;
    margin: 10% auto; /* Centrer verticalement et horizontalement */
    padding: 30px; /* Padding pour espace intérieur */
    border-radius: 10px; /* Coins arrondis */
    color: #FFFFFF; 
    font-size: 16px; 
    background-color: #6A6A6A;
    display: flex; /* Utiliser Flexbox */
    align-items: center; /* Centrer verticalement */
    justify-content: center; /* Centrer horizontalement */
    line-height: 3; /* Assurez-vous que le line-height ne perturbe pas l'alignement */
}

.intervention-container {
margin-top: -16.5%;
margin-left: 60%;

}
.intervention-container h1 {7
    color: #FFFFFF; 
    font-size: 24px; 
}


</style>

</body>
</html>
