<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'connexiondb.php';

// Filter username
$filter_username = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filter_username = $_POST['filter_username'];
}

// Fetch data from the 'users' table with filtering
$sql = "SELECT * FROM users WHERE username LIKE '%$filter_username%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Employee Management</title>
    <link rel="stylesheet" href="AdminStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Styles de la table et du filtre */
        .table-container {
            margin-top: 20px;
            overflow-x: auto; /* Ajoute un défilement horizontal */
            max-width: 100%; /* Empêche le conteneur de dépasser l'écran */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Largeurs maximales pour chaque colonne */
        th:nth-child(1), td:nth-child(1) { width: 50px; } /* ID */
        th:nth-child(2), td:nth-child(2) { width: 100px; } /* Username */
        th:nth-child(3), td:nth-child(3) { width: 100px; } /* Password */
        th:nth-child(4), td:nth-child(4) { width: 100px; } /* Department */
        th:nth-child(5), td:nth-child(5) { width: 100px; } /* Division */
        th:nth-child(6), td:nth-child(6) { width: 100px; } /* Service */
        th:nth-child(7), td:nth-child(7) { width: 100px; } /* CIN */
        th:nth-child(8), td:nth-child(8) { width: 100px; } /* Nom */
        th:nth-child(9), td:nth-child(9) { width: 100px; } /* Prenom */
        th:nth-child(10), td:nth-child(10) { width: 100px; } /* Telephone */
        th:nth-child(11), td:nth-child(11), th:nth-child(12), td:nth-child(12) { width: 50px; } /* Edit/Delete */

        input[type="text"] {
            padding: 8px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px; /* Ajustez la largeur selon vos besoins */
        }

        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
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

        .clear-filter {
            cursor: pointer;
            color: #FF0000; /* Couleur de la croix */
            margin-left: 10px;
            font-size: 18px;
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
                
                <!-- Form de filtrage -->
                <form method="POST" action="">
                    <input type="text" name="filter_username" placeholder="Filter by Username" value="<?php echo htmlspecialchars($filter_username); ?>">
                    <button type="submit">Filter</button>
                    <span class="clear-filter" onclick="clearFilter()">&times;</span> <!-- Croix pour enlever le filtrage -->
                </form>
                
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

    <script>
        function clearFilter() {
            document.querySelector('input[name="filter_username"]').value = ''; // Vide le champ de filtrage
            document.querySelector('form').submit(); // Soumet le formulaire
        }
    </script>
</body>
</html>
