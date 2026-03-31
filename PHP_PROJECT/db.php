<?php
// db.php - Database connection

$localhost = 'localhost'; // Usually 'localhost'
$username = 'root'; // Your database username
$password = ''; // Your database password
$dbname = 'blog_system'; // The name of your database

// Create a connection to the database
$conn = new mysqli($localhost, $username, $password, $dbname);

// Check for any connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
