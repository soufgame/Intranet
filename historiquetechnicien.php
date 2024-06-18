<?php
session_start();
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$user_id = $_SESSION['id'] 
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

</body>
</html>