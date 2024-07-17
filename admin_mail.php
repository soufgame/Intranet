<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_ids'])) {
    $file_ids = $_POST['file_ids'];
    $ids = implode(',', array_map('intval', $file_ids)); // Sécuriser les ID
    $delete_sql = "DELETE FROM files WHERE id IN ($ids)";

    $conn->query($delete_sql); // Suppression sans message de succès
}

// Initialisation des filtres
$destinataire = isset($_GET['destinataire']) ? $_GET['destinataire'] : '';
$envoyeur = isset($_GET['envoyeur']) ? $_GET['envoyeur'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Construction de la requête SQL
$sql = "SELECT
    f.id,
    f.file_name AS objet,
    f.username AS destinataire,
    u.username AS envoyeur,
    f.date,
    f.time
FROM
    files f
JOIN
    users u ON f.user_id = u.id
WHERE 1=1"; // Condition de base

// Ajout des conditions de filtre
if (!empty($destinataire)) {
    $sql .= " AND f.username LIKE '%" . $conn->real_escape_string($destinataire) . "%'";
}
if (!empty($envoyeur)) {
    $sql .= " AND u.username LIKE '%" . $conn->real_escape_string($envoyeur) . "%'";
}
if (!empty($date)) {
    $sql .= " AND f.date = '" . $conn->real_escape_string($date) . "'";
}

// Tri des résultats
$sql .= " ORDER BY f.date DESC, f.time DESC";

$result = $conn->query($sql);

if ($result === false) {
    die("Erreur lors de la récupération des données : " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        td:nth-child(2) {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        input[type="submit"] {
            background-color: #4b4b4b; /* Couleur verte */
            color: white; /* Texte blanc */
            border: none; /* Pas de bordure */
            padding: 10px 20px; /* Espacement */
            text-align: center; /* Centrer le texte */
            text-decoration: none; /* Pas de soulignement */
            display: inline-block; /* Élément en ligne avec marges */
            font-size: 16px; /* Taille de la police */
            margin: 4px 2px; /* Marges */
            cursor: pointer; /* Curseur main */
            border-radius: 5px; /* Coins arrondis */
            transition: background-color 0.3s; /* Transition de la couleur */
        }
        input[type="submit"]:hover {
            background-color: #4b4b4b; /* Couleur plus foncée au survol */
        }
        .delete-button {
            margin: 20px; /* Espacement */
            float: left; /* Alignement à gauche */
            margin-top: -6%;
            margin-left: 0%;
            
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
                    <h1>Mail</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
            <section class="content">
                <!-- Formulaire de filtre -->
                <div style="text-align: center; margin: 20px 0;">
                    <form method="GET" action="">
                        <input type="text" name="destinataire" placeholder="Destinataire" value="<?php echo htmlspecialchars($destinataire); ?>" />
                        <input type="text" name="envoyeur" placeholder="Envoyeur" value="<?php echo htmlspecialchars($envoyeur); ?>" />
                        <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" />
                        <input type="submit" value="Filtrer" />
                        <a href="admin_mail.php" style="margin-left: 10px;">
                            <i class="fas fa-times-circle" style="color: red; font-size: 20px;" title="Annuler le filtrage"></i>
                        </a>
                    </form>
                </div>
                <form method="POST" action="admin_mail.php">
                    <div class="delete-button">
                        <input type="submit" value="Supprimer sélectionnés" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ces fichiers ?');" />
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" /></th>
                                <th>ID</th>
                                <th>Objet</th>
                                <th>Destinataire</th>
                                <th>Envoyeur</th>
                                <th>Date</th>
                                <th>Heure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><input type="checkbox" name="file_ids[]" value="<?php echo htmlspecialchars($row['id']); ?>" /></td>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['objet']); ?></td>
                                        <td><?php echo htmlspecialchars($row['destinataire']); ?></td>
                                        <td><?php echo htmlspecialchars($row['envoyeur']); ?></td>
                                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune donnée disponible</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
            </section>
        </main>
    </div>
    <script>
        // Script pour sélectionner/désélectionner tous les checkbox
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.querySelectorAll('input[name="file_ids[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
    </script>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>
