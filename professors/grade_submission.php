<?php
session_start();
require_once '../includes/Database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submission_id = $_POST['submission_id'];
    $grade = $_POST['grade'];
    $feedback = $_POST['feedback'] ?? '';

    $conn= Database::getInstance()->getConnection();  // Singleton instance

    // Update the submission
    $stmt = $conn->prepare("UPDATE submissions SET grade = ?, feedback = ? WHERE id = ?");
    $stmt->execute([$grade, $feedback, $submission_id]);

    // Optionally: get the project ID of the submission (to redirect back)
    $stmt2 = $conn->prepare("SELECT t.project_id 
                             FROM submissions s 
                             JOIN tasks t ON s.task_id = t.id 
                             WHERE s.id = ?");
    $stmt2->execute([$submission_id]);
    $project = $stmt2->fetch();

    if ($project) {
        $project_id = $project['project_id'];
        header("Location: project_details.php?id=" . $project_id);
        exit();
    }
}

echo "Something went wrong.";
?>
