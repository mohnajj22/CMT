<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/Task.php';
require_once '../includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['project_id'])) {
    echo "<p class='alert'>⚠️ Project ID not provided.</p>";
    exit();
}

$project_id = $_GET['project_id'];

$conn = Database::getInstance()->getConnection();

$task = new Task($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $deadline = $_POST["deadline"];

    // Handle file upload
    $fileName = "";
    if (isset($_FILES["attachment"]) && $_FILES["attachment"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES["attachment"]["name"]);
        $targetPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetPath);
    }

    if ($task->create($title, $description, $deadline, $project_id, $fileName)) {
        $message = "✅ Task assigned successfully!";

        // ✅ Notify enrolled team members
        require_once '../includes/notify.php';

        $stmt = $conn->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
        $stmt->execute([$project_id]);
        $members = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($members as $member_id) {
            notifyUser($member_id, "A new task was assigned: $title", "team_members/my_tasks.php");
        }
    } else {
        $message = "❌ Failed to assign task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Task</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>



<div class="container">
    <h2>Assign Task</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Task Title</label>
            <input type="text" class="form-control" name="title" id="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Task Description</label>
            <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" class="form-control" name="deadline" id="deadline" required>
        </div>

        <div class="mb-3">
            <label for="attachment" class="form-label">Attach File (optional)</label>
            <input type="file" class="form-control" name="attachment" id="attachment">
        </div>

        <button type="submit" class="btn-primary">Assign Task</button>
    </form>
</div>

</body>
</html>
