<?php
// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Initialiser les variables pour stocker les valeurs du formulaire
$id = $nom = $prenom = $username = $cin = $numtel = $service = '';

// Vérifier si l'ID du technicien à modifier est défini dans l'URL
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM technicien WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nom = $row['Nom'];
        $prenom = $row['Prenom'];
        $username = $row['UserName'];
        $cin = $row['cin'];
        $numtel = $row['NumTel'];
        $service = $row['service'];
    } else {
        echo "Aucun technicien trouvé avec cet ID";
        exit();
    }
} else {
    echo "ID du technicien non spécifié";
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $cin = $_POST['cin'];
    $numtel = $_POST['numtel'];
    $service = $_POST['service'];

    $nom = $conn->real_escape_string($nom);
    $prenom = $conn->real_escape_string($prenom);
    $username = $conn->real_escape_string($username);
    $cin = $conn->real_escape_string($cin);
    $numtel = $conn->real_escape_string($numtel);
    $service = $conn->real_escape_string($service);

    $sql_update = "UPDATE technicien SET Nom = '$nom', Prenom = '$prenom', UserName = '$username', cin = '$cin', NumTel = '$numtel', service = '$service' WHERE id = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        header("Location: admin_technicien.php");
    } else {
        echo "Erreur lors de la mise à jour du technicien : " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Technicien</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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

        input[type="text"] {
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
            <h1>Modifier Technicien</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cin">CIN :</label>
                    <input type="text" id="cin" name="cin" value="<?php echo htmlspecialchars($cin); ?>" required>
                </div>
                <div class="form-group">
                    <label for="numtel">Numéro de Téléphone :</label>
                    <input type="text" id="numtel" name="numtel" value="<?php echo htmlspecialchars($numtel); ?>" required>
                </div>
                <div class="form-group">
                    <label for="service">Service :</label>
                    <input type="text" id="service" name="service" value="<?php echo htmlspecialchars($service); ?>" required>
                </div>
                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </div>
</body>
</html>
