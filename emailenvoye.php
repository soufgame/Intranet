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

// Récupérer l'ID du fichier
if (!isset($_GET['file_id'])) {
    die("Aucun fichier spécifié.");
}
$file_id = intval($_GET['file_id']); // S'assurer que l'ID est un entier

// Préparer la requête SQL pour sélectionner le fichier par ID
$stmt = $conn->prepare("SELECT * FROM files WHERE id = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $file_id);

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $file = $result->fetch_assoc();
    echo "<h1>Détails du fichier</h1>";
    echo "<p>Titre: " . htmlspecialchars($file["file_name"]) . "</p>";
    echo "<p>Message: " . htmlspecialchars($file["message"]) . "</p>";
    echo "<p>Envoyé à: " . htmlspecialchars($file["username"]) . "</p>";
    echo "<p>Date: " . htmlspecialchars($file["date"]) . "</p>";
    echo "<p>Heure: " . htmlspecialchars($file["time"]) . "</p>";

    // Offrir des liens de téléchargement pour chaque fichier
    for ($i = 1; $i <= 3; $i++) {
        $file_data_field = "file_data" . ($i > 1 ? "_$i" : "");
        if (!empty($file[$file_data_field])) {
            $file_name = htmlspecialchars($file["file_name"]) . ($i > 1 ? "_$i" : "");
            echo "<a href='downloadenvoye.php?file_id=$file_id&file_num=$i'>Télécharger fichier $file_name</a><br>";
        }
    }
} else {
    echo "Aucun fichier trouvé avec l'identifiant spécifié.";
}

$stmt->close();
$conn->close();
?>
