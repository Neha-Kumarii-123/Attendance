<?php
// Database connection configuration
$con = mysqli_connect("localhost", "root", "", "attendance_system");

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: Set charset to utf8 for proper character handling
mysqli_set_charset($con, "utf8");
?>