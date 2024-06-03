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
      margin-left: 220px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    header {
      background-color: #275805;
      color: #fff;
      padding: 20px;
      text-align: center;
      border-radius: 10px 10px 0 0;
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

    .sidebar {
      position: absolute;
      top: 0;
      left: 0;
      width: 200px;
      height: 100%; /* pour occuper toute la hauteur */
      background-color: #275805;
      border-radius: 0 10px 10px 0; /* pour correspondre au coin arrondi du header */
    }
  </style>
</head>
<body>
<header>
  <h1>Intranet</h1>
</header>

<div class="container">
  <!-- Contenu principal sans la table -->
</div>

<div class="sidebar">
  <button id="support">Support</button>
  <button id="rendez-vous">Support</button>

  <button id="patient">Nouveau </button>
  <a id="logoutButton" href="login.php">LOG OUT</a> <!-- Redirection vers login.php -->
  <button id="Dashboard">Dashboard</button>
</div>

<!-- Afficher le nom de l'utilisateur si disponible -->
<?php if(isset($_GET['username'])): ?>
<div class="doctor-label"><?php echo htmlspecialchars($_GET['username']); ?></div>
<?php endif; ?>

</body>
</html>
