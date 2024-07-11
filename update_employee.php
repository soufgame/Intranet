<?php
include 'connexiondb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $division = $_POST['division'];
    $service = $_POST['service'];
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];

    $sql = "UPDATE users SET username='$username', password='$password', department='$department', division='$division', service='$service', cin='$cin', nom='$nom', prenom='$prenom', telephone='$telephone' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_employe.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
