<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Utilisateur';

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialisation de la variable pour le message de succès
$successMessage = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description']) && isset($_POST['categorie']) && isset($_POST['priorite'])) {
        $description = $conn->real_escape_string($_POST['description']);
        $categorie = $conn->real_escape_string($_POST['categorie']);
        $priorite = $conn->real_escape_string($_POST['priorite']);
        $userID = $_SESSION['id'];
        $dateOuverture = date('Y-m-d H:i:s');

        $sql = "INSERT INTO Tickets (Description, Categorie, userID, DateOuverture, Statut) 
                VALUES ('$description', '$categorie', $userID, '$dateOuverture', 'Ouvert')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Le ticket a été créé avec succès.";
        } else {
            $successMessage = "Erreur: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
/* Style général pour les zones de texte */
input[type="text"],
textarea,
select {
    background-color: #CDCDCD; /* Fond sombre */
    color: #000000; /* Texte clair */
    border: 1px solid #444; /* Bordure */
    border-radius: 8px; /* Coins arrondis */
    padding: 10px; /* Espacement intérieur */
    font-size: 16px; /* Taille du texte */
    width: 150%; /* Prend toute la largeur disponible */
    max-width: 500px; /* Limite la largeur maximale */
    box-sizing: border-box; /* Inclut padding et border dans la largeur */
    margin-bottom: 15px; /* Espacement entre les champs */
}

/* Style pour les zones de texte au focus */
input[type="text"]:focus,
textarea:focus,
select:focus {
    border-color: #dc2f55; /* Couleur de bordure lors du focus */
    box-shadow: 0 0 5px rgba(220, 47, 85, 0.6); /* Légère ombre pour effet focus */
    outline: none; /* Supprime le contour par défaut */
}

/* Style spécifique pour les textarea */
textarea {
    height: 120px; /* Hauteur par défaut */
    resize: vertical; /* Permet le redimensionnement vertical */
}

/* Style spécifique pour les boutons de soumission */
button[type="submit"] {
    background-color: #08d; /* Couleur de fond */
    color: white; /* Couleur du texte */
    padding: 10px 15px; /* Espacement intérieur */
    border: none; /* Sans bordure */
    border-radius: 8px; /* Coins arrondis */
    cursor: pointer; /* Curseur pointeur */
    font-size: 18px; /* Taille du texte */
    text-align: center; /* Centre le texte */
    width: 100%; /* Prend toute la largeur disponible */
    max-width: 200px; /* Limite la largeur maximale */
    margin-top: 20px; /* Espacement en haut */
}

button[type="submit"]:hover {
    background-color: #06b; /* Couleur de fond au survol */
}

/* Style pour les messages de succès */
.success-message {
    background-color: #078C05; 
    color: white; /* Texte blanc */
    text-align: center; /* Centrage du texte */
    padding: 10px; /* Espacement intérieur */
    margin-top: 20px; /* Espacement en haut */
    font-size: 18px; /* Taille du texte */
    width: 200%; /* Prend toute la largeur disponible */
    box-sizing: border-box; /* Inclut padding et border dans la largeur */
}

.success-message button {
    background-color: #085F07; /* Fond gris foncé */
    color: white; /* Texte blanc */
    border: none; /* Sans bordure */
    padding: 10px 20px; /* Espacement intérieur */
    text-align: center; /* Centre le texte */
    font-size: 18px; /* Taille du texte */
    margin-top: 10px; /* Espacement en haut */
    cursor: pointer; /* Curseur pointeur */
}

label {
    font-weight: bold; /* Texte en gras */
    margin-bottom: 5px; /* Espacement en bas */
    display: block; /* Prend toute la largeur disponible */
    color: #000; /* Couleur du texte en noir */
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
      <main>
        <h1>Support</h1>
        <!-- Formulaire de création de ticket -->
        <div class="container">
            <div class="ticket-form">
                <form method="post" action="">
                    <div class="input-container">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4" cols="50" required></textarea>
                    </div>
                    <div class="input-container">
                        <label for="categorie">Catégorie:</label>
                        <select id="categorie" name="categorie" required>
                            <option value="logiciel">Logiciel</option>
                            <option value="materiel">Matériel</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label for="priorite">Priorité:</label>
                        <select id="priorite" name="priorite" required>
                            <option value="Basse">Basse</option>
                            <option value="Normale">Normale</option>
                            <option value="Haute">Haute</option>
                        </select>
                    </div>
                    <button type="submit">Créer Ticket</button>
                </form>
                <!-- Affichage du message de succès -->
                <?php if (!empty($successMessage)) : ?>
                <div class="success-message" id="successMessage">
                    <?php echo htmlspecialchars($successMessage); ?>
                    <button onclick="dismissMessage()">OK</button>
                </div>
                <?php endif; ?>
            </div>
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
    // JavaScript pour faire disparaître le message après avoir cliqué sur "OK"
    function dismissMessage() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }
  </script>
</body>
</html>

<?php
$conn->close();
?>
