<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/Project.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header('Location: ../login.php');
    exit();
}

$professor_id = $_SESSION['user_id'];
$message = "";

$db = new Database();
$conn = $db->connect();

$projectObj = new Project($conn);

// Generate enrollment code
function generateCode($length = 6) {
    return strtoupper(substr(md5(uniqid()), 0, $length));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $deadline = $_POST["deadline"];
    $enrollment_code = generateCode();

    if ($projectObj->createWithCode($title, $description, $deadline, $professor_id, $enrollment_code)) {
        $message = "✅ Project created successfully! Enrollment Code: <strong>$enrollment_code</strong>";
    } else {
        $message = "❌ Failed to create project.";
    }
}
?>

<?php include '../includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Project</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Create New Project</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Project Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Project Description</label>
            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" class="form-control" name="deadline" id="deadline" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
</div>
</body>
</html>
