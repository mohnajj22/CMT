<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/navbar_member.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

$conn = Database::getInstance()->getConnection();


$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notifications</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Notifications</h2>

    <?php if (empty($notifications)): ?>
        <p>You have no notifications yet.</p>
    <?php else: ?>
        <ul style="list-style: none; padding-left: 0;">
            <?php foreach ($notifications as $note): ?>
                <li style="margin-bottom: 1rem; background: #f9f9f9; padding: 1rem; border-left: 4px solid #005792;">
                    <?= htmlspecialchars($note['message']) ?><br>
                    <small><?= date('d M Y, H:i', strtotime($note['created_at'])) ?></small><br>
                    <?php if ($note['link']): ?>
                        <a href="../<?= htmlspecialchars($note['link']) ?>">View</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</body>
</html>
