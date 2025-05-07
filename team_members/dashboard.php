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
    <title>Team Dashboard - CMT</title>
    <link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<div class="container">
    <h2>Team Dashboard</h2>
    <!-- Content for Team Dashboard page goes here -->
</div>
</body>
</html>
