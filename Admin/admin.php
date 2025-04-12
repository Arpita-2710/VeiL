<?php
require 'db_config.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle message deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit();
}

// Message filtering
$filter_ip = $_GET['ip'] ?? null;
$query = "SELECT * FROM messages";
if ($filter_ip) {
    $query .= " WHERE ip_address = ?";
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if ($filter_ip) {
    $stmt->bind_param("s", $filter_ip);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - VeiL</title>
    <style>
        body {
            background: url('admin_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
            backdrop-filter: blur(6px);
        }

        h2 {
            color: #00ffd5;
            display: inline-block;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar a {
            margin-left: 15px;
            color: #00ffd5;
            text-decoration: none;
            font-weight: bold;
        }

        .filter-form {
            margin: 20px 0;
        }

        input[type="text"], button {
            padding: 8px;
            border-radius: 5px;
            border: none;
        }

        input[type="text"] {
            width: 250px;
        }

        button {
            background-color: #00ffd5;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: rgba(0, 0, 0, 0.6);
        }

        th, td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #111;
            color: #00ffd5;
        }

        .delete-btn {
            background-color: #ff5e5e;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .ip-link {
            color: #00ffd5;
        }

        .actions {
            margin-top: 10px;
        }

        .actions a {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <h2>Admin Panel</h2>
    <div class="actions">
        <a href="admin_register.php">➕ Add Admin</a>
        <a href="admin_logout.php">🚪 Logout</a>
    </div>
</div>

<form method="GET" class="filter-form">
    <input type="text" name="ip" placeholder="Filter by IP" value="<?= htmlspecialchars($filter_ip) ?>">
    <button type="submit">Filter</button>
</form>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Message</th>
        <th>IP</th>
        <th>Timestamp</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['message']) ?></td>
            <td>
                <a class="ip-link" href="admin.php?ip=<?= urlencode($row['ip_address']) ?>">
                    <?= htmlspecialchars($row['ip_address']) ?>
                </a>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Delete this message?');">
                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
