<?php
session_start();
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];

$servername = "localhost";
$username = "root";
$password = "Soufiane@2003";
$dbname = "intranet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
            t.TicketID,
            t.Description,
            t.Categorie,
            t.DateOuverture,
            t.DateCloture,
            t.Statut,
            t.userID,
            u.username
        FROM 
            tickets t
        JOIN 
            users u ON t.userID = u.id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="style/ticket.css">
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
    <a href="support.php" id="support">Support</a>
    <a href="profilu.php" id="profil">Profil</a>
</div>

<div class="doctor-label">
    <?php echo 'Technicien : ' . $nom . ' ' . $prenom; ?>
</div>

<div class="ticket-table">
    <h2>Liste des Tickets</h2>
    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Date d'Ouverture</th>
                <th>Date de Clôture</th>
                <th>Statut</th>
                <th>Assigné à</th>
                <th>Action</th> <!-- Nouvelle colonne pour le bouton -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['TicketID'] . "</td>";
                    echo "<td>" . $row['Description'] . "</td>";
                    echo "<td>" . $row['Categorie'] . "</td>";
                    echo "<td>" . $row['DateOuverture'] . "</td>";
                    echo "<td>" . $row['DateCloture'] . "</td>";
                    echo "<td>" . $row['Statut'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td><button class='button-12' onclick=\"accepterTicket(" . $row['TicketID'] . ")\">Accepter</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Aucun ticket trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function accepterTicket(ticketID) {
        // Code pour gérer l'acceptation du ticket (peut être une requête AJAX vers un script PHP)
        // Ici, vous pouvez implémenter le comportement souhaité lorsque le bouton "Accepter" est cliqué.
        alert("Ticket ID " + ticketID + " accepté !");
        // Exemple d'action : peut-être rediriger l'utilisateur, mettre à jour le statut du ticket, etc.
    }
</script>


</body>
</html>

<?php
$conn->close();
?>
