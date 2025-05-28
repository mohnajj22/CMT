<?php
session_start();
require_once '../includes/Database.php';
require_once '../includes/navbar_member.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$conn = Database::getInstance()->getConnection();

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $profile_pic = '';

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $uploadDir = 'uploads_member/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['avatar']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
            $profile_pic = 'team_members/' . $targetPath; // store relative path
        }
    }

    $sql = "UPDATE users SET email = :email";
    if ($profile_pic) {
        $sql .= ", profile_pic = :profile_pic";
    }
    $sql .= " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $params = [
        ':email' => $email,
        ':id' => $userId
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

// Get user info
$stmt = $conn->prepare("SELECT email, role, profile_pic FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - Member</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .profile-pic {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        form label {
            display: block;
            margin-top: 1rem;
        }
        form input[type="email"], input[type="file"] {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
        }
        button {
            margin-top: 1rem;
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
        <img src="../<?= htmlspecialchars($user['profile_pic']) ?>" class="profile-pic" alt="Profile Picture">
    <?php endif; ?>

    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= ucfirst(htmlspecialchars($user['role'])) ?></p>

    <!-- ✅ This form submits to this file itself -->
    <form method="POST" enctype="multipart/form-data">
        <label for="email">Change Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="avatar">Upload New Profile Picture</label>
        <input type="file" name="avatar">

        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</div>
</body>
</html>
