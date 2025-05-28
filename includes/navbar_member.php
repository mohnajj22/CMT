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
    $pdo = Database::getInstance()->getConnection();  // Singleton instance
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
            <a href="<?= $base ?>team_members/profile_member.php">Profile</a>
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
        <li><a href="grades.php">Grades</a></li>
        <a href="../team_members/my_project.php">My Project</a>
        <a href="<?= $base ?>team_members/my_tasks.php">My Tasks</a>
        <a href="<?= $base ?>team_members/dashboard.php">Team Dashboard</a>
        <a href="<?= $base ?>team_members/submit_work.php">Submit Work</a>
      <?php endif; ?>
    </div>
    <!-- Notification Bell -->
<div class="notification-dropdown" onclick="toggleNotifications()" style="position: relative; cursor: pointer; margin-left: 1.5rem;">
    <span style="font-size: 1.4rem;">🔔</span>
    <?php
    // Fetch unread count
    require_once 'Database.php';
    $conn = Database::getInstance()->getConnection();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user_id']]);
    $unreadCount = $stmt->fetchColumn();
    ?>
    <?php if ($unreadCount > 0): ?>
        <span style="position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 0.75rem; border-radius: 50%; padding: 3px 6px;">
            <?= $unreadCount ?>
        </span>
    <?php endif; ?>

    <!-- Dropdown -->
    <div id="notification-menu" class="dropdown-menu" style="right: 0; left: auto; max-height: 300px; overflow-y: auto;">
        <?php
        $stmt = $conn->prepare("SELECT message, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$_SESSION['user_id']]);
        $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if (empty($notes)): ?>
            <div style="padding: 10px;">No notifications</div>
        <?php else: ?>
            <?php foreach ($notes as $n): ?>
                <div style="padding: 10px; border-bottom: 1px solid #eee;">
                    <div><?= htmlspecialchars($n['message']) ?></div>
                    <small style="color: gray;"><?= date('d M Y, H:i', strtotime($n['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div style="text-align: center; padding: 8px;">
            <a href="notifications.php">View All</a>
        </div>
    </div>
</div>

<script>
function toggleNotifications() {
    var menu = document.getElementById('notification-menu');
    menu.classList.toggle('show');
}
</script>

   
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
