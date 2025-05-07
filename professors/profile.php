<?php
session_start();
require_once '../includes/Database.php';

require_once '../includes/navbar.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$db = new Database();
$conn = $db->connect();

$message = "";

// Handle profile update (email + avatar)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $profile_pic = '';

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $uploadDir = 'uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . time() . "_" . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $profile_pic = $targetPath;
        }
    }

    // Build SQL to update email and (optionally) profile_pic
    $sql = "UPDATE users SET email = :email";
    if ($profile_pic) {
        $sql .= ", profile_pic = :profile_pic";
    }
    $sql .= " WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $params = [
        ':email' => $email,
        ':id'    => $userId
    ];
    if ($profile_pic) {
        $params[':profile_pic'] = $profile_pic;
    }

    if ($stmt->execute($params)) {
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Failed to update profile.";
    }
}

// Fetch user info (email, role, profile_pic)
$stmt = $conn->prepare("SELECT email, role, profile_pic FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p>User not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - CMT</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .profile-pic {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 15px;
    }
    .profile-form label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
    }
    .profile-form input[type="email"],
    .profile-form input[type="file"] {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.3rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .profile-form button {
      margin-top: 1.5rem;
      background-color: #005792;
      color: white;
      border: none;
      padding: 0.8rem 1.5rem;
      border-radius: 6px;
      cursor: pointer;
    }
    .profile-form button:hover {
      background-color: #003f5b;
    }
  </style>
</head>
<body>
 

  <div class="container">
    <h2>My Profile</h2>

    <?php if (!empty($message)): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <?php if (!empty($user['profile_pic'])): ?>
      <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Photo" class="profile-pic">
    <?php endif; ?>

    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= ucfirst(htmlspecialchars($user['role'])) ?></p>

    <form method="POST" enctype="multipart/form-data" class="profile-form">
      <label for="email">Change Email</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <label for="avatar">Upload New Profile Picture</label>
      <input type="file" name="avatar" id="avatar">

      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
