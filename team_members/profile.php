<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$base = '../';
require_once $base . 'includes/Database.php';
include '../includes/navbar_member.php';


$pdo = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT name, email, role, profile_pic FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$email = $user['email'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile - CMT</title>
    <link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<div class="container">
    <h2>My Profile</h2>

    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>

    <form method="POST" action="update_profile.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Change Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control">
        </div>

        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
