<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session
$username = $_SESSION['username'];

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .suggestion-item {
        display: inline-block;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        margin: 5px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .suggestion-item:hover {
        max-width: none;
        overflow: visible;
        white-space: normal;
    }

    .remove-icon {
        width: 15px;
        height: auto;
        margin-right: 5px;
    }
    /* Styles spécifiques pour les zones de texte du formulaire */
    #emailForm {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

#emailForm label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

#emailForm input[type="text"],
#emailForm input[type="file"],
#emailForm textarea {
    background-color: #000000;
    border-radius: 12px;
    border: 0;
    box-sizing: border-box;
    color: #eee;
    font-size: 18px;
    outline: 0;
    padding: 10px 20px;
    width: 500px;
    height: 40px; /* Adjust height as needed */

}

#emailForm input[type="text"]:focus,
#emailForm input[type="file"]:focus,
#emailForm textarea:focus {
    outline: none;
    border-bottom: 2px solid #dc2f55; /* Consistent with the animation color */
}

#emailForm textarea {
    height: 200px; /* Make textarea longer */
    resize: vertical;
}

#emailForm .file-input-container {
    position: relative;
    margin-bottom: 15px;
}

#emailForm .file-input-container input[type="file"] {
    display: block;
    padding: 5px;
}

#emailForm .clear-file {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    background-color: transparent;
    border: none;
    color: #f00;
    font-size: 20px;
    cursor: pointer;
    display: none;
}

#emailForm button[type="submit"] {
    background-color: #08d;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 18px;
    text-align: center;
    width: 100%;
    margin-top: 20px;
}

#emailForm button[type="submit"]:hover {
    background-color: #06b;
}
  </style>
</head>
<body>
   <div class="container">
      <aside>
         <div class="top">
           <div class="logo">
             <h2><span class="danger">Intranet</span></h2>
           </div>
           <div class="close" id="close_btn">
             <span class="material-symbols-sharp">close</span>
           </div>
         </div>
         <div class="sidebar">
           <a href="dashboardemploye.php" class="active">
             <span class="material-symbols-sharp">grid_view</span>
             <h3>Dashboard</h3>
           </a>
           <a href="BoiteDeReception.php">
             <span class="material-symbols-sharp">person_outline</span>
             <h3>Boite de réception</h3>
           </a>
           <a href="nouveauMessage.php">
             <span class="material-symbols-sharp">mail_outline</span>
             <h3>Nouveau Message</h3>
           </a>
           <a href="Messageenvoye.php">
             <span class="material-symbols-sharp"> Mail</span>
             <h3>Message envoyee</h3>
           </a>
           <a href="createticket.php">
             <span class="material-symbols-sharp">receipt_long</span>
             <h3>Support</h3>
           </a>
           <a href="#">
             <span class="material-symbols-sharp">report_gmailerrorred</span>
             <h3>Suivie de Ticket</h3>
           </a>
           <a href="#">
             <span class="material-symbols-sharp">settings</span>
             <h3>Historique de Ticket</h3>
           </a>
           <a href="#">
             <span class="material-symbols-sharp">person_outline</span>
             <h3>Profil </h3>
           </a>
           <a href="login.php?logout=1" class="logout">
             <span class="material-symbols-sharp">logout</span>
             <h3>Logout</h3>
           </a>
         </div>
      </aside>
      <main>
        <h1>Support  </h1>
        <!-- Formulaire de la première page supprimé -->
        <div class="container">
            <!-- Formulaire retiré -->
        </div>
      </main>
      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>
          <div class="profile">
            <div class="info">
              <p><b><?php echo htmlspecialchars($username); ?></b></p>
              <p>Employé</p>
            </div>
            <div class="profile-photo">
              <img src="./images/profilu.jpg" alt="Photo de profil"/>
            </div>
          </div>
        </div>
      </div>
   </div>
   <script>
    // JavaScript pour la gestion des destinataires et des fichiers retiré
  </script>
</body>
</html>

<?php
$conn->close();
?>
