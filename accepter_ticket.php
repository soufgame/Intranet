<?php
session_start();
$technicienID = $_SESSION['id']; // Assurez-vous que 'id' est correctement défini dans votre session

if (isset($_POST['ticketID'])) {
    $ticketID = $_POST['ticketID'];

    $servername = "localhost";
    $username = "root";
    $password = "Soufiane@2003";
    $dbname = "intranet";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupération des données du ticket à insérer dans intervention
    $sql = "SELECT * FROM tickets WHERE TicketID = $ticketID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userID = $row['userID'];
        $description = $row['Description'];
        $categorie = $row['Categorie'];
        $dateOuverture = $row['DateOuverture'];
        $dateCloture = $row['DateCloture']; // Assurez-vous que c'est au format 'YYYY-MM-DD'
        $statut = $row['Statut'];

        // Vérifier et formater la date de clôture
        if (!empty($dateCloture)) {
            $dateCloture = date('Y-m-d', strtotime($dateCloture)); // Formater la date au format 'YYYY-MM-DD'
        } else {
            $dateCloture = null; // Ou vous pouvez décider de mettre NULL si la date de clôture n'est pas spécifiée
        }

        // Insertion dans la table intervention
        $insertSql = "INSERT INTO intervention (ticketID, userID, technicienID, Description, Categorie, DateOuverture, DateCloture, Statut)
                      VALUES ($ticketID, $userID, $technicienID, '$description', '$categorie', '$dateOuverture', ";

        if ($dateCloture !== null) {
            $insertSql .= "'$dateCloture', ";
        } else {
            $insertSql .= "NULL, ";
        }

        $insertSql .= "'$statut')";

        if ($conn->query($insertSql) === TRUE) {
            // Affichage du message de confirmation
            echo "Ticket ID $ticketID accepté.";
        } else {
            echo "Erreur : " . $conn->error;
        }
    } else {
        echo "Ticket non trouvé.";
    }

    $conn->close();
} else {
    echo "Paramètre 'ticketID' non trouvé.";
}
?>
