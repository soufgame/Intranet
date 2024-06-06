<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    echo "Utilisateur connecté : " . htmlspecialchars($_SESSION['username']) . "<br>";
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = htmlspecialchars($_SESSION['nom']);
$prenom = htmlspecialchars($_SESSION['prenom']);
$username = htmlspecialchars($_SESSION['username']);

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

// Créer la connexion
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Préparer la requête SQL pour sélectionner les fichiers de l'utilisateur connecté
$stmt = $conn->prepare("SELECT files.id, file_name, message, file_data, file_data_2, file_data_3, users.username FROM files INNER JOIN users ON files.user_id = users.id WHERE files.username = ?; ");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #DCDDDF;
        }
        .container {
            max-width: 1250px;
            margin: 20px auto; /* Ajustement de la marge pour centrer le conteneur */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Ajout de position relative pour positionner les éléments enfants absolus */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Ajustement de la marge supérieure pour déplacer la table vers le bas */
        }

        th, td {
            padding: 12px; /* Ajustement de la hauteur des lignes */
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px; /* Ajustement de la taille du texte */
        }

        th {
            background-color: #222; /* Dark green */
            color: white;
            font-weight: bold; /* Ajustement de la police en gras */
        }

        header {
            background-color: #222;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: 20px; /* Ajouté pour supprimer la marge par défaut */
            margin-top: -20px;
        }

        h1 {
            margin: 0;
        }
        .doctor-label {
            position: absolute;
            top: 20px;
            left: 30px;
            font-size: 35px;
            color: #FFFFFF;
        }
        #logoutButton {
            position: absolute;
            top: 800px;
            left: 40px;
            font-size: 20px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #logoutButton:hover {
            background-color: #555;
        }
        #rendez-vous {
            position: absolute;
            top: 200px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #rendez-vous:hover {
            background-color: #555;
        }
        #patient {
            position: absolute;
            top: 150px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #patient:hover {
            background-color: #555;
        }
        #Dashboard {
            position: absolute;
            top: 100px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #Dashboard:hover {
            background-color: #555;
        }
        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 200px;
            height: 100%;
            background-color: #222; /* Dark green */
            border-radius: 0 10px 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #222; /* Dark green */
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>

<div class="container">
    <?php
    // Afficher les fichiers de l'utilisateur
    echo "<h2>Fichiers de $prenom $nom</h2>";
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>File Name</th><th>Message</th><th>File Data</th><th>File Data 2</th><th>File Data 3</th><th>Username</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["message"]) . "</td>";
            echo "<td>";
            if (!empty($row["file_data"])) {
                echo "<a href='download.php?file_id=" . htmlspecialchars($row["id"]) . "&file_type=file_data'>Download File Data</a>";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($row["file_data_2"])) {
                echo "<a href='download.php?file_id=" . htmlspecialchars($row["id"]) . "&file_type=file_data_2'>Download File Data 2</a>";
            }
            echo "</td>";
            echo "<td>";
            if (!empty($row["file_data_3"])) {
                echo "<a href='download.php?file_id=" . htmlspecialchars($row["id"]) . "&file_type=file_data_3'>Download File Data 3</a>";
            }
            echo "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Aucun fichier trouvé pour cet utilisateur.</p>";
    }
    ?>
</div>

<div class="sidebar">
    <a href="support.php" id="rendez-vous">Support</a>
    <a href="Nouveau.php" id="patient">Nouveau</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboard.php" id="Dashboard">Dashboard</a>
</div>

<div class="doctor-label"><?php echo 'name: ' . htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
