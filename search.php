<?php
session_start();
require_once 'includes/database.php';
require_once 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$query = trim($_GET['q'] ?? '');
$results = ['projects' => [], 'tasks' => [], 'files' => []];

if ($query !== '') {
  $pdo = Database::getInstance()->getConnection();
    $like = "%{$query}%";

    // Search projects
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE title LIKE :q OR description LIKE :q");
    $stmt->execute([':q' => $like]);
    $results['projects'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search tasks - safer version (only title)
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE title LIKE :q");
    $stmt->execute([':q' => $like]);
    $results['tasks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search files - safe
    $stmt = $pdo->prepare("SELECT * FROM files WHERE file_path LIKE :q");
    $stmt->execute([':q' => $like]);
    $results['files'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results - CMT</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
  <h2>Search Results for “<?= htmlspecialchars($query) ?>”</h2>

  <?php if ($query === ''): ?>
    <p>Please enter a keyword to search.</p>
  <?php else: ?>

    <h3>Projects</h3>
    <?php if ($results['projects']): ?>
      <ul class="project-list">
        <?php foreach ($results['projects'] as $p): ?>
          <li class="project-item">
            <a href="project_details.php?id=<?= $p['id'] ?>" class="project-link"><?= htmlspecialchars($p['title']) ?></a>
            <?php if (!empty($p['description'])): ?>
              <p><?= htmlspecialchars($p['description']) ?></p>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No matching projects found.</p>
    <?php endif; ?>

    <h3>Tasks</h3>
    <?php if ($results['tasks']): ?>
      <ul>
        <?php foreach ($results['tasks'] as $t): ?>
          <li><strong><?= htmlspecialchars($t['title']) ?></strong></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No matching tasks found.</p>
    <?php endif; ?>

    <h3>Files</h3>
    <?php if ($results['files']): ?>
      <ul>
        <?php foreach ($results['files'] as $f): ?>
          <li><a href="uploads/<?= htmlspecialchars($f['file_path']) ?>" target="_blank"><?= htmlspecialchars($f['file_path']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No matching files found.</p>
    <?php endif; ?>
  <?php endif; ?>
</div>
</body>
</html>
