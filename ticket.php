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
            background-color: #fff;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        .table thead th {
            vertical-align: middle;
            background-color: #343a40;
            color: #fff;
            border-color: #454d55;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .btn {
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
        }

        .btn-success {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            color: #fff;
            background-color: #218838;
            border-color: #1e7e34;
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
    <h2>Liste des tickets sans intervention :</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Date d'ouverture</th>
                <th>Date de clôture</th>
                <th>Statut</th>
                <th>Utilisateur</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "root";
            $password = "Soufiane@2003";
            $dbname = "intranet";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Requête SQL pour récupérer les tickets sans intervention
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
                            users u ON t.userID = u.id
                        LEFT JOIN 
                            intervention i ON t.TicketID = i.TicketID
                        WHERE 
                            i.TicketID IS NULL";

                $stmt = $conn->query($sql);

                // Affichage des résultats dans la table
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['TicketID'] . "</td>";
                    echo "<td>" . $row['Description'] . "</td>";
                    echo "<td>" . $row['Categorie'] . "</td>";
                    echo "<td>" . $row['DateOuverture'] . "</td>";
                    echo "<td>" . $row['DateCloture'] . "</td>";
                    echo "<td>" . $row['Statut'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td><button class='btn btn-success' onclick='accepterTicket(" . $row['TicketID'] . ")'>Accepter</button></td>";
                    echo "</tr>";
                }
            } catch(PDOException $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function accepterTicket(ticketID) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "accepter_ticket.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
                setTimeout(function() {
                    window.location.href = "ticket.php"; 
                }, 100); 
            }
        };
        xhr.send("ticketID=" + ticketID);
    }
</script>
<script>
    $(function(){
        var str = '#len'; //increment by 1 up to 1-nelemnts
        $(document).ready(function(){
            var i, stop;
            i = 1;
            stop = 4; //num elements
            setInterval(function(){
                if (i > stop){
                    return;
                }
                $('#len'+(i++)).toggleClass('bounce');
            }, 500)
        });
    });
</script>

</body>
</html>
