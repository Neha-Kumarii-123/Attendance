<?php

include("db.php");

// 1. GEET The roll number
$roll = mysqli_real_escape_string($con, $_GET['roll']);

// 2.Deletion of record from database
$query1 = "DELETE FROM attendance WHERE roll_number='$roll'";
$query2 = "DELETE FROM attendance_records WHERE roll_number='$roll'";

$result1 = mysqli_query($con, $query1);
$result2 = mysqli_query($con, $query2);

// 3. Redirecting on index.php on Success
if($result1 && $result2) {
    header("location:index.php?delete=success");
} else {
    die("Error deleting: ".mysqli_error($con));
}
?>