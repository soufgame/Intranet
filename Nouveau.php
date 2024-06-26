<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les utilisateurs
    $stmt = $pdo->prepare("SELECT username FROM users ORDER BY username");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . htmlspecialchars($e->getMessage());
    exit();
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : 'Non spécifié';
$prenom = isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : 'Non spécifié';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" type="text/css" href="style/nouveau.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>

<div class="container">
    <form id="emailForm" action="upload.php" method="post" enctype="multipart/form-data">
        <label for="recipient-input">Destinataires</label>
        <div id="recipient-container">
        <input type="text" id="recipient-input" placeholder="Saisissez un nom d'utilisateur">
        </div>
        <ul id="suggestions" class="suggestions-list"></ul>

        <input type="hidden" name="recipients" id="recipients">

        <label for="file_name">Objet</label>
        <input type="text" id="file_name" name="file_name" class="small" required>

        <label for="message">Message {max 1000}:</label>
        <textarea id="message" name="message"></textarea>

        <div class="file-input-container">
            <label for="file_1">Sélectionner le premier fichier:</label>
            <input type="file" id="file_1" name="file[]">
            <button type="button" class="clear-file" id="clearFile1">&times;</button>
        </div>

        <div class="file-input-container">
            <label for="file_2">Sélectionner le deuxième fichier:</label>
            <input type="file" id="file_2" name="file[]">
            <button type="button" class="clear-file" id="clearFile2">&times;</button>
        </div>

        <div class="file-input-container">
            <label for="file_3">Sélectionner le troisième fichier:</label>
            <input type="file" id="file_3" name="file[]">
            <button type="button" class="clear-file" id="clearFile3">&times;</button>
        </div>

        <button type="submit">Envoyer</button>
    </form>
</div>

<div class="sidebar">
    <button id="rendez-vous">Support</button> <a href="historique.php" id="rendez-vous">Mail envoye</a>
    <a href="Nouveau.php" id="patient">Nouveau</a>
    <a id="logoutButton" href="logout.php">LOG OUT</a>
    <button id="Dashboard">Dashboard</button> <a href="dashboard.php" id="Dashboard">Dashboard</a>
    <button id="support">Support</button> <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
</div>

<div class="doctor-label"><?php echo 'Name: ' . $prenom . ' ' . $nom; ?></div>

<style>
    .remove-icon {
  width: 15px; /* Augmenter la taille de l'icône */
  height: auto; /* Hauteur automatique pour conserver les proportions */
  margin-right: 5px; /* Espace entre l'icône et le texte */
}

</style>

</body>
</html>
<script>
// Tableau pour stocker les destinataires sélectionnés
let selectedRecipients = [];

// Fonction pour ajouter un destinataire
// Fonction pour ajouter un destinataire
function addRecipient(username) {
    if (!selectedRecipients.includes(username)) {
        selectedRecipients.push(username);

        const recipientsDiv = document.getElementById('recipient-container');
        const recipientButton = document.createElement('button');
        recipientButton.classList.add('recipient');

        // Ajout de l'icône à côté du texte
        const icon = document.createElement('img');
        icon.src = 'remove.png'; // Chemin de votre icône de suppression
        icon.alt = 'Remove';
        icon.classList.add('remove-icon');
        recipientButton.appendChild(icon);

        // Texte du username
        const usernameText = document.createElement('span');
        usernameText.textContent = username;
        recipientButton.appendChild(usernameText);

        // Gestion du clic sur le bouton
        recipientButton.addEventListener('click', () => removeRecipient(username));

        recipientsDiv.appendChild(recipientButton);

        // Mettre à jour l'input caché avec les destinataires sélectionnés
        document.getElementById('recipients').value = JSON.stringify(selectedRecipients);
        updateInputPlaceholder();
    }
}


// Fonction pour supprimer un destinataire
function removeRecipient(username) {
    selectedRecipients = selectedRecipients.filter(recipient => recipient !== username);
    const recipientsButtons = document.querySelectorAll('.recipient');
    recipientsButtons.forEach(button => {
        if (button.textContent === username) {
            button.remove();
        }
    });
    document.getElementById('recipients').value = JSON.stringify(selectedRecipients);
    updateInputPlaceholder();
}

// Fonction pour mettre à jour le placeholder de l'input destinataires
function updateInputPlaceholder() {
    const recipientInput = document.getElementById('recipient-input');
    if (selectedRecipients.length > 0) {
        recipientInput.placeholder = '';
    } else {
        recipientInput.placeholder = 'Saisissez un nom d\'utilisateur';
    }
}

// Gestion de l'entrée utilisateur pour filtrer les suggestions de destinataires
document.getElementById('recipient-input').addEventListener('input', function() {
    const filter = this.value.trim();

    if (filter !== '') {
        $.ajax({
            url: 'search_users.php',
            type: 'GET',
            data: { search: filter },
            dataType: 'json',
            success: function(response) {
                const suggestions = document.getElementById('suggestions');
                suggestions.innerHTML = '';
                if (response.length > 0) {
                    response.forEach(user => {
                        const suggestion = document.createElement('li');
                        suggestion.textContent = user.username;
                        suggestion.classList.add('suggestion-item');
                        suggestion.addEventListener('click', () => addRecipient(user.username));
                        suggestions.appendChild(suggestion);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur:', status, error);
            }
        });
    } else {
        // Si le champ est vide, effacer les suggestions
        const suggestions = document.getElementById('suggestions');
        suggestions.innerHTML = '';
    }
});

// Event listener pour le formulaire de soumission
document.getElementById('emailForm').addEventListener('submit', function(event) {
    // Vérifie si au moins un destinataire a été sélectionné
    if (selectedRecipients.length === 0) {
        event.preventDefault();
        alert('Veuillez ajouter au moins un destinataire.');
    }
});

// Fonction pour initialiser les boutons de suppression de fichier
function handleFileInput(fileInputId, clearButtonId) {
    const fileInput = document.getElementById(fileInputId);
    const clearButton = document.getElementById(clearButtonId);

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            clearButton.style.display = 'block';
        } else {
            clearButton.style.display = 'none';
        }
    });

    clearButton.addEventListener('click', function() {
        fileInput.value = '';
        this.style.display = 'none';
    });
}

// Initialisation des boutons de suppression de fichier pour chaque champ de fichier
handleFileInput('file_1', 'clearFile1');
handleFileInput('file_2', 'clearFile2');
handleFileInput('file_3', 'clearFile3');

</script>

</body>
</html>
