<?php
$server_name = 'localhost';
$username = 'root';
$password = '';
$database = "undangan";

$conn = new mysqli($server_name, $username, $password, $database);

if ($conn->connect_errno) {
  die("Failed Connect to database: " . $conn->connect_error);
}