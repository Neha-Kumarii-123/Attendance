<?php
include("db.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit'])) {
    $date = date("Y-m-d");
    
    foreach($_POST['attendance_status'] as $id => $attendance_status) {
        $student_name = mysqli_real_escape_string($con, $_POST['student_name'][$id]);
        $roll_number = mysqli_real_escape_string($con, $_POST['roll_number'][$id]);
        $attendance_status = mysqli_real_escape_string($con, $attendance_status);
        
        $query = "INSERT INTO attendance_records 
                 (student_name, roll_number, attendance_status, date)
                 VALUES ('$student_name', '$roll_number', '$attendance_status', '$date')
                 ON DUPLICATE KEY UPDATE 
                 attendance_status = VALUES(attendance_status),
                 student_name = VALUES(student_name)";
        
        $result = mysqli_query($con, $query);
        if(!$result) {
            die("Error: ".mysqli_error($con));
        }
    }
    
    header("Location: index.php?success=1");
    exit();
} else {
    header("Location: index.php?error=1");
    exit();
}
?>