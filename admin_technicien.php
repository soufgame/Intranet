<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>soufiane</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <!-- Inclure une bibliothèque d'icônes, par exemple Font Awesome -->
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
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <!-- Remplace l'image par le texte admin1 -->
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
                    <h1>Technicien-list</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
            <section class="content">
                <?php
                // Inclure le fichier de connexion à la base de données
                include 'connexiondb.php';

                // Requête SQL pour sélectionner tous les techniciens
                $sql = "SELECT * FROM technicien";
                $result = $conn->query($sql);

                // Vérifier s'il y a des résultats
                if ($result->num_rows > 0) {
                    echo '<table>';
                    echo '<thead><tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Username</th><th>CIN</th><th>Numéro de Téléphone</th><th>Service</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    // Afficher les données de chaque ligne
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . $row['Nom'] . '</td>';
                        echo '<td>' . $row['Prenom'] . '</td>';
                        echo '<td>' . $row['UserName'] . '</td>';
                        echo '<td>' . $row['cin'] . '</td>';
                        echo '<td>' . $row['NumTel'] . '</td>';
                        echo '<td>' . $row['service'] . '</td>';
                        echo '<td class="action-buttons">';
                        echo '<a href="modifier_technicien.php?id=' . $row['id'] . '"><i class="fas fa-edit"></i></a>';
                        echo '<a href="supprimer_technicien.php?id=' . $row['id'] . '"><i class="fas fa-trash-alt"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "Aucun résultat trouvé";
                }

                // Fermer la connexion à la base de données
                $conn->close();
                ?>
            </section>
        </main>
    </div>
</body>
</html>
