<?php
// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Initialiser les variables pour stocker les valeurs du formulaire
$id = $nom = $prenom = $username = $cin = $numtel = $service = '';

// Vérifier si l'ID du technicien à modifier est défini dans l'URL
if (isset($_GET['id'])) {
    // Échapper les entrées pour éviter les injections SQL
    $id = $conn->real_escape_string($_GET['id']);

    // Requête SQL pour récupérer les informations du technicien spécifié
    $sql = "SELECT * FROM technicien WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les données du technicien
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
    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $username = $_POST['username'];
    $cin = $_POST['cin'];
    $numtel = $_POST['numtel'];
    $service = $_POST['service'];

    // Échapper les entrées pour éviter les injections SQL
    $nom = $conn->real_escape_string($nom);
    $prenom = $conn->real_escape_string($prenom);
    $username = $conn->real_escape_string($username);
    $cin = $conn->real_escape_string($cin);
    $numtel = $conn->real_escape_string($numtel);
    $service = $conn->real_escape_string($service);

    // Requête SQL pour mettre à jour les informations du technicien
    $sql_update = "UPDATE technicien SET Nom = '$nom', Prenom = '$prenom', UserName = '$username', cin = '$cin', NumTel = '$numtel', service = '$service' WHERE id = '$id'";

    if ($conn->query($sql_update) === TRUE) {
        // Redirection vers la page admin_technicien.php après mise à jour
        header("Location: admin_technicien.php");
    } else {
        echo "Erreur lors de la mise à jour du technicien : " . $conn->error;
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Technicien</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <!-- Inclure une bibliothèque d'icônes, par exemple Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <main class="main-content">
            <header class="top-header">
                <div class="header-content">
                    <h1>Modifier Technicien</h1>
                    <a href="admin_technicien.php" class="back"><i class="fas fa-arrow-left"></i> Retour</a>
                </div>
                
            </header>
            <style>/* AdminStyle.css */

/* Styles généraux */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-content {
    flex: 1;
    padding: 20px;
}

.top-header {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 {
    margin: 0;
    font-size: 24px;
}

.back {
    color: #fff;
    text-decoration: none;
    display: inline-block;
    padding: 8px 12px;
    border-radius: 4px;
    background-color: rgba(255, 255, 255, 0.2);
}

.back:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.content {
    background-color: #fff;
    padding: 20px;
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    display: block;
}

input[type="text"] {
    width: 100%;
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

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .back {
        margin-top: 10px;
    }
}
</style>
            <section class="content">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id); ?>">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom:</label>
                        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cin">CIN:</label>
                        <input type="text" id="cin" name="cin" value="<?php echo htmlspecialchars($cin); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="numtel">Numéro de Téléphone:</label>
                        <input type="text" id="numtel" name="numtel" value="<?php echo htmlspecialchars($numtel); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="service">Service:</label>
                        <input type="text" id="service" name="service" value="<?php echo htmlspecialchars($service); ?>" required>
                    </div>
                    <button type="submit">Enregistrer</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
