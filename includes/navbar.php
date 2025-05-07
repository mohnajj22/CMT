<?php
// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Calculate base path depending on current file location
$base = (strpos($_SERVER['PHP_SELF'], '/professors/') !== false || strpos($_SERVER['PHP_SELF'], '/team_members/') !== false) ? '../' : '';

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
          <!-- Inline styling used here for guaranteed small circle -->
          <img src="<?= $avatar ?>" alt="Avatar" id="avatarBtn" style="width:32px; height:32px; border-radius:50%; object-fit:cover; margin-right: 0.5rem; cursor:pointer;">
          <div class="dropdown-menu" id="avatarMenu">
            <a href="<?= $base ?>professors/profile.php">Profile</a>
            <a href="<?= $base ?>logout.php">Logout</a>
          </div>
        </div>
      <?php endif; ?>
      <a href="<?= $base ?>index.php" class="nav-logo">CMT</a>
    </div>

    <!-- Right side: links + search -->
    <div class="nav-right">
      <?php if (isset($_SESSION['user_id'])): ?>
        <link rel="stylesheet" href="<?= $base ?>assets/style.css">

        <a href="<?= $base ?>professors/my_projects.php">My Projects</a>
        <a href="<?= $base ?>professors/create_project.php">Create Project</a>
        <a href="<?= $base ?>professors/dashboard.php">Dashboard</a>
        <form action="<?= $base ?>search.php" method="get" class="nav-search">
          <input type="text" name="q" class="search-input" placeholder="Search…" required>
          <button type="submit" class="search-button">🔍</button>
        </form>
      <?php else: ?>
        <a href="<?= $base ?>login.php">Login</a>
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
