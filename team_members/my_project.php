<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/navbar_member.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$db = Database::getInstance()->getConnection();

// Fetch project ID for the logged-in team member
$stmt = $db->prepare("SELECT project_id FROM project_members WHERE user_id = ?");
$stmt->execute([$user_id]);
$project_id = $stmt->fetchColumn();

$project = null;
$members = [];

if ($project_id) {
    // Fetch project details
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch enrolled members
    $stmt = $db->prepare("SELECT u.name, u.email FROM users u JOIN project_members pm ON u.id = pm.user_id WHERE pm.project_id = ?");
    $stmt->execute([$project_id]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Project</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h2>📁 My Project</h2>

        <?php if ($project): ?>
            <div class="project-card">
                <h3><?= htmlspecialchars($project['title']) ?></h3>
                <p><strong>Description:</strong> <?= htmlspecialchars($project['description']) ?></p>
                <p><strong>Deadline:</strong> <?= htmlspecialchars($project['deadline']) ?></p>

                <h4>👥 Enrolled Members:</h4>
                <ul>
                    <?php foreach ($members as $member): ?>
                        <li><?= htmlspecialchars($member['name']) ?> - <?= htmlspecialchars($member['email']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <p>You are not enrolled in any project yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
