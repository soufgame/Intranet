<?php
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

// Vérifier si l'identifiant de fichier et le type de fichier sont passés en paramètre
if (isset($_GET['file_id']) && isset($_GET['file_type'])) {
    $fileId = $_GET['file_id'];
    $fileType = $_GET['file_type'];

    // Préparer la requête SQL pour sélectionner les informations du fichier spécifié
    $stmt = $conn->prepare("SELECT $fileType FROM files WHERE id = ?");
    $stmt->bind_param("i", $fileId);

    if (!$stmt->execute()) {
        die("Query failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Récupérer le nom du fichier et le contenu du fichier
        $filename = basename($row[$fileType]);
        $filedata = $row[$fileType];

        // Définir les en-têtes pour forcer le téléchargement du fichier
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Sortir le contenu du fichier
        echo $filedata;
    } else {
        die("Aucun fichier trouvé avec l'identifiant spécifié.");
    }
} else {
    die("Paramètres manquants.");
}

$stmt->close();
$conn->close();
?>
