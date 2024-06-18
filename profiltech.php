<?php
session_start();
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$user_id = $_SESSION['id'] 


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="style/profiltech.css">
</head>
   
<header>
    <h1>Intranet</h1>
</header>

<div class="sidebar">
<a href="intervention.php" id="rendez-vous">intervention</a>
<a href="ticket.php" id="patient">Ticket</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboardtechnici.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profiltech.php" id="profil">Profil</a>

</div>

<div class="user-info">
<link rel="stylesheet" href="style/profiltech.css">

    <?php
    // Récupérer l'ID de l'utilisateur
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

    // Connexion à la base de données
    $servername = "localhost";
    $dbname = "intranet";
    $dbusername = "root";
    $dbpassword = "Soufiane@2003";

    // Créer une connexion
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
    $stmt = $conn->prepare("
SELECT 
    Nom,
    Prenom,
    UserName,
    cin,
    NumTel,
    service
FROM 
    technicien
WHERE id = ?
");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Afficher les informations de l'utilisateur
        while ($row = $result->fetch_assoc()) {
            echo "<label>Username: " . htmlspecialchars($row["UserName"]) . "</label><br>";
            echo "<label>Nom: " . htmlspecialchars($row["Nom"]) . "</label><br>";
            echo "<label>Prénom: " . htmlspecialchars($row["Prenom"]) . "</label><br>";
            echo "<label>Service: " . htmlspecialchars($row["service"]) . "</label><br>";
            echo "<label>CIN: " . htmlspecialchars($row["cin"]) . "</label><br>";
            echo "<label>Téléphone: " . htmlspecialchars($row["NumTel"]) . "</label><br>";
        }
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermer la connexion et la requête
    $stmt->close();
    $conn->close();
    ?>
</div>




<div class="doctor-label">
    <?php echo 'Technicien : ' . $nom . ' ' . $prenom; ?>
</div>
</body>
<style>
    
    .user-info {
    margin: 40px auto;
    max-width: 800px;
    padding: 30px;
    background-color: #555;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: left;
    color: #fff;
}

.user-info label {
    display: block;
    margin-bottom: 15px;
    font-size: 22px;
    font-weight: bold;
}

.user-info p {
    margin-bottom: 20px;
    font-size: 20px;
}

.user-info a {
    color: #007bff;
    text-decoration: none;
}

.user-info a:hover {
    text-decoration: underline;
}


</style>
</html>
