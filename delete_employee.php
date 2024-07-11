<?php
include 'connexiondb.php';

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: admin_employe.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
