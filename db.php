<?php
$host = 'unreliablecode.net'; // Database host
$user = 'root';      // Database username
$pass = '250304';          // Database password
$dbname = 'inventory_management'; // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
