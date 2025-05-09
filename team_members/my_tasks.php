<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/navbar_member.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$db = (new Database())->connect();

$stmt = $db->prepare("SELECT project_id FROM project_members WHERE user_id = ?");
$stmt->execute([$user_id]);
$project_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

$tasks = [];

if ($project_ids) {
    $in  = str_repeat('?,', count($project_ids) - 1) . '?';
    $sql = "SELECT * FROM tasks WHERE project_id IN ($in)";
    $stmt = $db->prepare($sql);
    $stmt->execute($project_ids);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks - CMT</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .task-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .task-card {
            background: #ffffff;
            border-left: 5px solid #007bff;
            padding: 1.2rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .task-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #003d5b;
        }
        .task-desc {
            margin: 0.5rem 0;
            color: #333;
        }
        .task-meta {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .task-meta i {
            margin-right: 0.4rem;
            color: #555;
        }
        .download-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .download-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="margin-top: 1rem;">📋 My Tasks</h2>

    <?php if (!empty($tasks)): ?>
        <div class="task-container">
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                    <div class="task-desc"><?= htmlspecialchars($task['description']) ?></div>
                    <div class="task-meta">
                        📅 <strong>Deadline:</strong> <?= $task['deadline'] ?>
                    </div>
                    <?php if (!empty($task['file'])): ?>
                        <a class="download-link" href="../uploads/<?= $task['file'] ?>" target="_blank">📎 Download File</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No tasks assigned to your project(s) yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
