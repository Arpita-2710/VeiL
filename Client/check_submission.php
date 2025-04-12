<?php
require 'db_config.php';

$ip = $_SERVER['REMOTE_ADDR'];
$sql = "SELECT COUNT(*) as count FROM messages WHERE ip_address = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['hasSubmitted' => $row['count'] > 0]);
?>
