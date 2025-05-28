<?php
// Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/Database.php';

// Use singleton to get DB connection
$conn = Database::getInstance()->getConnection();

// Calculate base path
$base = (strpos($_SERVER['PHP_SELF'], '/professors/') !== false || strpos($_SERVER['PHP_SELF'], '/team_members/') !== false) ? '../' : '';

// Default avatar path
$avatar = $base . 'assets/images/default-avatar.png';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
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
        <div class="avatar-dropdown" style="position: relative;">
          <img src="<?= $avatar ?>" alt="Avatar" id="avatarBtn"
               style="width:32px; height:32px; border-radius:50%; object-fit:cover; margin-right: 0.5rem; cursor:pointer;">

          <div class="dropdown-menu" id="avatarMenu"
               style="display: none; position: absolute; background-color: white; padding: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 6px; z-index: 100;">
            <a href="<?= $base ?>professors/profile.php" style="display: block; padding: 5px;">Profile</a>
            <a href="<?= $base ?>logout.php" style="display: block; padding: 5px;">Logout</a>
          </div>
        </div>
      <?php endif; ?>
      <a href="<?= $base ?>index.php" class="nav-logo" style="font-weight: bold; font-size: 20px; color: white;">CMT</a>
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
      menu.style.display = menu.classList.contains('show') ? 'block' : 'none';
    });
    document.addEventListener('click', () => {
      menu.classList.remove('show');
      menu.style.display = 'none';
    });
  }
});
</script>
