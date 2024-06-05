<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Vérifier les paramètres de la requête
if (!isset($_GET['file_id']) || !isset($_GET['file_type'])) {
    die("Invalid request");
}

$file_id = intval($_GET['file_id']);
$file_type = $_GET['file_type'];

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

// Préparer la requête SQL pour sélectionner le fichier
$stmt = $conn->prepare("SELECT $file_type FROM files WHERE id = ? AND username = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$username = $_SESSION['username'];
$stmt->bind_param("is", $file_id, $username);

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$stmt->bind_result($file_data);
$stmt->fetch();

if (empty($file_data)) {
    die("File not found or access denied");
}

$stmt->close();
$conn->close();

// Définir les en-têtes pour le téléchargement du fichier
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$file_type\".txt");
header("Content-Length: " . strlen($file_data));

// Envoyer le fichier au client
echo $file_data;
?>
