<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/navbar_member.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

$conn = Database::getInstance()->getConnection();


$member_id = $_SESSION['user_id'];
$submissions = [];

try {
    $stmt = $conn->prepare("SELECT s.file_path, s.grade, s.feedback, s.submitted_at, t.title AS task_title
                            FROM submissions s
                            JOIN tasks t ON s.task_id = t.id
                            WHERE s.member_id = ? AND s.grade IS NOT NULL");
    $stmt->execute([$member_id]);
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Something went wrong: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Grades</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>My Graded Submissions</h2>

    <?php if (empty($submissions)): ?>
        <p>You have no graded submissions yet.</p>
    <?php else: ?>
        <?php foreach ($submissions as $sub): ?>
            <div class="grade-card">
                <div class="grade-header"><?= htmlspecialchars($sub['task_title']) ?> (Graded)</div>
                <div class="grade-row">
                    <div class="grade-circle"><?= htmlspecialchars($sub['grade']) ?></div>
                    <div class="grade-meta">
                        <p><strong>Submitted:</strong> <?= date("d M Y, H:i", strtotime($sub['submitted_at'])) ?></p>
                        <p><strong>Feedback:</strong> <?= htmlspecialchars($sub['feedback']) ?: 'No feedback' ?></p>
                        <a class="download-link" href="../<?= htmlspecialchars($sub['file_path']) ?>" target="_blank">📁 Download File</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
