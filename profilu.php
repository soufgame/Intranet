<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : 'Nom non défini';
$prenom = isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : 'Prénom non défini';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="style/profilu.css">
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>



<div class="sidebar">
    <a href="historique.php" id="rendez-vous">Mail envoye</a>
    <a href="Nouveau.php" id="patient">Nouveau mail</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboard.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
</div>

<div class="user-info">
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
    $stmt = $conn->prepare("SELECT username, nom, prenom, department, division, service, cin, telephone FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier s'il y a des résultats
    if ($result->num_rows > 0) {
        // Afficher les informations de l'utilisateur
        while ($row = $result->fetch_assoc()) {
            echo "<label>Username: " . $row["username"] . "</label><br>";
            echo "<label>Nom: " . $row["nom"] . "</label><br>";
            echo "<label>Prénom: " . $row["prenom"] . "</label><br>";
            echo "<label>Département: " . $row["department"] . "</label><br>";
            echo "<label>Division: " . $row["division"] . "</label><br>";
            echo "<label>Service: " . $row["service"] . "</label><br>";
            echo "<label>CIN: " . $row["cin"] . "</label><br>";
            echo "<label>Téléphone: " . $row["telephone"] . "</label><br>";
        }
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermer la connexion et la requête
    $stmt->close();
    $conn->close();
    ?>
</div>

<div class="doctor-label"><?php echo 'Name: ' . $prenom . ' ' . $nom; ?></div>

</body>
</html>
