<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'connexiondb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $division = $_POST['division'];
    $service = $_POST['service'];
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];

    $sql = "INSERT INTO users (username, password, department, division, service, cin, nom, prenom, telephone) VALUES ('$username', '$password', '$department', '$division', '$service', '$cin', '$nom', '$prenom', '$telephone')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_employe.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <header class="top-header">
                <div class="header-content">
                    <h1>Add Employee</h1>
                    <a href="admin_employe.php" class="back"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </header>
            <section class="content">
                <form action="add_employee.php" method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required>
                    <label for="division">Division:</label>
                    <input type="text" id="division" name="division" required>
                    <label for="service">Service:</label>
                    <input type="text" id="service" name="service" required>
                    <label for="cin">CIN:</label>
                    <input type="text" id="cin" name="cin" required>
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                    <label for="prenom">Prenom:</label>
                    <input type="text" id="prenom" name="prenom" required>
                    <label for="telephone">Telephone:</label>
                    <input type="text" id="telephone" name="telephone" required>
                    <button type="submit">Add Employee</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
