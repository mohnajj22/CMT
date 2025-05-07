<?php
session_start();
require_once '../includes/Database.php';

require_once '../includes/Project.php';
require_once '../includes/navbar.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$professor_id = $_SESSION['user_id'];
$projectObj = new Project((new Database())->connect());
$projects = $projectObj->getProjectsByProfessor($professor_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects - CMT</title>
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>
    <div class="container">
        <h2>My Projects</h2>

        <?php if (!empty($projects)): ?>
            <ul class="project-list">
                <?php foreach ($projects as $project): ?>
                    <li class="project-item">
                        <a href="project_details.php?id=<?= $project['id'] ?>" class="project-link">
                            📁 <?= htmlspecialchars($project['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>You are not supervising any projects yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
