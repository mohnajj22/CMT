<?php
session_start();
require_once 'includes/Database.php';

$pdo = (new Database())->connect();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'] ?? '';

    // Validate role value
    if (!in_array($role, ['professor', 'member'])) {
        $message = "Invalid role selected.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $message = "Email is already registered.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
            $success = $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $password,
                ':role'     => $role
            ]);

            $message = $success
                ? "Account created successfully! <a href='login.php'>Login here</a>."
                : "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - CMT</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
        <select name="role" class="form-control" required>
              <option value="professor">Professor</option>
             <option value="member">Team Member</option>
</select>

        </div>

        <button type="submit" class="btn-primary">Register</button>
    </form>
</div>
</body>
</html>
