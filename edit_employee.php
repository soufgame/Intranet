<?php
include 'connexiondb.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
</head>
<body>
    <h2>Edit Employee</h2>
    <form action="update_employee.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo $row['password']; ?>" required>
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" value="<?php echo $row['department']; ?>" required>
        <label for="division">Division:</label>
        <input type="text" id="division" name="division" value="<?php echo $row['division']; ?>" required>
        <label for="service">Service:</label>
        <input type="text" id="service" name="service" value="<?php echo $row['service']; ?>" required>
        <label for="cin">CIN:</label>
        <input type="text" id="cin" name="cin" value="<?php echo $row['cin']; ?>" required>
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo $row['nom']; ?>" required>
        <label for="prenom">Prenom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo $row['prenom']; ?>" required>
        <label for="telephone">Telephone:</label>
        <input type="text" id="telephone" name="telephone" value="<?php echo $row['telephone']; ?>" required>
        <button type="submit">Update Employee</button>
    </form>
</body>
</html>
