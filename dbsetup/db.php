<?php
$host = "db";            
$user = "root";     
$password = "root";
$database = "school_db";    

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
