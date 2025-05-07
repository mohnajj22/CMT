<?php
session_start();

// Only basic protection for now (ignore role to test easily)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $uploadDir = 'uploads/';

        // Create the folder if not exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['file']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $message = "✅ File uploaded successfully!";
        } else {
            $message = "❌ Failed to upload file.";
        }
    } else {
        $message = "⚠️ No file selected or an error occurred.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload File - CMT</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .container {
      max-width: 700px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #005792;
    }
    label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
    }
    input[type="file"],
    input[type="text"],
    select {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.3rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      margin-top: 1.5rem;
      background-color: #005792;
      color: white;
      border: none;
      padding: 0.8rem 1.5rem;
      border-radius: 6px;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #003f5b;
    }
    .alert {
      margin-top: 1rem;
      padding: 10px;
      background-color: #e7f3fe;
      border-left: 5px solid #005792;
    }
  </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">
    <h2>Upload File</h2>

    <?php if (!empty($message)): ?>
        <div class="alert"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <label for="project">Select Project:</label>
      <select id="project" name="project">
        <option value="1">AI Chatbot System</option>
        <option value="2">Database Management Tool</option>
        <option value="3">E-Learning Platform</option>
      </select>

      <label for="task">Select Task:</label>
      <select id="task" name="task">
        <option value="1">Design Phase</option>
        <option value="2">Implementation</option>
        <option value="3">Testing</option>
      </select>

      <label for="file">Choose File to Upload:</label>
      <input type="file" id="file" name="file" required>

      <label for="description">File Description:</label>
      <input type="text" id="description" name="description" placeholder="Describe the file content" required>

      <input type="submit" value="Upload File">
    </form>
</div>

</body>
</html>
