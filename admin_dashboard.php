<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Inclure le fichier de connexion à la base de données
include 'connexiondb.php';

// Requêtes pour compter les enregistrements
$employeCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$technicienCount = $conn->query("SELECT COUNT(*) AS count FROM technicien")->fetch_assoc()['count'];
$ticketCount = $conn->query("SELECT COUNT(*) AS count FROM intervention")->fetch_assoc()['count'];
$mailCount = $conn->query("SELECT COUNT(*) AS count FROM files")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .container-results {
    display: flex;
    justify-content: space-around;
    margin: 20px 0;
}

.result-box {
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
    flex: 1;
    margin: 0 10px;
    background-color: #4b4b4b; /* Couleur marron */
    color: white; /* Optionnel : change la couleur du texte pour un meilleur contraste */
}

.result-box h3 {
    margin: 0 0 10px;
}

.result-box p {
    font-size: 24px;
    font-weight: bold;
}

</style>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <p class="profile-text">admin</p>
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
                    <h1>Dashboard</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
            <section class="content">
                <div class="container-results">
                    <div class="result-box">
                        <h3>Employés</h3>
                        <p><?php echo $employeCount; ?></p>
                    </div>
                    <div class="result-box">
                        <h3>Techniciens</h3>
                        <p><?php echo $technicienCount; ?></p>
                    </div>
                    <div class="result-box">
                        <h3>Tickets</h3>
                        <p><?php echo $ticketCount; ?></p>
                    </div>
                    <div class="result-box">
                        <h3>Mails</h3>
                        <p><?php echo $mailCount; ?></p>
                    </div>
                </div>
                <!-- Table will be placed here -->
            </section>
        </main>
    </div>
</body>
</html>
