<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>soufiane</title>
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
    </style>
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
                    <h1>Technicien-list</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
          
            <section class="content">
                <div class="filter">
                    <form method="GET" action="">
                        <input type="text" name="username" placeholder="Filtrer par Username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
                        <input type="submit" value="Filtrer">
                        <?php if (!empty($_GET['username'])): ?>
                            <a href="admin_technicien.php" class="clear-filter" title="Effacer le filtre"><i class="fas fa-times-circle"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="add-button">
                    <a href="ajouter_technicien.php" class="btn"><i class="fas fa-plus-circle"></i> Ajouter Technicien</a>
                </div>
                <?php
                include 'connexiondb.php';

                // Récupérer la valeur du filtre
                $usernameFilter = isset($_GET['username']) ? $_GET['username'] : '';

                // Requête SQL avec filtre
                $sql = "SELECT * FROM technicien WHERE UserName LIKE ?";
                $stmt = $conn->prepare($sql);
                $likeFilter = '%' . $usernameFilter . '%';
                $stmt->bind_param('s', $likeFilter);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<table>';
                    echo '<thead><tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Username</th><th>CIN</th><th>Numéro de Téléphone</th><th>Service</th><th>Mot De Passe</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['id'] . '</td>';
                        echo '<td>' . $row['Nom'] . '</td>';
                        echo '<td>' . $row['Prenom'] . '</td>';
                        echo '<td>' . $row['UserName'] . '</td>';
                        echo '<td>' . $row['cin'] . '</td>';
                        echo '<td>' . $row['NumTel'] . '</td>';
                        echo '<td>' . $row['service'] . '</td>';
                        echo '<td>' . $row['MotDePasse'] . '</td>';
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

                $stmt->close();
                $conn->close();
                ?>
            </section>
        </main>
    </div>
</body> 
</html>
