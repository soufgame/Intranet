<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Initialisation des filtres
$destinataire = isset($_GET['destinataire']) ? $_GET['destinataire'] : '';
$envoyeur = isset($_GET['envoyeur']) ? $_GET['envoyeur'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Construction de la requête SQL
$sql = "SELECT
    f.id,
    f.message AS objet,
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
                <table>
                    <thead>
                        <tr>
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
                                <td colspan="6">Aucune donnée disponible</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn->close();
?>
