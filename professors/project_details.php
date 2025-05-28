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
$conn = Database::getInstance()->getConnection();

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
    .modal {
      display: none;
      position: fixed;
      top: 10%;
      left: 25%;
      width: 50%;
      background-color: #fff;
      padding: 20px;
      border: 2px solid #ccc;
      z-index: 9999;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    .modal button {
      margin-right: 10px;
    }
    .grades-table {
      width: 100%;
      border-collapse: collapse;
    }
    .grades-table th, .grades-table td {
      border: 1px solid #ddd;
      padding: 10px;
    }
    .grades-table th {
      background-color: #f4f4f4;
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

  <!-- 🔁 REPLACED TASK PROGRESS WITH GRADES SECTION -->
  <div class="section">
    <h3>Grades</h3>
    <?php
      $stmt = $conn->prepare("SELECT s.id AS submission_id, s.task_id, s.file_path, s.grade, s.feedback, s.submitted_at,
                                     t.title AS task_title, u.name AS member_name
                              FROM submissions s
                              JOIN tasks t ON s.task_id = t.id
                              JOIN project_members pm ON pm.user_id = s.member_id
                              JOIN users u ON u.id = s.member_id
                              WHERE t.project_id = :project_id");
      $stmt->execute(['project_id' => $project_id]);
      $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (count($submissions) === 0): ?>
      <p>No submissions yet.</p>
    <?php else: ?>
      <table class="grades-table">
        <tr>
          <th>Task</th>
          <th>Student</th>
          <th>File</th>
          <th>Status</th>
          <th>Grade</th>
        </tr>
        <?php foreach ($submissions as $sub): ?>
          <tr>
            <td><?= htmlspecialchars($sub['task_title']) ?></td>
            <td><?= htmlspecialchars($sub['member_name']) ?></td>
            <td><a href="<?= $sub['file_path'] ?>" target="_blank">Download</a></td>
            <td><?= $sub['grade'] !== null ? 'Graded' : 'Pending' ?></td>
            <td>
              <button onclick="document.getElementById('modal-<?= $sub['submission_id'] ?>').style.display='block'">Grade</button>
            </td>
          </tr>

          <!-- Modal -->
          <div id="modal-<?= $sub['submission_id'] ?>" class="modal">
            <h4>Grade Submission</h4>
            <form action="grade_submission.php" method="post">
              <input type="hidden" name="submission_id" value="<?= $sub['submission_id'] ?>">
              <label>Grade (0–100):</label>
              <input type="number" name="grade" min="0" max="100" value="<?= $sub['grade'] ?>"><br><br>
              <label>Feedback (optional):</label><br>
              <textarea name="feedback" rows="4" cols="50"><?= htmlspecialchars($sub['feedback']) ?></textarea><br><br>
              <button type="submit">Submit</button>
              <button type="button" onclick="document.getElementById('modal-<?= $sub['submission_id'] ?>').style.display='none'">Cancel</button>
            </form>
          </div>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
