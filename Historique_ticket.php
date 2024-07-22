<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session
$username = $_SESSION['username']; // Récupérer le nom d'utilisateur depuis la session
$userID = $_SESSION['id']; // Récupérer l'ID de l'utilisateur depuis la session

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requête SQL pour récupérer les interventions de l'utilisateur actuel
$sql = "SELECT * FROM intervention WHERE userID = $userID";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historique des Ticket</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="style.css">
  <style>
    /* Ajoutez votre CSS personnalisé ici */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    th {
      background-color: #f2f2f2;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
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
        <h1>Historique des Tickets</h1>
        
        <table>
          <thead>
            <tr>
              <th>Ticket ID</th>
              <th>Description</th>
              <th>Catégorie</th>
              <th>Date Ouverture</th>
              <th>Date Clôture</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Afficher les données de chaque ligne
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['ticketID'] . "</td>";
                    echo "<td>" . $row['Description'] . "</td>";
                    echo "<td>" . $row['Categorie'] . "</td>";
                    echo "<td>" . $row['DateOuverture'] . "</td>";
                    echo "<td>" . $row['DateCloture'] . "</td>";
                    echo "<td>" . $row['Statut'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Aucun ticket trouvé.</td></tr>";
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
