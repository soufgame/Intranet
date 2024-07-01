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
$userID = $_SESSION['id']; // Récupérer l'ID de l'utilisateur connecté

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

// Créer la connexion
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Afficher l'erreur de connexion
}

// Préparer la requête SQL pour sélectionner les fichiers de l'utilisateur connecté
$stmt = $conn->prepare("
    SELECT f.id, f.file_name, f.message, f.file_data, f.file_data_2, f.file_data_3, f.username, f.date, f.time
    FROM files f
    WHERE f.user_id = ?
    ORDER BY f.date DESC, f.time DESC
");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error); // Afficher l'erreur de préparation de la requête
}

$stmt->bind_param("i", $userID); // Utiliser "i" pour binder un entier

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error); // Afficher l'erreur d'exécution de la requête
}

$result = $stmt->get_result();
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
      table {
    width: 100%;
    max-width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-family: Arial, sans-serif;
}


table th,
table td {
    border: 1px solid #dddddd;
    padding: 12px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    min-width: 100px; /* Ajustez la valeur selon vos besoins */
}


    table th {
        background-color: #242424;
        color: #FFFFFF;
        font-size: 16px;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
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
           <a href="createticket.php"">
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
        <h1> Message envoyee</h1>
        <!-- Formulaire de la première page supprimé -->
        <div class="table-container" style="overflow: auto;">
    <table border='1'>
        <tr><th>Titre</th><th>Date</th><th>Time</th><th>Envoyé à</th></tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='" . htmlspecialchars($row["id"]) . "' onclick=\"window.location='emailenvoye.php?file_id=" . htmlspecialchars($row["id"]) . "'\">";
            echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

      </main>
      <div class="right">
        <div class="top">
          <button id="menu_bar">
            <span class="material-symbols-sharp">menu</span>
          </button>
          <div class="profile">
            <div class="info">
              <p><b><?php echo htmlspecialchars($nom . ' ' . $prenom); ?></b></p>
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
$stmt->close();
$conn->close();
?>
