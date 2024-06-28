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

// Préparer la requête SQL pour sélectionner les fichiers de l'utilisateur connecté
$stmt = $conn->prepare("
    SELECT files.id, file_name, message, file_data, file_data_2, file_data_3, date, time, users.username, is_read 
    FROM files 
    INNER JOIN users ON files.user_id = users.id 
    WHERE files.username = ? 
    ORDER BY date DESC, time DESC
");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
}

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Boite de reception</title>
  <!-- Inclure le fichier CSS principal -->
  <link rel="stylesheet" href="style.css">
  <!-- Inclure les autres ressources CSS si nécessaire -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <style>
    /* Styles spécifiques pour la table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
        background-color: #fff; /* Fond blanc pour les cellules de la table */
        color: #333; /* Texte noir pour les cellules de la table */
    }
    th {
        background-color: #222;
        color: white;
        font-weight: bold;
    }
    tr.unread {
        font-weight: bold;
    }
    tr:hover {
        background-color: #f2f2f2;
    }
    /* Styles pour le mode sombre */
    .dark-mode {
        background-color: #222; /* Fond sombre pour le corps de la page */
        color: white; /* Texte blanc pour le corps de la page */
    }
    .dark-mode th {
        background-color: #222; /* Fond sombre pour les en-têtes de colonne */
        color: white; /* Texte blanc pour les en-têtes de colonne */
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
        
        <!-- Insérer la table ici -->
        <div class="table-container">
          <table border='1'>
            <tr><th>Titre</th><th>Date</th><th>Time</th><th>Expéditeur</th><th>V</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
  <tr <?php echo $row['is_read'] ? '' : 'class="unread"' ?>>
    <td><a href="email.php?file_id=<?php echo $row['id']; ?>">
        <?php echo htmlspecialchars($row["file_name"]) ?></a></td>
    <td><a href="email.php?file_id=<?php echo $row['id']; ?>">
        <?php echo htmlspecialchars($row["date"]) ?></a></td>
    <td><a href="email.php?file_id=<?php echo $row['id']; ?>">
        <?php echo htmlspecialchars($row["time"]) ?></a></td>
    <td><a href="email.php?file_id=<?php echo $row['id']; ?>">
        <?php echo htmlspecialchars($row["username"]) ?></a></td>
    <td><a href="email.php?file_id=<?php echo $row['id']; ?>">
        <?php echo $row['is_read'] ? "<img src='vue.png' alt='Vue' width='20' height='20'>" : "<img src='non vue.png' alt='Non vue' width='20' height='20'>" ?></a></td>
  </tr>
<?php endwhile; ?>


          </table>
        </div>
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
   <!-- Inclure votre script JavaScript à la fin de la page -->
   <script>
   const themeToggler = document.querySelector('.theme-toggler');

   themeToggler.addEventListener('click', function() {
       document.body.classList.toggle('dark-mode');
   });
   </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
