<?php
require_once 'Database.php';

function notifyUser($user_id, $message, $link = null) {
$conn = Database::getInstance()->getConnection();

    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $message, $link]);
}
