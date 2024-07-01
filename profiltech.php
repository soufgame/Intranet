<?php
session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$user_id = $_SESSION['id']; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="tech.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            font-weight: 600;
            text-align: center !important;
            color: white;
        }
        .container {
            margin-top: 20px;
        }
        .user-info {
            margin: 40px auto;
            max-width: 800px;
            padding: 30px;
            background-color: #2B2B2B;
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
</head>

<body>

<div class="container-fluid">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="dashboardtechnici.php">Dashboard</a></li>
                <li><a href="ticket.php">Ticket</a></li>
                <li><a href="intervention.php">Intervention</a></li>
                <li><a href="historiquetechnicien.php">Historique</a></li>
                <li><a href="profiltech.php">Profils</a></li>
            </ul>
        </div>
    </nav>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // JavaScript functions can be added here if needed
</script>

</body>
</html>
