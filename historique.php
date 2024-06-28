<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Récupérer le nom et le prénom de l'utilisateur
$nom = htmlspecialchars($_SESSION['nom']);
$prenom = htmlspecialchars($_SESSION['prenom']);
$userID = $_SESSION['id']; // Récupérer l'ID de l'utilisateur connecté

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

// Créer la connexion
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Afficher l'erreur de connexion
}

// Préparer la requête SQL pour sélectionner les fichiers de l'utilisateur connecté
$stmt = $conn->prepare("
      SELECT f.id, f.file_name, f.message, f.file_data, f.file_data_2, f.file_data_3, f.username, f.date, f.time
    FROM files f
    WHERE f.user_id = ?
    ORDER BY f.date DESC, f.time DESC
");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error); // Afficher l'erreur de préparation de la requête
}

$stmt->bind_param("i", $userID); // Utilisez "i" pour binder un entier

if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error); // Afficher l'erreur d'exécution de la requête
}

$result = $stmt->get_result();
?>

<table border='1'>
    <tr><th>Titre</th><th>Date</th><th>Time</th><th>Envoyé à</th></tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-id='" . htmlspecialchars($row["id"]) . "'>";
        echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<?php
$stmt->close();
$conn->close();
?>
