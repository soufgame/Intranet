<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session si nécessaire
$username = $_SESSION['username']; // Exemple : récupérer le nom d'utilisateur
$id = $_SESSION['id']; // Exemple : récupérer le nom d'utilisateur

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
         <!-- Sidebar -->
         <div class="sidebar">
         <a href="dashboardemploye.php" class="active">
  <span class="material-symbols-sharp">grid_view</span>
  <h3>Dashboard</h3>
</a>

           <a href="BoiteDeReception.php">
  <span class="material-symbols-sharp">person_outline</span>
  <h3>Boite d réception</h3>
</a>
           <a href="#">
             <span class="material-symbols-sharp">insights</span>
             <h3>Nouveau Message</h3>
           </a>
           <a href="#">
             <span class="material-symbols-sharp">mail_outline</span>
             <h3>Message envoyee</h3>
           </a>
           <a href="#">
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
             <span class="material-symbols-sharp">add</span>
             <h3>Profil </h3>
           </a>
           <a href="login.php?logout=1" class="logout">
  <span class="material-symbols-sharp">logout</span>
  <h3>Logout</h3>
</a>

         </div>
      </aside>

      <!-- Main Section -->
      <main>
        <h1>Boite de reception</h1>
       
      </main>

      <!-- Right Section -->
      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>
          <div class="theme-toggler">
            <span class="material-symbols-sharp active">light_mode</span>
            <span class="material-symbols-sharp">dark_mode</span>
          </div>
          <div class="profile">
            <div class="info">
              <p><b><?php echo htmlspecialchars($username); ?></b></p>
              <p>Employé</p>
              <small class="text-muted"></small>
            </div>
            <div class="profile-photo">
              <img src="./images/profilu.jpg" alt="Photo de profil"/>
            </div>
          </div>
        </div>
      </div>
   </div>
   <script src="script.js"></script>
</body>
</html>

