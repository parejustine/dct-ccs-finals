<?php
// db.php

// Database connection
$servername = "localhost"; // Laragon default
$username = "root";        // Default username
$password = "";            // Default password
$dbname = "dct-ccs-finals"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate user credentials
function validateUser($email, $password)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $hashedPassword = md5($password); // Assuming passwords are stored as MD5 hash
    $stmt->bind_param("ss", $email, $hashedPassword);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to check if the default admin credentials are correct
function validateAdmin($email, $password)
{
    $defaultAdminEmail = "admin@gmai..com"; // Admin email
    $defaultAdminPassword = "admin123"; // Admin password (plaintext, for simplicity)
    return ($email == $defaultAdminEmail && $password == $defaultAdminPassword);
}

// Close the database connection (optional, can be done in login.php as well)
function closeDbConnection()
{
    global $conn;
    $conn->close();
}