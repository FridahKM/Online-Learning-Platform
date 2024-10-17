<?php
// Database configuration
$host = "localhost";        // Host name (Usually localhost)
$db_user = "root";          // Database username
$db_pass = "";              // Database password (Leave blank for default on localhost)
$db_name = "online_learning_platform";  // Database name

// Create a connection to MySQL
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to close the connection (optional, you can use it later)
function closeDbConnection($conn) {
    $conn->close();
}
?>
