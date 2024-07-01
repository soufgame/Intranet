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
    <link rel="stylesheet" href="tech.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: rgb(0, 0, 0);
            font-weight: 600;
            text-align: center !important;
            color: white;
        }
        .container {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            background-color: #343a40; /* Fond sombre pour le mode sombre */
            color: #fff; /* Texte blanc pour le mode sombre */
        }
        .table th,
        .table td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }
        .table thead th {
            vertical-align: middle;
            background-color: #000000; /* Fond plus sombre pour l'en-tête */
            color: #fff;
            border-color: #454d55;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05); /* Alternance de lignes légèrement plus claires */
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.075); /* Surbrillance au survol */
        }
        .btn {
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
        }
        .btn-success {
            color: #fff;
            background-color: #767676;
            border-color: #1e7e34;
        }
        .btn-success:hover {
            color: #fff;
            background-color: #000000;
            border-color: #28a745;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="dashboardtechnici.php">Dashboard</a></li>
                <li><a href="ticket.php">Ticket</a></li>
                <li><a href="intervention.php">Intervention</a></li>
                <li><a href="historiquetechnicien.php">Historique</a></li>
                <li><a href="profiltech.php">Profils</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="container">
    <h2>Historique des tickets :</h2>
    <table class="table table-striped">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // JavaScript functions can be added here if needed
</script>

</body>
</html>
