<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'connexiondb.php';

// Fetch data from the 'users' table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Employee Management</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <!-- Include a library for icons, for example Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
        .action-icons {
            text-align: center;
        }
        .action-icons a {
            margin: 0 5px;
            color: #000;
        }
        .action-icons a:hover {
            color: #007BFF;
        }
        .table-container {
            overflow-x: auto;
        }
        button {
        background-color: #007BFF;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 10px;
    }

    button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile">
                <p class="profile-text">admin1</p>
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
                    <h1>Employee List</h1>
                    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </header>
            <section class="content">
                <button onclick="window.location.href='add_employee.php'">Add Employee</button>
                <h2>Employee List</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Department</th>
                                <th>Division</th>
                                <th>Service</th>
                                <th>CIN</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Telephone</th>
                                <th class="action-icons">Edit</th>
                                <th class="action-icons">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['username']}</td>
                                            <td>{$row['password']}</td>
                                            <td>{$row['department']}</td>
                                            <td>{$row['division']}</td>
                                            <td>{$row['service']}</td>
                                            <td>{$row['cin']}</td>
                                            <td>{$row['nom']}</td>
                                            <td>{$row['prenom']}</td>
                                            <td>{$row['telephone']}</td>
                                            <td class='action-icons'><a href='edit_employee.php?id={$row['id']}'><i class='fas fa-edit'></i></a></td>
                                            <td class='action-icons'><a href='delete_employee.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this employee?\");'><i class='fas fa-trash-alt'></i></a></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='12'>No results found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
