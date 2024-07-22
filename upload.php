<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Soufiane@2003";
$dbname = "intranet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données du formulaire
$file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$user_id = $_SESSION['id']; // Utilisez $_SESSION['id'] au lieu de $_SESSION['user_id']

// Récupérer les destinataires sous forme de tableau
$recipients = isset($_POST['recipients']) ? json_decode($_POST['recipients'], true) : [];

if (!is_array($recipients)) {
    $recipients = [];
}

$file_data_1 = null;
$file_data_2 = null;
$file_data_3 = null;

if (isset($_FILES['file'])) {
    $files = $_FILES['file'];

    if (isset($files['tmp_name'][0]) && $files['error'][0] === UPLOAD_ERR_OK) {
        $file_data_1 = file_get_contents($files['tmp_name'][0]);
    }

    if (isset($files['tmp_name'][1]) && $files['error'][1] === UPLOAD_ERR_OK) {
        $file_data_2 = file_get_contents($files['tmp_name'][1]);
    }

    if (isset($files['tmp_name'][2]) && $files['error'][2] === UPLOAD_ERR_OK) {
        $file_data_3 = file_get_contents($files['tmp_name'][2]);
    }
}

// Obtenir la date et l'heure actuelles
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Préparer et exécuter les requêtes d'insertion pour chaque destinataire
foreach ($recipients as $username) {
    $stmt = $conn->prepare("INSERT INTO files (file_name, file_data, file_data_2, file_data_3, message, user_id, username, date, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $file_name, $file_data_1, $file_data_2, $file_data_3, $message, $user_id, $username, $current_date, $current_time);

    if ($stmt->execute()) {
        $success_message = "Message envoye avec succès pour $username.";
    } else {
        $error_message = "Erreur pour $username: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téléchargement de fichiers</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        .success-message {
            color: green;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .return-button {
            background-color: #c2fbd7;
            border-radius: 100px;
            box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,rgba(44, 187, 99, .15) 0 1px 2px,rgba(44, 187, 99, .15) 0 2px 4px,rgba(44, 187, 99, .15) 0 4px 8px,rgba(44, 187, 99, .15) 0 8px 16px,rgba(44, 187, 99, .15) 0 16px 32px;
            color: green;
            cursor: pointer;
            display: inline-block;
            font-family: CerebriSans-Regular,-apple-system,system-ui,Roboto,sans-serif;
            padding: 20px 50px; 
            text-align: center;
            text-decoration: none;
            transition: all 250ms;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .return-button:hover {
            box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,rgba(44,187,99,.25) 0 1px 2px,rgba(44,187,99,.25) 0 2px 4px,rgba(44,187,99,.25) 0 4px 8px,rgba(44,187,99,.25) 0 8px 16px,rgba(44,187,99,.25) 0 16px 32px;
            transform: scale(1.05) rotate(-1deg);
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($success_message)) { ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if(isset($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        <a href="javascript:history.go(-1)" class="return-button">Retour</a>
    </div>
</body>
</html>
