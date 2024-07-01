<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : 'Nom non défini';
$prenom = isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : 'Prénom non défini';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateur</title>
    <link rel="stylesheet" href="tech.css">
    <style>
        .user-info {
            background-color: #f0f0f0;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .user-info label {
            font-weight: bold;
        }

        .user-info label, .user-info span {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <aside>
        <!-- Ajoutez votre contenu pour la barre latérale (aside) si nécessaire -->
    </aside>

    <main>
        <div class="user-info">
            <?php
            // Récupérer l'ID de l'utilisateur
            $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : '';

            // Connexion à la base de données
            $servername = "localhost";
            $dbname = "intranet";
            $dbusername = "root";
            $dbpassword = "Soufiane@2003";

            // Créer une connexion
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
            $stmt = $conn->prepare("SELECT username, nom, prenom, department, division, service, cin, telephone FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Vérifier s'il y a des résultats
            if ($result->num_rows > 0) {
                // Afficher les informations de l'utilisateur
                while ($row = $result->fetch_assoc()) {
                    echo "<label>Username: " . htmlspecialchars($row["username"]) . "</label><br>";
                    echo "<label>Nom: " . htmlspecialchars($row["nom"]) . "</label><br>";
                    echo "<label>Prénom: " . htmlspecialchars($row["prenom"]) . "</label><br>";
                    echo "<label>Département: " . htmlspecialchars($row["department"]) . "</label><br>";
                    echo "<label>Division: " . htmlspecialchars($row["division"]) . "</label><br>";
                    echo "<label>Service: " . htmlspecialchars($row["service"]) . "</label><br>";
                    echo "<label>CIN: " . htmlspecialchars($row["cin"]) . "</label><br>";
                    echo "<label>Téléphone: " . htmlspecialchars($row["telephone"]) . "</label><br>";
                }
            } else {
                echo "Aucun résultat trouvé.";
            }

            // Fermer la connexion et la requête
            $stmt->close();
            $conn->close();
            ?>
        </div>

        <div class="doctor-label"><?php echo 'Nom: ' . $prenom . ' ' . $nom; ?></div>
    </main>
</div>

</body>
</html>
