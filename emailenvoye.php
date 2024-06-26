<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

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

// Vérifier si l'identifiant de fichier est passé en paramètre
if (isset($_GET['file_id'])) {
    $fileId = intval($_GET['file_id']);

    // Préparer la requête SQL pour sélectionner les informations du fichier spécifié
    $stmt = $conn->prepare("SELECT file_name, message, file_data, file_data_2, file_data_3, date, time, users.username FROM files INNER JOIN users ON files.user_id = users.id WHERE files.id = ?");
    $stmt->bind_param("i", $fileId);

    if (!$stmt->execute()) {
        die("Query failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Aucun fichier trouvé avec l'identifiant spécifié.");
    }
} else {
    die("Identifiant de fichier non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - Mail envoyé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #222;
            color: #fff;
        }
        .button-back {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .button-back:hover {
            background-color: #555;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding-top: 60px; /* Add padding to separate content from the button */
        }
        .email {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #333;
        }
        .email h2 {
            margin-top: 0;
        }
        .email p {
            margin-bottom: 10px;
        }
        .email a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .email a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<button class="button-back" onclick="window.history.back()">Retour</button>

<div class="container">
    <h1>Mail envoyé</h1>
    <div class="email">
        <h2><?php echo htmlspecialchars($row["file_name"]); ?></h2>
        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($row["message"])); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($row["date"]); ?></p>
        <p><strong>Heure:</strong> <?php echo htmlspecialchars($row["time"]); ?></p>
        <p><strong>Expéditeur:</strong> <?php echo htmlspecialchars($row["username"]); ?></p>
        <?php if (!empty($row["file_data"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data">Télécharger File Data</a>
        <?php endif; ?>
        <?php if (!empty($row["file_data_2"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data_2">Télécharger File Data 2</a>
        <?php endif; ?>
        <?php if (!empty($row["file_data_3"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data_3">Télécharger File Data 3</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
