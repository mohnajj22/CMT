<?php
session_start();

// Database connection
include 'includes/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Check if user found and password is correct
    if ($user && $password == $user['password']) {  // You should hash passwords in production
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");  // Redirect to homepage or dashboard
    } else {
        echo "Invalid login credentials!";
    }
}
