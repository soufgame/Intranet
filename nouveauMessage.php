<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les informations de session
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

// Inclusion du formulaire PHP pour les destinataires, l'objet, le message, et les fichiers
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
    .suggestion-item {
        display: inline-block;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        margin: 5px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .suggestion-item:hover {
        max-width: none;
        overflow: visible;
        white-space: normal;
    }

    .remove-icon {
        width: 15px;
        height: auto;
        margin-right: 5px;
    }
    /* Styles spécifiques pour les zones de texte du formulaire */
    #emailForm {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

#emailForm label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

#emailForm input[type="text"],
#emailForm input[type="file"],
#emailForm textarea {
    background-color: #2E2E2E;
    border-radius: 12px;
    border: 0;
    box-sizing: border-box;
    color: #eee;
    font-size: 18px;
    outline: 0;
    padding: 10px 20px;
    width: 500px;
    height: 40px; /* Adjust height as needed */

}

#emailForm input[type="text"]:focus,
#emailForm input[type="file"]:focus,
#emailForm textarea:focus {
    outline: none;
    border-bottom: 2px solid #dc2f55; /* Consistent with the animation color */
}

#emailForm textarea {
    height: 200px; /* Make textarea longer */
    resize: vertical;
}

#emailForm .file-input-container {
    position: relative;
    margin-bottom: 15px;
}

#emailForm .file-input-container input[type="file"] {
    display: block;
    padding: 5px;
}

#emailForm .clear-file {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    background-color: transparent;
    border: none;
    color: #f00;
    font-size: 20px;
    cursor: pointer;
    display: none;
}

#emailForm button[type="submit"] {
    background-color: #08d;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 18px;
    text-align: center;
    width: 100%;
    margin-top: 20px;
}

#emailForm button[type="submit"]:hover {
    background-color: #06b;
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
      <main>
        <h1>Nouveau message</h1>
        <!-- Insertion du formulaire de la première page ici -->
        <div class="container">
            <form id="emailForm" action="upload.php" method="post" enctype="multipart/form-data">
                <label for="recipient-input">Destinataires</label>
                <div id="recipient-container">
                    <input type="text" id="recipient-input" placeholder="Saisissez un nom d'utilisateur">
                </div>
                <ul id="suggestions" class="suggestions-list"></ul>

                <input type="hidden" name="recipients" id="recipients">

                <label for="file_name">Objet</label>
                <input type="text" id="file_name" name="file_name" class="small" required placeholder="Saisissez un Objet">

                <label for="message">Message:</label>
                <textarea id="message" name="message"></textarea >

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
    let selectedRecipients = [];

    function addRecipient(username) {
        if (!selectedRecipients.includes(username)) {
            selectedRecipients.push(username);

            const recipientsDiv = document.getElementById('recipient-container');
            const recipientButton = document.createElement('button');
            recipientButton.classList.add('recipient');

            const icon = document.createElement('img');
            icon.src = 'remove.png';
            icon.alt = 'Remove';
            icon.classList.add('remove-icon');
            recipientButton.appendChild(icon);

            const usernameText = document.createElement('span');
            usernameText.textContent = username;
            recipientButton.appendChild(usernameText);

            recipientButton.addEventListener('click', () => removeRecipient(username));

            recipientsDiv.appendChild(recipientButton);

            document.getElementById('recipients').value = JSON.stringify(selectedRecipients);
            updateInputPlaceholder();
        }
    }

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

    function updateInputPlaceholder() {
        const recipientInput = document.getElementById('recipient-input');
        if (selectedRecipients.length > 0) {
            recipientInput.placeholder = '';
        } else {
            recipientInput.placeholder = 'Saisissez un nom d\'utilisateur';
        }
    }

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
            const suggestions = document.getElementById('suggestions');
            suggestions.innerHTML = '';
        }
    });

    document.getElementById('emailForm').addEventListener('submit', function(event) {
        if (selectedRecipients.length === 0) {
            event.preventDefault();
            alert('Veuillez ajouter au moins un destinataire.');
        }
    });

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

    handleFileInput('file_1', 'clearFile1');
    handleFileInput('file_2', 'clearFile2');
    handleFileInput('file_3', 'clearFile3');
  </script>
</body>
</html>

<?php
$conn->close();
?>
