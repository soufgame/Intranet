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
      height: 100%;
      background-color: #275805;
      border-radius: 0 10px 10px 0;
    }
    form {
      margin-top: 20px;
    }
    label {
      display: block;
      margin: 10px 0 5px;
    }
    input[type="text"], input[type="file"], select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="text"].small {
      width: 50%;
    }
    textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
      border-radius: 5px;
      border: 1px solid #ccc;
      height: 100px; /* Set height as needed */
    }
    input[type="submit"] {
      background-color: #275805;
      color: #fff;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #3a7007;
    }
    .file-input-container {
      position: relative;
    }
    .clear-file {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      background-color: red;
      color: white;
      border: none;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      text-align: center;
      cursor: pointer;
      display: none;
    }
  </style>
</head>
<body>
<?php
  session_start();

  // Vérifier si l'utilisateur est connecté
  if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
  }

  // Récupérer le nom et le prénom de l'utilisateur
  $nom = htmlspecialchars($_SESSION['nom']);
  $prenom = htmlspecialchars($_SESSION['prenom']);
?>
<header>
  <h1>Intranet</h1>
</header>

<div class="container">
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="file_name">Nom du fichier:</label>
    <input type="text" id="file_name" name="file_name" class="small" required>

    <div class="file-input-container">
      <label for="file_1">Sélectionner le premier fichier:</label>
      <input type="file" id="file_1" name="file[]" required>
      <button type="button" class="clear-file" id="clearFile1">&times;</button>
    </div>

    <div class="file-input-container">
      <label for="file_2">Sélectionner le deuxième fichier:</label>
      <input type="file" id="file_2" name="file[]">
      <button type="button" class="clear-file" id="clearFile2">&times;</button>
    </div>

    <div class="file-input-container">
      <label for="file_3">Sélectionner le troisième fichier:</label>
      <input type="file" id="file_3" name="file[]">
      <button type="button" class="clear-file" id="clearFile3">&times;</button>
    </div>

    <label for="message">Message {max 1000}:</label>
    <textarea id="message" name="message" required></textarea>

    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>

    <input type="submit" value="Envoyer">
  </form>
</div>

<div class="sidebar">
  <button id="rendez-vous">Support</button> <a href="support.php" id="rendez-vous">Support</a>
  <a href="Nouveau.php" id="patient">Nouveau</a>
  <button id="patient">Nouveau </button> <a href="Nouveau.php" id="patient">Nouveau</a>
  <a id="logoutButton" href="logout.php">LOG OUT</a>
  <button id="Dashboard">Dashboard</button> <a href="dashboard.php" id="Dashboard">Dashboard</a>
</div>

<div class="doctor-label"><?php echo 'name: '.$prenom . ' ' .$nom; ?></div>

<script>
  function handleFileInput(fileInputId, clearButtonId) {
    const fileInput = document.getElementById(fileInputId);
    const clearButton = document.getElementById(clearButtonId);

    fileInput.addEventListener('change', function() {
      if (this.files.length > 0) {
        clearButton.style.display = 'block';
      } else {
        clearButton.style.display = 'none';
      }
    });

    clearButton.addEventListener('click', function() {
      fileInput.value = '';
      this.style.display = 'none';
    });
  }

  handleFileInput('file_1', 'clearFile1');
  handleFileInput('file_2', 'clearFile2');
  handleFileInput('file_3', 'clearFile3');
</script>


</body>
</html> 