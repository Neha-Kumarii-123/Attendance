<?php
// Database connection configuration
$con = mysqli_connect("localhost", "root", "", "attendance_system");

// Check connection
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Optional: Set charset to utf8 for proper character handling
mysqli_set_charset($con, "utf8");

// 1. Add this new function (Python API URL)
function getPythonAPIUrl() {
    return 'http://localhost:5000';
}

// 2. Add this main function (Iris Data Saver)
function saveIrisData($roll_number, $iris_data) {
    global $con;
    $iris_data = mysqli_real_escape_string($con, $iris_data);
    $query = "UPDATE attendance SET iris_data = '$iris_data' WHERE roll_number = '$roll_number'";
    return mysqli_query($con, $query);
}


?>