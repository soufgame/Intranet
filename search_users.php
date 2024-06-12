<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$dbname = "intranet";
$dbusername = "root";
$dbpassword = "Soufiane@2003";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer le texte de recherche
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');

    // Préparer et exécuter la requête SQL
    $stmt = $pdo->prepare("SELECT username FROM users WHERE username LIKE :search ORDER BY username");
    $stmt->execute(['search' => $search . '%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats au format JSON
    header('Content-Type: application/json');
    echo json_encode($users);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur de connexion : ' . htmlspecialchars($e->getMessage())]);
    exit();
}
?>
