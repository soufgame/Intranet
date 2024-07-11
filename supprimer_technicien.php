<?php
// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Vérifier si l'ID du technicien à supprimer est défini dans l'URL
if (isset($_GET['id'])) {
    // Échapper les entrées pour éviter les injections SQL
    $id = $conn->real_escape_string($_GET['id']);

    // Requête SQL pour supprimer le technicien
    $sql = "DELETE FROM technicien WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirection vers la page admin_technicien.php après suppression
        header("Location: admin_technicien.php");
    } else {
        echo "Erreur lors de la suppression du technicien : " . $conn->error;
    }
} else {
    echo "ID du technicien non spécifié";
}

// Fermer la connexion à la base de données
$conn->close();
?>
