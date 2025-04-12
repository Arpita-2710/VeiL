<?php
session_start();
require 'db_config.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed_password);
            if ($insert->execute()) {
                $success = "Admin registered successfully!";
            } else {
                $error = "Error while registering admin.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin - VeiL</title>
    <style>
        body {
            background: #0e0e0e;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .register-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px #00ffd5;
            width: 300px;
            text-align: center;
        }

        h2 {
            color: #00ffd5;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #00ffd5;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .msg {
            margin-top: 10px;
            font-size: 14px;
        }

        .error {
            color: #ff5e5e;
        }

        .success {
            color: #7fff7f;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #00ffd5;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Register New Admin</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="New Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>

        <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="msg success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
    </form>

    <div class="login-link">
        Already have an account? <a href="admin_login.php">Login here</a>
    </div>
</div>

</body>
</html>
