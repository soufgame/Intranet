<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    echo "Utilisateur connecté : " . htmlspecialchars($_SESSION['username']) . "<br>";
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = htmlspecialchars($_SESSION['nom']);
$prenom = htmlspecialchars($_SESSION['prenom']);
$username = htmlspecialchars($_SESSION['username']);

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

// Créer la connexion
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'identifiant de fichier est passé en paramètre
if (isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];

    // Préparer la requête SQL pour sélectionner les informations du fichier spécifié
    $stmt = $conn->prepare("SELECT file_name, message, file_data, file_data_2, file_data_3, date, time, users.username FROM files INNER JOIN users ON files.user_id = users.id WHERE files.id = ?");
    $stmt->bind_param("i", $fileId);

    if (!$stmt->execute()) {
        die("Query failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Aucun fichier trouvé avec l'identifiant spécifié.");
    }
} else {
    die("Identifiant de fichier non spécifié.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #222;


        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .email {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .email h2 {
            margin-top: 0;
        }
        .email p {
            margin-bottom: 10px;
            word-wrap: break-word; 

        }
        .email a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .email a:hover {
            text-decoration: underline;
        }


        .button-92 {
            --c: #fff;
            /* couleur du texte */
            background: linear-gradient(90deg, #0000 33%, #fff5, #0000 67%) var(--_p,100%)/300% no-repeat,
            #000000;
            /* couleur de fond */
            color: #0000;
            border: none;
            transform: perspective(500px) rotateY(calc(20deg*var(--_i,-1)));
            text-shadow: calc(var(--_i,-1)* 0.08em) -.01em 0 var(--c),
            calc(var(--_i,-1)*-0.08em) .01em 2px #0004;
            outline-offset: .1em;
            transition: 0.3s;
            font-weight: bold;
            font-size: 2rem;
            margin: 20px;
            cursor: pointer;
            padding: .1em .3em;
            position: fixed; /* Pour positionner le bouton de façon fixe */
            top: 20px; /* Distance du haut */
            left: 20px; /* Distance de la gauche */
            z-index: 999; /* Pour placer le bouton au-dessus du contenu */
        }

        .button-92:hover,
        .button-92:focus-visible {
            --_p: 0%;
            --_i: 1;
        }

        .button-92:active {
            text-shadow: none;
            color: var(--c);
            box-shadow: inset 0 0 9e9q #0005;
            transition: 0s;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Mail </h1>
    <div class="email">
        <h2><?php echo htmlspecialchars($row["file_name"]); ?></h2>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($row["message"]); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($row["date"]); ?></p>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($row["time"]); ?></p>
        <p><strong>Expéditeur:</strong> <?php echo htmlspecialchars($row["username"]); ?></p>
        <?php if (!empty($row["file_data"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data">Télécharger File Data</a>
        <?php endif; ?>
        <?php if (!empty($row["file_data_2"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data_2">Télécharger File Data 2</a>
        <?php endif; ?>
        <?php if (!empty($row["file_data_3"])) : ?>
            <a href="downloademail.php?file_id=<?php echo htmlspecialchars($fileId); ?>&file_type=file_data_3">Télécharger File Data 3</a>
        <?php endif; ?>
    </div>
</div>
<button class="button-92" role="button" onclick="window.history.back()">Retour</button>


</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
