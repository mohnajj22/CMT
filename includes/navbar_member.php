<?php
// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate base path depending on current file location
$base = (strpos($_SERVER['PHP_SELF'], '/team_members/') !== false) ? '../' : '';

// Default avatar
$avatar = $base . 'assets/images/default-avatar.png';

if (isset($_SESSION['user_id'])) {
    require_once $base . 'includes/Database.php';
    $pdo = (new Database())->connect();
    $stmt = $pdo->prepare("SELECT profile_pic FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row['profile_pic'])) {
        $avatar = $base . htmlspecialchars($row['profile_pic']);
    }
}
?>
<nav class="navbar">
  <div class="nav-container">
    <!-- Left side: avatar + logo -->
    <div class="nav-left">
      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="avatar-dropdown">
          <img src="<?= $avatar ?>" alt="Avatar" id="avatarBtn" style="width:32px; height:32px; border-radius:50%; object-fit:cover; margin-right: 0.5rem; cursor:pointer;">
          <div class="dropdown-menu" id="avatarMenu">
            <a href="<?= $base ?>team_members/profile.php">Profile</a>
            <a href="<?= $base ?>logout.php">Logout</a>
          </div>
        </div>
      <?php endif; ?>
      <a href="<?= $base ?>index.php" class="nav-logo">CMT</a>
    </div>

    <!-- Right side: team member links -->
    <div class="nav-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= $base ?>team_members/enroll_project.php">Enroll in Project</a>
        <a href="../team_members/my_project.php">My Project</a>
        <a href="<?= $base ?>team_members/my_tasks.php">My Tasks</a>
        <a href="<?= $base ?>team_members/dashboard.php">Team Dashboard</a>
        <a href="<?= $base ?>team_members/submit_work.php">Submit Work</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('avatarBtn');
  const menu = document.getElementById('avatarMenu');
  if (btn) {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      menu.classList.toggle('show');
    });
    document.addEventListener('click', () => menu.classList.remove('show'));
  }
});
</script>
