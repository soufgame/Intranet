<?php
// Inclure le fichier de connexion
include 'connexiondb.php';

// Requête pour récupérer les données de la table intervention
$sql = "SELECT * FROM intervention";
$result = $conn->query($sql);

// Vérifiez si des résultats ont été trouvés
$tableHTML = ""; // Variable pour stocker le tableau HTML
if ($result->num_rows > 0) {
    // Créer le tableau
    $tableHTML .= "<table border='1'>
            <tr>
                <th>Intervention ID</th>
                <th>User ID</th>
                <th>Technicien ID</th>
                <th>Description</th>
                <th>Categorie</th>
                <th>Date Ouverture</th>
                <th>Date Cloture</th>
                <th>Statut</th>
            </tr>";

    // Parcourir les résultats et les afficher
    while($row = $result->fetch_assoc()) {
        $tableHTML .= "<tr>
                <td>{$row['interventionID']}</td>
                <td>{$row['userID']}</td>
                <td>{$row['technicienID']}</td>
                <td>{$row['Description']}</td>
                <td>{$row['Categorie']}</td>
                <td>{$row['DateOuverture']}</td>
                <td>{$row['DateCloture']}</td>
                <td>{$row['Statut']}</td>
              </tr>";
    }
    $tableHTML .= "</table>";
} else {
    $tableHTML = "Aucune intervention trouvée.";
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soufiane</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <p class="profile-text">admin1</p>
                <p>Hello, Admin</p>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="admin_employe.php"><i class="fas fa-user-tie"></i> Employe</a></li>
                    <li><a href="admin_technicien.php"><i class="fas fa-tools"></i> Technicien</a></li>
                    <li><a href="admin_mail.php"><i class="fas fa-envelope"></i> Mail</a></li>
                    <li><a href="admin_ticket.php"><i class="fas fa-ticket-alt"></i> Ticket</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="top-header">
                <div class="header-content">
                    <h1>Ticket</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
            <section class="content">
                <!-- Afficher le tableau ici -->
                <?php echo $tableHTML; ?>
            </section>
        </main>
    </div>
</body>
</html>
