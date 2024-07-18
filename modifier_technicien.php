<?php
// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Initialiser les variables pour stocker les valeurs du formulaire
$id = $nom = $prenom = $username = $cin = $numtel = $service = $motdepasse = '';
$usernameFilter = ''; // Initialisation de la variable pour éviter les erreurs

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
        $motdepasse = isset($row['MotdePasse']) ? $row['MotdePasse'] : ''; // Vérifiez que la colonne existe
    } else {
        echo "Aucun technicien trouvé avec cet ID";
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
        $motdepasse = $_POST['motdepasse']; // Récupérer le mot de passe du formulaire

        $nom = $conn->real_escape_string($nom);
        $prenom = $conn->real_escape_string($prenom);
        $username = $conn->real_escape_string($username);
        $cin = $conn->real_escape_string($cin);
        $numtel = $conn->real_escape_string($numtel);
        $service = $conn->real_escape_string($service);
        $motdepasse = $conn->real_escape_string($motdepasse); // Échapper le mot de passe

        // Mise à jour de la base de données
        $sql_update = "UPDATE technicien SET Nom = '$nom', Prenom = '$prenom', UserName = '$username', cin = '$cin', NumTel = '$numtel', service = '$service', MotdePasse = '$motdepasse' WHERE id = '$id'";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: admin_technicien.php"); // Redirection après la mise à jour
            exit();
        } else {
            echo "Erreur lors de la mise à jour du technicien : " . $conn->error;
        }
    }
} else {
    // Vérifier si le formulaire de filtrage a été soumis
    $usernameFilter = isset($_GET['username']) ? $_GET['username'] : '';
}

// Récupérer la liste des techniciens
$sql = "SELECT * FROM technicien WHERE UserName LIKE ?";
$stmt = $conn->prepare($sql);
$likeFilter = '%' . $usernameFilter . '%';
$stmt->bind_param('s', $likeFilter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techniciens</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Styles CSS pour la table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .action-buttons a {
            margin-right: 5px;
            text-decoration: none;
            color: #007bff;
        }
        .action-buttons a:hover {
            text-decoration: underline;
        }
        .filter {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .filter input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        .filter input[type="submit"] {
            background-color: #4b4b4b;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .filter input[type="submit"]:hover {
            background-color: #4b4b4b;
        }
        .clear-filter {
            margin-left: 10px;
            color: #4b4b4b; /* Rouge pour la croix */
            text-decoration: none;
            font-size: 18px; /* Taille de la croix */
        }
        .clear-filter:hover {
            color: #4b4b4b; /* Couleur plus foncée au survol */
        }
        .add-button {
            margin-top: 20px;
            text-align: left;
        }
        .add-button .btn {
            background-color: #4b4b4b;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .add-button .btn:hover {
            background-color: #4b4b4b;
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
        <?php if ($id): ?>
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
                        <label for="numtel">Numéro de téléphone :</label>
                        <input type="text" id="numtel" name="numtel" value="<?php echo htmlspecialchars($numtel); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="service">Service :</label>
                        <input type="text" id="service" name="service" value="<?php echo htmlspecialchars($service); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="motdepasse">Mot de passe :</label>
                        <input type="text" id="motdepasse" name="motdepasse" value="<?php echo htmlspecialchars($motdepasse); ?>" required>
                    </div>
                    <button type="submit">Modifier</button>
                </form>
            </div>
        <?php else: ?>
            <div class="filter">
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="username" placeholder="Filtrer par nom d'utilisateur" value="<?php echo htmlspecialchars($usernameFilter); ?>">
                    <input type="submit" value="Filtrer">
                    <?php if ($usernameFilter): ?>
                        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="clear-filter"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="add-button">
                <a href="add_technicien.php" class="btn">Ajouter un Technicien</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Nom d'utilisateur</th>
                        <th>CIN</th>
                        <th>Numéro de téléphone</th>
                        <th>Service</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nom']); ?></td>
                            <td><?php echo htmlspecialchars($row['Prenom']); ?></td>
                            <td><?php echo htmlspecialchars($row['UserName']); ?></td>
                            <td><?php echo htmlspecialchars($row['cin']); ?></td>
                            <td><?php echo htmlspecialchars($row['NumTel']); ?></td>
                            <td><?php echo htmlspecialchars($row['service']); ?></td>
                            <td class="action-buttons">
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=" . $row['id']); ?>">Modifier</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
