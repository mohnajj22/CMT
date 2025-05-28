<?php
session_start();
require_once 'includes/Database.php';

$pdo = Database::getInstance()->getConnection();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && (
      $password === $user['password'] || password_verify($password, $user['password'])
  )) {
  
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; // ✅ critical for navbars & access

        // Redirect by role
        if ($user['role'] === 'professor') {
            header("Location: professors/dashboard.php");
        } elseif ($user['role'] === 'member') {
            header("Location: team_members/dashboard.php");
        } else {
            header("Location: index.php"); // fallback
        }
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - CMT</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-bg {
      background: url('assets/images/cmt-background.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 2rem 3rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 350px;
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #005792;
    }

    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 0.7rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-box button {
      width: 100%;
      padding: 0.8rem;
      background-color: #005792;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
    }

    .login-box button:hover {
      background-color: #003d5b;
    }

    .error-msg {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <div class="login-bg">
    <div class="login-box">
      <h2>Login to CMT</h2>
      <?php if (!empty($message)): ?>
        <div class="error-msg"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <form method="post" action="">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
