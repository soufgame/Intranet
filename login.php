<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      font-family: "Comic Sans MS", cursive;
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #333;
    }
    .container {
      display: flex;
      flex-direction: row;
      width: 100%;
    }
    .form-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .card {
      width: 80%;
      max-width: 500px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    input {
      padding: 10px;
      margin-bottom: 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      transition: border-color 0.3s ease-in-out;
      outline: none;
      color: #333;
    }
    input:focus {
      border-color: #ff4500;
    }
    button {
      background-color: #0047ab;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }
    button:hover {
      background-color: #00274c;
    }
    .error-message {
      color: red;
      text-align: center;
      margin-bottom: 12px;
    }
    .home-button {
      position: absolute;
      top: 30px;
      left: 50px;
      background-color: #0047ab;
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }
    .home-button:hover {
      background-color: #00274c;
    }
  </style>
</head>
<body>
  <!-- Bouton pour rediriger vers accueil.html -->
  <a href="accueil.html" class="home-button">Accueil</a>

  <div class="container">
    <div class="form-container">
      <div class="card">
        <h2>Login</h2>
        <?php
          session_start();
          $error_message = '';
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $dbname = "intranet";
            $dbusername = "root";
            $dbpassword = "Soufiane@2003";

            // Create connection
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $user = $_POST['username'];
            $pass = $_POST['password'];

            // Prepare and bind
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $user, $pass);

            // Execute statement
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              // Authentification réussie, récupérer le nom de l'utilisateur
              $row = $result->fetch_assoc();
              $username = $row['username'];
              $nom = $row['nom'];
              $prenom = $row['prenom'];
              $user_id = $row['id'];

              

              // Stocker le nom de l'utilisateur dans la session
              $_SESSION['username'] = $username;
              $_SESSION['nom'] = $nom;
              $_SESSION['prenom'] = $prenom;
              $_SESSION['id'] = $user_id ;




              // Rediriger vers dashboard.php
              header("Location: dashboard.php");
              exit();
            } else {
              $error_message = "Invalid username or password";
            }

            $stmt->close();
            $conn->close();
          }
        ?>
        <form method="post" action="">
          <div class="error-message"><?php echo $error_message; ?></div>
          <input type="text" id="username" name="username" placeholder="Username" required>
          <input type="password" id="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>