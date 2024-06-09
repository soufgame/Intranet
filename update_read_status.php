<?php
session_start();

if (!isset($_SESSION['username'])) {
    die("Accès refusé.");
}

$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['file_id'])) {
    $fileId = intval($_POST['file_id']);

    $stmt = $conn->prepare("UPDATE files SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $fileId);

    if (!$stmt->execute()) {
        die("Mise à jour échouée: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
