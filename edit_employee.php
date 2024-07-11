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
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        /* Form container styles */
        form {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Form label styles */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Form input styles */
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Submit button styles */
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Back button styles */
        .back-button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: left;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Edit Employee</h2>
    <a href="javascript:history.back()" class="back-button">Back</a>
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
