<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'NaluriDatabase';

// Create a connection
$conn = new mysqli(hostname: $host, username: $user, password: $password, database: $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}
?>