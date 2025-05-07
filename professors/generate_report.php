<?php
session_start();
// Protect access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Generate Report - CMT</title>
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
    select,
    input[type="submit"] {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.3rem;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
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
  </style>
</head>
<body>
  <?php include '../includes/navbar.php'; ?>

  <div class="container">
    <h2>Generate Project Report</h2>
    <form action="#" method="POST">
      <label for="project">Select Project:</label>
      <select id="project" name="project">
        <option value="1">AI Chatbot System</option>
        <option value="2">Database Management Tool</option>
        <option value="3">E-Learning Platform</option>
      </select>

      <label for="timeframe">Select Report Timeframe:</label>
      <select id="timeframe" name="timeframe">
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
        <option value="custom">Custom</option>
      </select>

      <input type="submit" value="Generate Report">
    </form>
  </div>
</body>
</html>
