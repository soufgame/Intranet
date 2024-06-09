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
$stmt = $conn->prepare("
    SELECT files.id, file_name, message, file_data, file_data_2, file_data_3, date, time, users.username, is_read 
    FROM files 
    INNER JOIN users ON files.user_id = users.id 
    WHERE files.username = ? 
    ORDER BY date DESC, time DESC
");

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
            margin: 20px;
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
            color: #333;
            background-color: #FFFFFF;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #Dashboard:hover {
            background-color: #555;
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

<div class="container">
<?php
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Titre</th><th>Date</th><th>Time</th><th>Expéditeur</th><th>V</th></tr>";
    while ($row = $result->fetch_assoc()) {
        // Vérifiez si la clé is_read existe avant de l'utiliser
        $isRead = isset($row['is_read']) ? $row['is_read'] : false;
        $rowClass = $isRead ? '' : ' class="unread"';
        echo "<tr{$rowClass} data-id='" . htmlspecialchars($row["id"]) . "'>";
        echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "<td>" . ($isRead ? "<img src='vue.png' alt='Vue' width='20' height='20'>" : "<img src='non vue.png' alt='Non vue' width='20' height='20'>") . "</td>";


        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Aucun Imail recu.</p>";
}
?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('tr[data-id]');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            var fileId = row.getAttribute('data-id');
            
            // Envoyer une requête AJAX pour mettre à jour le statut de lecture
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_read_status.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('file_id=' + fileId);

            // Rediriger vers la page email.php
            window.location.href = 'email.php?file_id=' + fileId;
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

</div>

<div class="doctor-label"><?php echo 'Nom: ' . htmlspecialchars($prenom) . ' ' . htmlspecialchars($nom); ?></div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
