<?php
// filepath: C:\xampp\htdocs\anon\db_config.php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "root";  // Update based on your DB credentials
$password = "";
$dbname = "anonymous_messaging";

// Securely connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>
