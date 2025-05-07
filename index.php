<?php
session_start();
$base = '';
$role = $_SESSION['role'] ?? null;

// Debug (remove after testing)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($role === 'professor') {
    include $base . 'includes/navbar.php';
} elseif ($role === 'member') {
    include $base . 'includes/navbar_member.php';
} else {
    // If guest and file exists
    if (file_exists($base . 'includes/navbar_guest.php')) {
        include $base . 'includes/navbar_guest.php';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CMT</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Collaboration & Management Tool (CMT)</h1>
        <p>This platform allows professors and team members to manage projects, assign tasks, upload files, and track progress.</p>
        
        <div class="features">
            <h2>Key Features</h2>
            <ul>
                <li>Create and manage academic projects</li>
                <li>Assign and track tasks</li>
                <li>Upload and download project files</li>
                <li>Generate progress reports</li>
            </ul>
        </div>
    </div>
</body>
</html>
