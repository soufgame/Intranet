<?php
session_start(); // THIS MUST BE THE VERY FIRST LINE OF CODE

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

    // Try to authenticate as a regular user first
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful authentication as a regular user
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['nom'] = $row['nom'];
        $_SESSION['prenom'] = $row['prenom'];
        $_SESSION['id'] = $row['id'];
        header("Location: dashboardemploye.php");
        exit();
    } else {
        // If not a regular user, try to authenticate as a technician
        $stmt_technicien = $conn->prepare("SELECT * FROM technicien WHERE UserName = ? AND MotDePasse = ?");
        $stmt_technicien->bind_param("ss", $user, $pass);
        $stmt_technicien->execute();
        $result_technicien = $stmt_technicien->get_result();

        if ($result_technicien->num_rows > 0) {
            // Successful authentication as a technician
            $row_technicien = $result_technicien->fetch_assoc();
            $_SESSION['username'] = $row_technicien['UserName'];
            $_SESSION['nom'] = $row_technicien['Nom'];
            $_SESSION['prenom'] = $row_technicien['Prenom'];
            $_SESSION['id'] = $row_technicien['id'];
            header("Location: dashboardtechnici.php");
            exit();
        } else {
            // If not a regular user or technician, try to authenticate as admin
            if ($user === 'admin' && $pass === 'admin') {
                // Successful authentication as admin
                $_SESSION['username'] = 'admin';
                $_SESSION['nom'] = 'Admin';
                $_SESSION['prenom'] = 'Admin';
                $_SESSION['id'] = 1; // Arbitrary ID for admin
                header("Location: admin_dashboard.php");
                exit();
            } else {
                // Authentication failed for all roles
                $error_message = "Nom d'utilisateur ou mot de passe incorrect";
            }
        }
        // Close technician statement if it was initialized
        if (isset($stmt_technicien) && $stmt_technicien) {
            $stmt_technicien->close();
        }
    }
    // Close user statement
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Intranet</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
      min-height: 100vh;
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #020617 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Animation de fond avec particules */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: 
        radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(34, 197, 94, 0.1) 0%, transparent 50%);
      animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      33% { transform: translateY(-20px) rotate(1deg); }
      66% { transform: translateY(10px) rotate(-1deg); }
    }

    .container {
      display: flex;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }

    .brand-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 60px;
      color: white;
    }

    .logo {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 30px;
      box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
      animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3); }
      50% { transform: scale(1.05); box-shadow: 0 25px 50px rgba(59, 130, 246, 0.4); }
    }

    .logo::before {
      content: '🌐';
      font-size: 36px;
    }

    .brand-title {
      font-size: 3.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 20px;
      text-align: center;
      line-height: 1.1;
    }

    .brand-subtitle {
      font-size: 1.5rem;
      color: #94a3b8;
      text-align: center;
      font-weight: 300;
      letter-spacing: 0.5px;
    }

    .form-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .card {
      width: 100%;
      max-width: 450px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 50px 40px;
      border-radius: 24px;
      box-shadow: 
        0 32px 64px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
    }

    h2 {
      text-align: center;
      color: #1e293b;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 40px;
      position: relative;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, #3b82f6, #8b5cf6);
      border-radius: 2px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .input-group {
      position: relative;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 16px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      outline: none;
      color: #1e293b;
      background: #ffffff;
    }

    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
      transform: translateY(-1px);
    }

    input[type="text"]::placeholder, input[type="password"]::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    .checkbox-container {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 8px 0;
    }

    .checkbox-container input[type="checkbox"] {
      width: 20px;
      height: 20px;
      accent-color: #3b82f6;
      border-radius: 4px;
    }

    .checkbox-container label {
      color: #64748b;
      font-size: 14px;
      cursor: pointer;
      user-select: none;
    }

    button {
      background: linear-gradient(135deg, #3b82f6, #1d4ed8);
      color: white;
      padding: 18px 24px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    button:hover {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(37, 99, 235, 0.3);
    }

    button:hover::before {
      left: 100%;
    }

    button:active {
      transform: translateY(0);
    }

    .error-message {
      color: #ef4444;
      text-align: center;
      font-size: 14px;
      font-weight: 500;
      padding: 12px;
      background: rgba(239, 68, 68, 0.1);
      border-radius: 8px;
      border-left: 4px solid #ef4444;
    }

    .home-button {
      position: absolute;
      top: 30px;
      left: 30px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      color: white;
      padding: 12px 24px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 50px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      z-index: 10;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .home-button::before {
      content: '←';
      font-size: 18px;
    }

    .home-button:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(-4px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
        padding: 20px;
      }

      .brand-section {
        padding: 40px 20px 20px;
        text-align: center;
      }

      .brand-title {
        font-size: 2.5rem;
      }

      .brand-subtitle {
        font-size: 1.2rem;
      }

      .form-container {
        padding: 20px;
      }

      .card {
        padding: 40px 30px;
      }

      .home-button {
        top: 20px;
        left: 20px;
        padding: 10px 20px;
      }
    }

    @media (max-width: 480px) {
      .brand-title {
        font-size: 2rem;
      }

      .card {
        padding: 30px 20px;
      }

      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <a href="index.html" class="home-button">Accueil</a>

  <div class="container">
    <div class="brand-section">
      <div class="logo"></div>
      <h1 class="brand-title">intranet</h1>
      <p class="brand-subtitle">Restez toujours connecté</p>
    </div>

    <div class="form-container">
      <div class="card">
        <h2>Connexion</h2>
        <?php if (!empty($error_message)): ?>
          <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
          <div class="input-group">
            <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
          </div>
          
          <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
          </div>
          
          <div class="checkbox-container">
            <input type="checkbox" id="showPassword" onclick="togglePassword()">
            <label for="showPassword">Afficher le mot de passe</label>
          </div>
          
          <button type="submit">Se connecter</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      var passwordField = document.getElementById('password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
      } else {
        passwordField.type = 'password';
      }
    }

    // Animation d'entrée pour les éléments
    document.addEventListener('DOMContentLoaded', function() {
      const card = document.querySelector('.card');
      const inputs = document.querySelectorAll('input');
      
      // Animation séquentielle des inputs
      inputs.forEach((input, index) => {
        input.style.opacity = '0';
        input.style.transform = 'translateY(20px)';
        setTimeout(() => {
          input.style.transition = 'all 0.5s ease';
          input.style.opacity = '1';
          input.style.transform = 'translateY(0)';
        }, 200 + (index * 100));
      });
    });
  </script>
</body>
</html>