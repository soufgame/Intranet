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

// Récupérer les paramètres
if (!isset($_GET['file_id']) || !isset($_GET['file_num'])) {
    die("Paramètres manquants.");
}
$file_id = intval($_GET['file_id']);
$file_num = intval($_GET['file_num']);
if ($file_num < 1 || $file_num > 3) {
    die("Numéro de fichier invalide.");
}

// Préparer la requête SQL pour sélectionner le fichier par ID et numéro de fichier
$file_data_field = "file_data" . ($file_num > 1 ? "_$file_num" : "");
$stmt = $conn->prepare("SELECT $file_data_field, file_name FROM files WHERE id = ?");
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
    $file_data = $file[$file_data_field];
    $file_name = $file["file_name"] . ($file_num > 1 ? "_$file_num" : "");

    // Envoyer les en-têtes pour le téléchargement
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Content-Length: ' . strlen($file_data));

    echo $file_data;
} else {
    echo "Aucun fichier trouvé.";
}

$stmt->close();
$conn->close();
?>
