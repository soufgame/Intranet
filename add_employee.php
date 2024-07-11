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
    <style>
        /* Inline CSS for simplicity; normally, use an external stylesheet */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            padding: 20px;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
        }

        .back {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            margin-left: 20px; /* Ajustement pour espacement à gauche */
            padding: 8px 16px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .back:hover {
            background-color: #e0e0e0;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header class="top-header">
        <div class="header-content">
            <a href="admin_employe.php" class="back"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </header>
    <div class="container">
        <main class="main-content">
            <header>
                <h1>Add Employee</h1>
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
