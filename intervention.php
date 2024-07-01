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

$newStatus = ""; // Initialisation de $newStatus pour éviter l'avertissement Undefined variable

// Update status if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ticketID']) && isset($_POST['newStatus'])) {
    $ticketID = $_POST['ticketID'];
    $newStatus = $_POST['newStatus'];

    if ($newStatus === "ouvert") {
        // Delete the row from intervention table if status is 'ouvert'
        $deleteSql = "DELETE FROM intervention WHERE ticketID = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("i", $ticketID);
        $deleteStmt->execute();
        $deleteStmt->close();
    } else {
        // Update the status and dateCloture if status is not 'ouvert'
        if ($newStatus === "ferme") {
            $dateCloture = date('Y-m-d H:i:s'); // Current date and time
        } else {
            $dateCloture = null; // Null for other statuses
        }

        $updateSql = "UPDATE intervention SET Statut = ?, DateCloture = NOW() WHERE ticketID = ?";
        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt === false) {
            die('Erreur de préparation de la requête SQL: ' . $conn->error);
        }

        $updateStmt->bind_param("si", $newStatus, $ticketID);
        $updateStmt->execute();

        if ($updateStmt->error) {
            die('Erreur lors de l\'exécution de la requête SQL: ' . $updateStmt->error);
        }

        $updateStmt->close();
    }

    // Update the Ticket table if status is 'ferme'
    if ($newStatus === "ferme") {
        $updateTicketSql = "UPDATE tickets SET Statut = ?, DateCloture = ? WHERE TicketID = ?";
        $updateTicketStmt = $conn->prepare($updateTicketSql);

        if ($updateTicketStmt === false) {
            die('Erreur de préparation de la requête SQL pour Ticket: ' . $conn->error);
        }

        $updateTicketStmt->bind_param("ssi", $newStatus, $dateCloture, $ticketID);
        $updateTicketStmt->execute();

        if ($updateTicketStmt->error) {
            die('Erreur lors de l\'exécution de la requête SQL pour Ticket: ' . $updateTicketStmt->error);
        }

        $updateTicketStmt->close();
    }
}

// Fetch interventions excluding those with status 'ouvert'
$sql = "SELECT i.ticketID, u.username, i.Description, i.Categorie, i.DateOuverture, i.DateCloture, i.Statut
        FROM intervention i
        JOIN tickets t ON i.ticketID = t.TicketID
        JOIN users u ON t.userID = u.ID
        WHERE i.technicienID = ?
        AND i.Statut != 'ouvert'
        AND i.Statut != 'fermé'";
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
    <link rel="stylesheet" href="tech.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
 body {
    background-color: rgb(0, 0, 0);
    font-weight: 600;
    text-align: center !important;
    color: white;
}

.interventions-table {
    margin-top: 20px;
    background-color: #333; /* Fond sombre */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.interventions-table table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.interventions-table th, .interventions-table td {
    padding: 12px 15px;
    text-align: center;
    color: #fff; /* Couleur du texte */
}

.interventions-table th {
    background-color: #555; /* Couleur de fond pour les en-têtes */
    font-weight: bold;
}

.interventions-table td {
    border-bottom: 1px solid #666; /* Couleur de la bordure inférieure */
}

.interventions-table tbody tr:nth-child(even) {
    background-color: #444; /* Couleur de fond pour lignes paires */
}

.interventions-table tbody tr:hover {
    background-color: #666; /* Couleur de fond au survol */
}

.interventions-table select {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #666; /* Couleur de fond pour le sélecteur */
    color: #fff; /* Couleur du texte */
}

.interventions-table select:focus {
    outline: none;
    border-color: #66afe9;
    box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
}

    </style>
</head>
<body>
<div class="container-fluid">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a id="len1" class="hoverable" href="dashboardtechnici.php">Dashboard</a></li>
                <li><a id="len2" class="hoverable" href="ticket.php">Ticket</a></li>
                <li><a id="len3" class="hoverable" href="intervention.php">Intervention</a></li>
                <li><a id="len4" class="hoverable" href="historiquetechnicien.php">Historique</a></li>
                <li><a id="len5" class="hoverable" href="profiltech.php">Profils</a></li>
            </ul>
        </div>
    </nav>
</div>




<div class="interventions-table">
    <div class="title-container">
        <h2>Liste des interventions</h2>
    </div>
    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Utilisateur</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Date d'ouverture</th>
                <th>Date de clôture</th>
                <th>Statut</th>
                <th>Modifier Statut</th>
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
                        <td>
                            <form method="post" class="status-form">
                                <input type="hidden" name="ticketID" value="<?php echo $intervention['ticketID']; ?>">
                                <input type="hidden" name="currentStatus" value="<?php echo $intervention['Statut']; ?>">

                                <select name="newStatus" onchange="this.form.submit()">
                                    <option value="" <?php echo ($intervention['Statut'] == '') ? 'selected' : ''; ?>></option>
                                    <option value="ouvert" <?php echo ($intervention['Statut'] == 'ouvert') ? 'selected' : ''; ?>>ouvert</option>
                                    <option value="en court" <?php echo ($intervention['Statut'] == 'en court') ? 'selected' : ''; ?>>en court</option>
                                    <option value="ferme" <?php echo ($intervention['Statut'] == 'ferme') ? 'selected' : ''; ?>>ferme</option>
                                    <option value="resolu" <?php echo ($intervention['Statut'] == 'resolu') ? 'selected' : ''; ?>>resolu</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Aucune intervention disponible</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
