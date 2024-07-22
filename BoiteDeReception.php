<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session
$username = $_SESSION['username'];
$id = $_SESSION['id'];

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
$sql = "
    SELECT files.id, file_name, message, file_data, file_data_2, file_data_3, date, time, users.username, is_read 
    FROM files 
    INNER JOIN users ON files.user_id = users.id 
    WHERE 1"; // Condition de base

// Vérifier si le paramètre username est défini
$username_filter = "";
if (isset($_GET['username']) && !empty($_GET['username'])) {
    $username_filter = trim($_GET['username']);
    $sql .= " AND users.username = ?";
}

$sql .= " ORDER BY date DESC, time DESC";

$stmt = $conn->prepare($sql);

if (!empty($username_filter)) {
    $stmt->bind_param("s", $username_filter);
}

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
  <link rel="stylesheet" href="style.css">
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
        background-color: #fff;
        color: #333;
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
        background-color: #222;
        color: white;
    }
    .dark-mode th {
        background-color: #222;
        color: white;
    }
    /* Styles pour le formulaire de filtre */
   /* Styles pour le formulaire de filtre */
.filter-form {
    margin: 20px 0;
}

.filter-form input {
    padding: 8px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    width: 200px; /* Ajuster la largeur selon vos besoins */
}

.filter-form button {
    padding: 8px 12px;
    background-color: #007bff; /* Couleur du bouton */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.filter-form button:hover {
    background-color: #0056b3; /* Couleur au survol */
}

.clear-filter {
    color: red;
    cursor: pointer;
    margin-left: 10px;
    font-size: 18px; /* Taille de la croix */
    vertical-align: middle; /* Aligner verticalement */
}

.clear-filter:hover {
    color: darkred; /* Couleur au survol de la croix */
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
        <h1>Boite de reception</h1>
        
        <!-- Formulaire de filtre -->
        <form method="GET" action="BoiteDeReception.php" class="filter-form">
            <label for="username">Filtrer par Expéditeur :</label>
            <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" value="<?php echo htmlspecialchars($username_filter); ?>">
            <button type="submit">Filtrer</button>
            <?php if (!empty($username_filter)): ?>
                <span class="clear-filter" onclick="window.location.href='BoiteDeReception.php'">✖</span>
            <?php endif; ?>
        </form>

        <!-- Insérer la table ici -->
        <div class="table-container">
          <table border='1'>
            <tr><th>Titre</th><th>Date</th><th>Heure</th><th>Expéditeur</th><th>V</th></tr>
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
