<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer le nom d'utilisateur depuis la session
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

// Requête pour récupérer les interventions de l'utilisateur actuel
$sqlInterventions = "SELECT * FROM intervention WHERE userID = ? AND statut != 'fermé';
";
$stmtInterventions = $conn->prepare($sqlInterventions);
if (!$stmtInterventions) {
    die("Error preparing statement: " . $conn->error);
}

$stmtInterventions->bind_param("i", $_SESSION['id']); // Utiliser $_SESSION['id'] pour l'ID de l'utilisateur
$stmtInterventions->execute();
$resultInterventions = $stmtInterventions->get_result();

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
  <style>
    /* CSS pour la table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-family: Arial, sans-serif;
    }

    table th, table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #f2f2f2;
      color: #333;
    }

    table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    table tbody tr:hover {
      background-color: #f1f1f1;
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
           <a href="suivie_ticket.php">
             <span class="material-symbols-sharp">report_gmailerrorred</span>
             <h3>Suivie de Ticket</h3>
           </a>
           <a href="#">
             <span class="material-symbols-sharp">settings</span>
             <h3>Historique de Ticket</h3>
           </a>
           <a href="profil_user.php">
             <span class="material-symbols-sharp">person_outline</span>
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
        <h1>Suivre votre Ticket</h1>
        
        <!-- Table des interventions -->
        <table>
          <thead>
            <tr>
              <th>Intervention ID</th>
              <th>Ticket ID</th>
              <th>Description</th>
              <th>Catégorie</th>
              <th>Date d'Ouverture</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $resultInterventions->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['interventionID']); ?></td>
                <td><?php echo htmlspecialchars($row['ticketID']); ?></td>
                <td><?php echo htmlspecialchars($row['Description']); ?></td>
                <td><?php echo htmlspecialchars($row['Categorie']); ?></td>
                <td><?php echo htmlspecialchars($row['DateOuverture']); ?></td>
                <td><?php echo htmlspecialchars($row['Statut']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
       
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
