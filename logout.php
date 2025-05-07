<?php
session_start();  // Start the session to access session variables
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page after logging out
header("Location: login.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logged Out - CMT</title>
  <link rel="stylesheet" href="assets/style.css"> <!-- Linking to the external style.css -->
</head>
<body>
  <div class="logout-container">
    <div class="logout-box">
      <h2>You have been logged out!</h2>
      <p>Thank you for using CMT. Please log in again to continue.</p>
      <a href="login.php">Login Again</a>
    </div>
  </div>
</body>
</html>
