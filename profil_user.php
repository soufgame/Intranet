<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session si nécessaire
$username = $_SESSION['username']; // Récupérer le nom d'utilisateur depuis la session

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['id'];

// Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT username, nom, prenom, department, division, service, cin, telephone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    // Récupérer les données de l'utilisateur
    $row = $result->fetch_assoc();
    $username = htmlspecialchars($row["username"]);
    $nom = htmlspecialchars($row["nom"]);
    $prenom = htmlspecialchars($row["prenom"]);
    $department = htmlspecialchars($row["department"]);
    $division = htmlspecialchars($row["division"]);
    $service = htmlspecialchars($row["service"]);
    $cin = htmlspecialchars($row["cin"]);
    $telephone = htmlspecialchars($row["telephone"]);
} else {
    echo "Aucun résultat trouvé pour cet utilisateur.";
}

// Fermer la requête et la connexion à la base de données
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateur</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="style.css">

    <style>
       
       .user-info {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-info label {
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

.user-info label::after {
    content: ":";
}

.user-info label:first-child::after {
    content: "";
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
             <!-- Sidebar -->
<div class="sidebar">
  <a href="dashboardemploye.php" class="active">
    <span class="material-symbols-sharp">dashboard</span>
    <h3>Dashboard</h3>
  </a>

  <a href="BoiteDeReception.php">
    <span class="material-symbols-sharp">inbox</span>
    <h3>Boite de réception</h3>
  </a>

  <a href="nouveauMessage.php">
    <span class="material-symbols-sharp">edit</span>
    <h3>Nouveau Message</h3>
  </a>

  <a href="Messageenvoye.php">
    <span class="material-symbols-sharp">send</span>
    <h3>Message envoyée</h3>
  </a>

   <a href="createticket.php">
    <span class="material-symbols-sharp">support_agent</span>
    <h3>Support </h3>
  </a>

  <a href="suivie_ticket.php">
    <span class="material-symbols-sharp">assignment</span>
    <h3>Suivi de Ticket</h3>
  </a>

  <a href="Historique_ticket.php">
    <span class="material-symbols-sharp">history</span>
    <h3>Historique de Ticket</h3>
  </a>

  <a href="profil_user.php">
    <span class="material-symbols-sharp">person</span>
    <h3>Profil</h3>
  </a>

  <a href="login.php?logout=1" class="logout">
    <span class="material-symbols-sharp">logout</span>
    <h3>Logout</h3>
  </a>
</div>
      </aside>

      <!-- Main Section -->
      <main>
        <h1>Profil Utilisateur</h1>
        <div class="user-info">
            <label>Username: <?php echo $username; ?></label><br>
            <label>Nom: <?php echo $nom; ?></label><br>
            <label>Prénom: <?php echo $prenom; ?></label><br>
            <label>Département: <?php echo $department; ?></label><br>
            <label>Division: <?php echo $division; ?></label><br>
            <label>Service: <?php echo $service; ?></label><br>
            <label>CIN: <?php echo $cin; ?></label><br>
            <label>Téléphone: <?php echo $telephone; ?></label><br>
        </div>

      </main>
   </div>
   <script src="script.js"></script>
   
</body>
</html>
