<?php
session_start();
require_once '../includes/Database.php';

$conn = Database::getInstance()->getConnection();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

$member_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $description = $_POST['description'];

    if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === 0) {
        $fileName = basename($_FILES['submission_file']['name']);
        $fileTmp = $_FILES['submission_file']['tmp_name'];

        // For saving to disk
        $uploadFolder = '../professors/uploads/';
        $targetPath = $uploadFolder . $fileName;

        // For saving to DB (browser-accessible path)
        $browserPath = 'professors/uploads/' . $fileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            // Save submission record
            $stmt = $conn->prepare("INSERT INTO submissions (task_id, member_id, file_path, description) VALUES (?, ?, ?, ?)");
            $stmt->execute([$task_id, $member_id, $browserPath, $description]);

            $message = "✅ Task submitted successfully.";
        } else {
            $message = "❌ File upload failed.";
        }
    } else {
        $message = "❌ No file uploaded or upload error.";
    }
}

// Fetch tasks assigned to this team member
$tasks = [];
$sql = "SELECT t.id, t.title 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id
        JOIN project_members pm ON pm.project_id = p.id
        WHERE pm.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$member_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Work</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include '../includes/navbar_member.php'; ?>
    <div class="container">
        <h2>Submit Work</h2>
        <?php if ($message): ?>
            <p style="color:green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="task_id">Select Task:</label>
            <select name="task_id" id="task_id" required>
                <option value="">-- Select a task --</option>
                <?php foreach ($tasks as $task): ?>
                    <option value="<?= htmlspecialchars($task['id']) ?>"><?= htmlspecialchars($task['title']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="submission_file">Upload File:</label>
            <input type="file" name="submission_file" required><br><br>

            <label for="description">Comments (optional):</label><br>
            <textarea name="description" rows="4" cols="50"></textarea><br><br>

            <button type="submit">Submit Task</button>
        </form>
    </div>
</body>
</html>
