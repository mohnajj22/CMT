<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/Database.php';
$pdo = Database::getInstance()->getConnection();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['enrollment_code']);

    $stmt = $pdo->prepare("SELECT id FROM projects WHERE enrollment_code = ?");
    $stmt->execute([$code]);
    $project = $stmt->fetch();

    if ($project) {
        $project_id = $project['id'];
        $user_id = $_SESSION['user_id'];

        // Check if already enrolled
        $stmt = $pdo->prepare("SELECT * FROM project_members WHERE project_id = ? AND user_id = ?");
        $stmt->execute([$project_id, $user_id]);
        if ($stmt->fetch()) {
            $message = "⚠️ You are already enrolled in this project.";
        } else {
            // Check how many are enrolled already
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM project_members WHERE project_id = ?");
            $stmt->execute([$project_id]);
            $count = $stmt->fetchColumn();

            if ($count >= 2) {
                $message = "❌ This project already has 2 members.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO project_members (project_id, user_id) VALUES (?, ?)");
                $stmt->execute([$project_id, $user_id]);
                $message = "✅ You have been successfully enrolled in the project.";
            }
        }
    } else {
        $message = "❌ Invalid enrollment code.";
    }
}
?>

<?php include '../includes/navbar_member.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Enroll in Project</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Enroll in a Project</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Enter Enrollment Code</label>
            <input type="text" name="enrollment_code" class="form-control" required>
        </div>
        <button type="submit" class="btn-primary">Enroll</button>
    </form>
</div>
</body>
</html>
