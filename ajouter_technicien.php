<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Définir les variables vides pour récupérer les valeurs du formulaire
$nom = $prenom = $username = $motdepasse = $cin = $numtel = $service = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $motdepasse = $_POST['motdepasse'];
    $cin = $_POST['cin'];
    $numtel = $_POST['numtel'];
    $service = $_POST['service'];

    // Échapper les entrées pour éviter les injections SQL
    $nom = $conn->real_escape_string($nom);
    $prenom = $conn->real_escape_string($prenom);
    $username = $conn->real_escape_string($username);
    $motdepasse_hash = password_hash($motdepasse, PASSWORD_DEFAULT); // Hasher le mot de passe
    $cin = $conn->real_escape_string($cin);
    $numtel = $conn->real_escape_string($numtel);
    $service = $conn->real_escape_string($service);

    // Requête SQL pour insérer le nouveau technicien dans la base de données
    $sql_insert = "INSERT INTO technicien (Nom, Prenom, UserName, MotDePasse, cin, NumTel, service) 
                   VALUES ('$nom', '$prenom', '$username', '$motdepasse_hash', '$cin', '$numtel', '$service')";

    if ($conn->query($sql_insert) === TRUE) {
        // Redirection vers la page admin_technicien.php après l'ajout
        header("Location: admin_technicien.php");
    } else {
        echo "Erreur lors de l'ajout du technicien : " . $conn->error;
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Technicien</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <!-- Inclure une bibliothèque d'icônes, par exemple Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Styles CSS pour la page Ajouter Technicien */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            justify-content: center;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 100%;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Style pour le bouton Retour */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="admin_technicien.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
    <div class="container">
        <div class="form-container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="motdepasse">Mot de passe :</label>
                    <input type="password" id="motdepasse" name="motdepasse" required>
                </div>
                <div class="form-group">
                    <label for="cin">CIN :</label>
                    <input type="text" id="cin" name="cin" required>
                </div>
                <div class="form-group">
                    <label for="numtel">Numéro de Téléphone :</label>
                    <input type="text" id="numtel" name="numtel" required>
                </div>
                <div class="form-group">
                    <label for="service">Service :</label>
                    <input type="text" id="service" name="service" value="informatique" required>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>
</body>
</html>
