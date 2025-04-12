<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Join messages with users table to fetch recipient username
$result = $conn->query("SELECT messages.message, messages.created_at, users.username 
                        FROM messages 
                        JOIN users ON messages.user_id = users.id 
                        ORDER BY messages.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Messages</title>
  <style>
    body {
      background: #121212;
      color: white;
      font-family: Arial;
      padding: 20px;
    }
    .message {
      background: #1e1e1e;
      margin-bottom: 15px;
      padding: 15px;
      border-radius: 10px;
    }
    .message small {
      color: #aaa;
      display: block;
      margin-top: 5px;
    }
    a {
      color: #04AA6D;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <h2>Shared Messages</h2>
  <p><a href="index.php">← Back to Home</a> | <a href="logout.php">Logout</a></p>

  <?php while ($msg = $result->fetch_assoc()): ?>
    <div class="message">
      <p><?= htmlspecialchars($msg['message']) ?></p>
      <small>Sent to: <?= htmlspecialchars($msg['username']) ?> | <?= $msg['created_at'] ?></small>
    </div>
  <?php endwhile; ?>
</body>
</html>
