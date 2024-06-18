<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); 
    exit();
}
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$technicienId = $_SESSION['id']; 
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}
// Préparez la requête SQL
$sql = "SELECT i.ticketID, u.username, i.Description, i.Categorie, i.DateOuverture, i.DateCloture, i.Statut
        FROM intervention i
        JOIN tickets t ON i.ticketID = t.TicketID
        JOIN users u ON t.userID = u.ID
        WHERE i.technicienID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $technicienId);
$stmt->execute();
$result = $stmt->get_result();
$interventions = [];
while ($row = $result->fetch_assoc()) {
    $interventions[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intervention</title>
    <link rel="stylesheet" href="style/intervention.css">
    <style>
        table {
            margin: 20px auto;
            width: 90%;
            max-width: 1700px; 
            margin-right: 5%;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .title-container {
            padding-left: 13%;
        }
    </style>
</head>
<body>
<header>
    <h1>Intranet</h1>
</header>
<div class="sidebar">
    <a href="intervention.php" id="rendez-vous">intervention</a>
    <a href="ticket.php" id="patient">Ticket</a>
    <a href="logout.php" id="logoutButton">LOG OUT</a>
    <a href="dashboardtechnici.php" id="Dashboard">Dashboard</a>
    <a href="historiquetechnicien.php" id="support">Historique</a>
    <a href="profiltech.php" id="profil">Profil</a>
</div>

<div class="doctor-label">
    <?php echo 'Technicien : ' . $nom . ' ' . $prenom; ?>
</div>

<div class="interventions-table">
<div class="title-container">
        <h2>Liste des interventions</h2>
    </div>    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Utilisateur</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Date d'ouverture</th>
                <th>Date de clôture</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($interventions) > 0): ?>
                <?php foreach ($interventions as $intervention): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($intervention['ticketID']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['username']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['Description']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['Categorie']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['DateOuverture']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['DateCloture']); ?></td>
                        <td><?php echo htmlspecialchars($intervention['Statut']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">vide</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
