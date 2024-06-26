<?php
session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    
    header("Location: login.php");
    exit();
}
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$technicienID = $_SESSION['id']; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="style/historiquetechnicien.css">
</head>

<body>
    <header>
        <h1>Intranet</h1>
    </header>

    <style>

table {
    width: 80%; 
    border-collapse: collapse; 
    margin-top: 20px; 
    margin-left: 250px; 
    margin-right: auto;
}

table th, table td {
    padding: 12px;
    text-align: left; 
    border-bottom: 1px solid #ddd; 
    max-width: 200px; 
    overflow: hidden; 
    text-overflow: ellipsis; 
    white-space: nowrap; 
    vertical-align: middle; 
}

table th {
    background-color: #f2f2f2; 
    color: #333; 
}

table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tbody tr:hover {
    background-color: #e0e0e0; 
}



    </style>

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

    
    <div class="historique">
        <h2><br></h2>
        <table>
            <thead>
                <tr>
                    <th>Numéro de ticket</th>
                    <th>Utilisateur</th>
                    <th>Description</th>
                    <th>Catégorie</th>
                    <th>Date d'ouverture</th>
                    <th>Date de clôture</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=intranet', 'root', 'Soufiane@2003');
                
                $stmt = $pdo->prepare("SELECT i.ticketID, u.username, i.Description, i.Categorie, i.DateOuverture, i.DateCloture, i.Statut
                                      FROM intervention i
                                      JOIN users u ON i.userID = u.id
                                      WHERE i.technicienID = ?");
                $stmt->execute([$technicienID]);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['ticketID']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['Description']}</td>";
                    echo "<td>{$row['Categorie']}</td>";
                    echo "<td>{$row['DateOuverture']}</td>";
                    echo "<td>{$row['DateCloture']}</td>";
                    echo "<td>{$row['Statut']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
