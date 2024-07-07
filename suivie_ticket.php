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
$sqlInterventions = "SELECT * FROM intervention WHERE userID = ? AND statut != 'ferme';";

$stmtInterventions = $conn->prepare($sqlInterventions);
if (!$stmtInterventions) {
    die("Error preparing statement: " . $conn->error);
}

$stmtInterventions->bind_param("i", $_SESSION['id']); // Utiliser $_SESSION['id'] pour l'ID de l'utilisateur
$stmtInterventions->execute();
$resultInterventions = $stmtInterventions->get_result();

// Gestion de la mise à jour du statut
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['interventionID'])) {
    $interventionID = $_POST['interventionID'];

    // Préparer et exécuter la requête UPDATE
    $sqlUpdate = "UPDATE intervention SET Statut = 'ferme' WHERE interventionID = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("i", $interventionID);

    if ($stmtUpdate->execute()) {
        // Redirection après la mise à jour réussie
        header("Location: suivie_ticket.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du statut.";
    }
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

    /* CSS pour les boutons */
    .action-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 5px 10px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
      border-radius: 5px;
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
        <h1>Suivre votre Ticket</h1>
        
        <!-- Table des interventions -->
       <!-- Table des interventions -->
<table>
  <thead>
    <tr>
      <th>Intervention ID</th>
      <th>Ticket ID</th>
      <th>Description</th>
      <th>Catégorie</th>
      <th>Date d'Ouverture</th>
      <th>Date de Clôture</th>
      <th>Statut</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    if ($resultInterventions->num_rows > 0) {
      while($row = $resultInterventions->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['interventionID']); ?></td>
          <td><?php echo htmlspecialchars($row['ticketID']); ?></td>
          <td><?php echo htmlspecialchars($row['Description']); ?></td>
          <td><?php echo htmlspecialchars($row['Categorie']); ?></td>
          <td><?php echo htmlspecialchars($row['DateOuverture']); ?></td>
          <td><?php echo htmlspecialchars($row['DateCloture']); ?></td>
          <td><?php echo htmlspecialchars($row['Statut']); ?></td>
          <td>
            <?php if ($row['Statut'] == 'resolu'): ?>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="interventionID" value="<?php echo $row['interventionID']; ?>">
                <button type="submit" class="action-btn">Valider</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; 
    } else {
      echo '<tr><td colspan="8">Aucun ticket en intervention.</td></tr>';
    }
    ?>
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
