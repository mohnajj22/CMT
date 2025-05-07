<?php
session_start();
require_once '../includes/Database.php';

require_once '../includes/Project.php';
require_once '../includes/navbar.php';

// Check for login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check for project ID
if (!isset($_GET['id'])) {
    echo "<p>⚠️ Project ID not provided.</p>";
    exit();
}

$project_id = $_GET['id'];
$db = new Database();
$conn = $db->connect();
$projectObj = new Project($conn);
$project = $projectObj->getProjectById($project_id);

if (!$project) {
    echo "<p>⚠️ Project not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Project Details - CMT</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .project-details { padding: 2rem; }
    .section {
      background: #fff;
      border-radius: 10px;
      padding: 1.5rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
    }
    .section h3 { margin-top: 0; color: #005792; }
    .btn {
      display: inline-block;
      padding: 0.5rem 1rem;
      background-color: #005792;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 0.5rem;
    }
    .btn:hover { background-color: #003d5b; }
    .progress-bar {
      background: #eee;
      border-radius: 20px;
      overflow: hidden;
      height: 20px;
      margin-top: 10px;
    }
    .progress-bar-fill {
      height: 100%;
      background-color: #00b894;
      width: 60%;
      text-align: right;
      padding-right: 10px;
      color: white;
      line-height: 20px;
    }
  </style>
</head>
<body>
 

  <div class="project-details">
    <h2>Project: <?= htmlspecialchars($project['title']) ?></h2>
    <p><strong>Deadline:</strong> <?= htmlspecialchars($project['deadline']) ?></p>

    <div class="section">
      <h3>Assign Task</h3>
      <p>Assign a new task to a team member (select from database - not implemented yet).</p>
      <a href="assign_task.php?project_id=<?= $project['id'] ?>" class="btn">Assign New Task</a>
    </div>

    <div class="section">
      <h3>Upload File</h3>
      <p>Upload a related document or deliverable to the project folder.</p>
      <a href="assign_task.php?project_id=<?= $project['id'] ?>" class="btn">Upload File</a>
    </div>

    <div class="section">
      <h3>Team Members</h3>
      <ul>
        <li>Ahmed Alnajeh - Backend</li>
        <li>Mohamed Alnajeh - Frontend</li>
      </ul>
    </div>

    <div class="section">
      <h3>Task Progress</h3>
      <p>Current completion status of all assigned tasks:</p>
      <div class="progress-bar">
        <div class="progress-bar-fill">60%</div>
      </div>
    </div>
  </div>
</body>
</html>
