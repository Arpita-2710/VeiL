<?php
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password_raw = $_POST['password'];

    $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password_hash);

    if ($stmt->execute()) {
        echo "Registration successful. <a href='login.php'>Login here</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Register</h2>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type
