
<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['prenom']) || !isset($_SESSION['nom']) || !isset($_SESSION['id'])) {
    // Redirige vers la page de connexion ou affiche un message d'erreur
    header("Location: login.php"); // Redirige vers la page de connexion
    exit();
}

// Récupère l'ID de l'utilisateur à partir de la session
$userID = $_SESSION['id'];

// Connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "Soufiane@2003";
$database = "intranet";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $database);

// Vérifie la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Initialisation de la variable pour le message de succès
$successMessage = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs du formulaire sont définis dans $_POST
    if (isset($_POST['description']) && isset($_POST['categorie']) && isset($_POST['priorite'])) {
        // Récupérer les données du formulaire
        $description = $_POST['description'];
        $categorie = $_POST['categorie'];
        $priorite = $_POST['priorite'];

        // Date et heure actuelles
        $dateOuverture = date('Y-m-d H:i:s');

        // Requête d'insertion
        $sql = "INSERT INTO Tickets (Description, Categorie, userID, DateOuverture, Statut) 
                VALUES ('$description', '$categorie', $userID, '$dateOuverture', 'Ouvert')";

        if ($conn->query($sql) === TRUE) {
            // Message de succès à afficher
            $successMessage = "Le ticket a été créé avec succès.";
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" type="text/css" href="style/support.css">
    <style>
      .success-message {
    background-color: #4C4949; /* Nouvelle couleur de fond */
    color: white; /* Couleur du texte en blanc */
    text-align: center;
    padding: 20px; /* Augmentation de l'espacement intérieur */
    margin-bottom: 20px;
    display: <?php echo $successMessage ? 'block' : 'none'; ?>;
    font-size: 18px; /* Augmentation de la taille du texte */
}

.success-message button {
    background-color: #555;
    color: white;
    border: none;
    padding: 12px 24px; /* Ajustement des dimensions du bouton */
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 18px; /* Même taille de police que le message */
    margin-top: 10px;
    cursor: pointer;
}

    </style>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>

<!-- Affichage du message de succès -->
<div class="success-message" id="successMessage">
    <?php echo $successMessage; ?>
    <button onclick="dismissMessage()">OK</button>
</div>

<div class="sidebar">
    <a href="historique.php" id="rendez-vous">Mail envoye</a>
    <a href="Nouveau.php" id="patient">Nouveau mail</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboard.php" id="Dashboard">Dashboard</a>
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
    <div class="ticket-form">
        <h2>Create a Ticket</h2>
        <form method="post" action="">
            <div class="input-container">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea>
            </div>
            <div class="input-container">
                <label for="categorie">Catégorie:</label>
                <select id="categorie" name="categorie" required>
                    <option value="logiciel">logiciel</option>
                    <option value="materiel">materiel</option>
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
    </div>

    <?php
    $prenom = $_SESSION['prenom'];
    $nom = $_SESSION['nom'];
    ?>
    <header>
        <div class="doctor-label">Name: <?php echo htmlspecialchars($prenom . ' ' . $nom); ?></div>
    </header>

</body>
</html>

<script>
    // JavaScript pour faire disparaître le message après avoir cliqué sur "OK"
    function dismissMessage() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }
</script>
