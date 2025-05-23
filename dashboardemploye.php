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

// Requête pour le nombre de messages non lus pour l'utilisateur actuel
$sqlMessagesNonVus = "SELECT COUNT(*) AS countMessagesNonVus FROM files WHERE username = ? AND is_read = 0";

$stmtMessagesNonVus = $conn->prepare($sqlMessagesNonVus);
if (!$stmtMessagesNonVus) {
    die("Error preparing statement: " . $conn->error);
}

$stmtMessagesNonVus->bind_param("s", $username); // 's' indique que le paramètre est une chaîne (username)
$stmtMessagesNonVus->execute();
$resultMessagesNonVus = $stmtMessagesNonVus->get_result();

$countMessagesNonVus = 0;
if ($resultMessagesNonVus->num_rows > 0) {
    $rowMessagesNonVus = $resultMessagesNonVus->fetch_assoc();
    $countMessagesNonVus = $rowMessagesNonVus['countMessagesNonVus'];
}

$stmtMessagesNonVus->close();


// Requête pour le nombre de tickets en cours
$sqlTicketsEnCours = "SELECT COUNT(*) AS countTicketsEnCours FROM files WHERE user_id = ? ";
$stmtTicketsEnCours = $conn->prepare($sqlTicketsEnCours);
if (!$stmtTicketsEnCours) {
    die("Error preparing statement: " . $conn->error);
}

$stmtTicketsEnCours->bind_param("i", $user_id);
$stmtTicketsEnCours->execute();
$resultTicketsEnCours = $stmtTicketsEnCours->get_result();

$countTicketsEnCours = 0;
if ($resultTicketsEnCours->num_rows > 0) {
    $rowTicketsEnCours = $resultTicketsEnCours->fetch_assoc();
    $countTicketsEnCours = $rowTicketsEnCours['countTicketsEnCours'];
}

$stmtTicketsEnCours->close();

// Requête pour le nombre total de tickets résolus
$sqlTicketsResolus = "SELECT COUNT(*) AS countTicketsResolus FROM files WHERE user_id = ? ";
$stmtTicketsResolus = $conn->prepare($sqlTicketsResolus);
if (!$stmtTicketsResolus) {
    die("Error preparing statement: " . $conn->error);
}

$stmtTicketsResolus->bind_param("i", $user_id);
$stmtTicketsResolus->execute();
$resultTicketsResolus = $stmtTicketsResolus->get_result();

$countTicketsResolus = 0;
if ($resultTicketsResolus->num_rows > 0) {
    $rowTicketsResolus = $resultTicketsResolus->fetch_assoc();
    $countTicketsResolus = $rowTicketsResolus['countTicketsResolus'];
}

$stmtTicketsResolus->close();

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['id'];

// Requête pour le nombre de tickets en cours
$sqlTicketsEnCours = "SELECT COUNT(*) AS countTicketsEnCours FROM intervention WHERE Statut = 'en cours' AND userID = ?";
$stmtTicketsEnCours = $conn->prepare($sqlTicketsEnCours);
if (!$stmtTicketsEnCours) {
    die("Error preparing statement: " . $conn->error);
}

$stmtTicketsEnCours->bind_param("i", $user_id); // 'i' pour un entier (user_id)
$stmtTicketsEnCours->execute();
$resultTicketsEnCours = $stmtTicketsEnCours->get_result();

$countTicketsEnCours = 0;
if ($resultTicketsEnCours->num_rows > 0) {
    $rowTicketsEnCours = $resultTicketsEnCours->fetch_assoc();
    $countTicketsEnCours = $rowTicketsEnCours['countTicketsEnCours'];
}

$stmtTicketsEnCours->close();

// Requête pour le nombre total de tickets résolus
$sqlTicketsResolus = "SELECT COUNT(*) AS countTicketsResolus FROM intervention WHERE Statut = 'resolu' AND userID = ?";
$stmtTicketsResolus = $conn->prepare($sqlTicketsResolus);
if (!$stmtTicketsResolus) {
    die("Error preparing statement: " . $conn->error);
}

$stmtTicketsResolus->bind_param("i", $user_id); // 'i' pour un entier (user_id)
$stmtTicketsResolus->execute();
$resultTicketsResolus = $stmtTicketsResolus->get_result();

$countTicketsResolus = 0;
if ($resultTicketsResolus->num_rows > 0) {
    $rowTicketsResolus = $resultTicketsResolus->fetch_assoc();
    $countTicketsResolus = $rowTicketsResolus['countTicketsResolus'];
}

$stmtTicketsResolus->close();


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
        <h1>Dashboard</h1>
        <div class="insights">
          <!-- Total Messages Non Vus -->
          <div class="sales">
            <img src="./images/voir.png" alt="Unread Message Icon" class="icon">
            <div class="middle">
              <div class="left">
                <h3>Messages non vus</h3>
                <h1><?php echo $countMessagesNonVus; ?></h1>
              </div>
            </div>
          </div>
          <!-- Total Tickets en Cours -->
          <!-- Total Tickets en Cours -->
<div class="expenses">
    <img src="./images/billet.png" alt="Unread Message Icon" class="icon">
    <div class="middle">
        <div class="left">
            <h3>Tickets en cours</h3>
            <h1><?php echo $countTicketsEnCours; ?></h1>
        </div>
    </div>
</div>

          <!-- Total Tickets Résolus -->
          <div class="income">
            <img src="./images/cochee.png" alt="Unread Message Icon" class="icon">
            <div class="middle">
              <div class="left">
                <h3>Tickets Résolus</h3>
                <h1><?php echo $countTicketsResolus; ?></h1>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Right Section -->
      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>
          
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

<?php
// Fermer la connexion à la base de données
$conn->close();
?>
