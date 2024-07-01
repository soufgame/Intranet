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

// Initialise les variables pour le nombre de tickets
$nombreTicketsSansIntervention = 0;
$nombreTicketsenIntervention = 0;

// Requête SQL pour compter les tickets sans intervention
$sqlSansIntervention = "SELECT COUNT(*) AS total_tickets_sans_intervention 
                        FROM tickets t 
                        WHERE NOT EXISTS (
                            SELECT 1 
                            FROM intervention i 
                            WHERE i.ticketID = t.Ticketid
                        )";

try {
    $stmtSansIntervention = $conn->query($sqlSansIntervention);
    $rowSansIntervention = $stmtSansIntervention->fetch(PDO::FETCH_ASSOC);
    // Vérifie si la requête a retourné des résultats
    if ($rowSansIntervention) {
        $nombreTicketsSansIntervention = $rowSansIntervention['total_tickets_sans_intervention'];
    }
} catch(PDOException $e) {
    // Gérer l'erreur si la requête échoue
    $nombreTicketsSansIntervention = "Erreur lors du calcul";
}

// Requête SQL pour compter les tickets en intervention pour le technicien spécifique
$sqlEnIntervention = "SELECT COUNT(*) AS total_intervention 
                      FROM intervention 
                      WHERE technicienID = :technicienId";

try {
    $stmtEnIntervention = $conn->prepare($sqlEnIntervention);
    $stmtEnIntervention->bindParam(':technicienId', $technicienId, PDO::PARAM_INT);
    $stmtEnIntervention->execute();
    $rowEnIntervention = $stmtEnIntervention->fetch(PDO::FETCH_ASSOC);
    // Vérifie si la requête a retourné des résultats
    if ($rowEnIntervention) {
        $nombreTicketsenIntervention = $rowEnIntervention['total_intervention'];
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
    <!-- Intégration du lien vers tech.css -->
    <link rel="stylesheet" href="tech.css">
    <!-- Intégration du lien vers le template CSS de navigation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
   
    <style>
        body {
            background-color: rgb(0, 0, 0);
            font-weight:600;
            text-align:center !important;
            color: white;
        }
 /* Styles pour le container des tickets ouverts */
.ticket-container {
    background-color: #636363;
    padding: 20px;
    margin: 20px;
    border-radius: 10px;
    text-align: center;
    font-size: 18px; /* Augmenter la taille du texte */
    font-weight: bold; /* Texte en gras */

}

.ticket-container h2 {
    font-size: 24px;
    color: #fff;
}

/* Styles pour le container des tickets en intervention */
.intervention-container {
    background-color: #636363;
    padding: 20px;
    margin: 20px;
    border-radius: 10px;
    text-align: center;
    font-size: 18px; /* Augmenter la taille du texte */
    font-weight: bold; /* Texte en gras */

}

.intervention-container h2 {
    font-size: 24px;
    color: #fff;
}


    </style>
</head>
<body>


<!-- Intégration du template de navigation -->
<div class="container-fluid">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a id="len1" class="hoverable" href="dashboardtechnici.php">Dashboard</a></li>
                <li><a id="len2" class="hoverable" href="ticket.php">Ticket</a></li>
                <li><a id="len3" class="hoverable" href="intervention.php">Intervention</a></li>
                <li><a id="len4" class="hoverable" href="historiquetechnicien.php">Historique</a></li>
                <li><a id="len5" class="hoverable" href="profiltech.php">Profils</a></li>
            </ul>
        </div>
    </nav>

</div>

<!-- Container pour le nombre de tickets ouverts -->
<div class="ticket-container">
    <h2>Nombre de tickets ouverts : <?php echo $nombreTicketsSansIntervention; ?></h2>
</div>

<!-- Container pour le nombre de tickets en intervention -->
<div class="intervention-container">
    <h2>Nombre de tickets en intervention : <?php echo $nombreTicketsenIntervention; ?></h2>
</div>

<!-- Intégration du lien vers jQuery (assurez-vous d'avoir jQuery disponible) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Intégration du JavaScript pour les animations -->
<script>
    $(function(){
        var str = '#len'; //increment by 1 up to 1-nelemnts
        $(document).ready(function(){
            var i, stop;
            i = 1;
            stop = 4; //num elements
            setInterval(function(){
                if (i > stop){
                    return;
                }
                $('#len'+(i++)).toggleClass('bounce');
            }, 500)
        });
    });
</script>

</body>
</html>
