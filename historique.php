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
$userID = $_SESSION['id']; // Récupérer l'ID de l'utilisateur connecté

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

// Créer la connexion
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Afficher l'erreur de connexion
}

// Préparer la requête SQL pour sélectionner les fichiers de l'utilisateur connecté
$stmt = $conn->prepare("
      SELECT f.id, f.file_name, f.message, f.file_data, f.file_data_2, f.file_data_3,f.username, f.date, f.time
    FROM files f
    WHERE f.user_id = ?
    ORDER BY f.date DESC, f.time DESC
");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error); // Afficher l'erreur de préparation de la requête
}

$stmt->bind_param("i", $userID); // Utilisez "i" pour binder un entier

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error); // Afficher l'erreur d'exécution de la requête
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
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        th {
            background-color: #222;
            color: white;
            font-weight: bold;
        }
        header {
            background-color: #222;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: 0px;
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
            color: #333;
            background-color: #FFFFFF;
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
        #profil {
            position: absolute;
            top: 300px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #profil:hover {
            background-color: #555;
        }
        .sidebar a {
    text-decoration: none;
    display: block;
    color: #FFFFFF;
    padding: 10px;
    margin-bottom: 5px;
}


        
        #support {
            position: absolute;
            top: 250px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #support:hover {
            background-color: #555;
        }

        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 200px;
            height: 100%;
            background-color: #222;
            border-radius: 0 10px 10px 0;
        }
        tr.unread {
            font-weight: bold;
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


<
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Titre</th><th>Date</th><th>Time</th><th>Envoyé a</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row["id"]) . "'>";
            echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Aucun fichier trouvé pour cet utilisateur.</p>"; // Aucun fichier trouvé
    }
    ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('table tr[data-id]');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            var fileId = row.getAttribute('data-id');
            
            // Rediriger vers la page emailenvoye.php avec l'id du fichier
            window.location.href = 'emailenvoye.php?file_id=' + fileId;
        });
    });
});

</script>

<div class="sidebar">
    <a href="historique.php" id="rendez-vous">Historique</a>
    <a href="Nouveau.php" id="patient">Nouveau</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboard.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>


</div>

<div class="doctor-label"><?php echo 'Name: ' . htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
