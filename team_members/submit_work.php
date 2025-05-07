<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$base = '../';
include '../includes/navbar_member.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Work - CMT</title>
    <link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<div class="container">
    <h2>Submit Work</h2>
    <!-- Content for Submit Work page goes here -->
</div>
</body>
</html>
