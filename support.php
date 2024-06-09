<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #DCDDDF;
        }
        .container {
            max-width: 1250px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        header {
            background-color: #222;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: 20px;
            margin-top: -20px;
        }
        h1 {
            margin: 0;
        }
        .doctor-label {
            position: absolute;
            top: 20px;
            left: 30px;
            font-size: 35px;
            color: #FFFFFF;
        }
        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            width: 200px;
            height: 100%;
            background-color: #222;
            border-radius: 0 10px 10px 0;
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            color: #FFFFFF;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #555;
        }
        #logoutButton {
            position: absolute;
            top: 800px;
            left: 40px;
            font-size: 20px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #logoutButton:hover {
            background-color: #555;
        }
        #rendez-vous {
            position: absolute;
            top: 200px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #rendez-vous:hover {
            background-color: #555;
        }
        #patient {
            position: absolute;
            top: 150px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #patient:hover {
            background-color: #555;
        }
        #Dashboard {
            position: absolute;
            top: 100px;
            left: 15px;
            font-size: 25px;
            color: #FFFFFF;
            background-color: #333;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #Dashboard:hover {
            background-color: #555;
        }

        #support {
            position: absolute;
            top: 250px;
            left: 15px;
            font-size: 25px;
            color: #333;
            background-color: #FFFFFF;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #support:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>



<div class="sidebar">
    <a href="historique.php" id="rendez-vous">Historique</a>
    <a href="Nouveau.php" id="patient">Nouveau</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboard.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
</div>
<div class="doctor-label">Name: <?php echo $_SESSION['prenom']; ?> <?php echo $_SESSION['nom']; ?></div>


</body>
</html>
