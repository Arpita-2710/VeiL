<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['user_id'])) {
    $message = trim($_POST['message']);
    $user_id = intval($_POST['user_id']);
    $ip = $_SERVER['REMOTE_ADDR'];

    if ($message === "" || $user_id <= 0) {
        echo "Invalid input.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO messages (message, ip_address, user_id) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Failed to prepare statement.";
        exit;
    }

    $stmt->bind_param("ssi", $message, $ip, $user_id);

    if ($stmt->execute()) {
        echo "Message submitted!";
    } else {
        echo "Error submitting message.";
    }

    $stmt->close();
} else {
    echo "❌ Invalid request.";
}
?>
