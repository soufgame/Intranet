<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost"; // Remplacez par votre serveur
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = "Soufiane@2003"; // Remplacez par votre mot de passe
$dbname = "intranet"; // Remplacez par votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le fichier a été téléchargé sans erreur
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Récupérer les données du formulaire
    $file_name = $_POST['file_name'];
    $message = $_POST['message'];
    $user_id = $_SESSION['id']; // Utilisez $_SESSION['id'] au lieu de $_SESSION['user_id']
    $username = $_SESSION['username']; // Utilisez $_SESSION['username'] au lieu de $_POST['username']

    // Gérer le fichier uploadé
    $file = $_FILES['file'];
    $file_tmp_path = $file['tmp_name'];
    $file_data = file_get_contents($file_tmp_path);

    // Préparer et exécuter la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO files (file_name, file_data, message, user_id, username) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $file_name, $file_data, $message, $user_id, $username);

    if ($stmt->execute()) {
        echo "Fichier téléchargé avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erreur lors du téléchargement du fichier.";
    if (isset($_FILES['file']['error'])) {
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                echo "Le fichier est trop volumineux.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "Le fichier n'a été que partiellement téléchargé.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "Aucun fichier n'a été téléchargé.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Il manque un dossier temporaire.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Échec de l'écriture du fichier sur le disque.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "Une extension PHP a arrêté le téléchargement du fichier.";
                break;
            default:
                echo "Erreur inconnue.";
                break;
        }
    }
}

$conn->close();
?>
