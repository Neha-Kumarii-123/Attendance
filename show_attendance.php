<?php
include("db.php");
include("header.php");

$date = isset($_POST['date']) ? mysqli_real_escape_string($con, $_POST['date']) : date('Y-m-d');

if(isset($_POST['submit'])) {
    foreach($_POST['attendance_status'] as $roll_number => $status) {
        $query = "INSERT INTO attendance_records 
                 (student_name, roll_number, attendance_status, date)
                 SELECT a.student_name, a.roll_number, '".mysqli_real_escape_string($con, $status)."', '$date'
                 FROM attendance a
                 WHERE a.roll_number = '".mysqli_real_escape_string($con, $roll_number)."'
                 ON DUPLICATE KEY UPDATE 
                 attendance_status = VALUES(attendance_status)";
        mysqli_query($con, $query);
    }
    echo "<div class='alert alert-success'>Attendance updated successfully!</div>";
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>
            <a class="btn btn-success" href="add.php">Add Student</a>
            <a class="btn btn-info pull-right" href="index.php">Back</a>
        </h2>
    </div>

    <div class="panel-body">
        <form action="show_attendance.php" method="post">
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <table class="table table-striped">
                <tr>
                    <th>#Serial</th>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Attendance Status</th>
                </tr>
                <?php
                $result = mysqli_query($con, "SELECT ar.* FROM attendance_records ar 
                                            JOIN attendance a ON ar.roll_number = a.roll_number
                                            WHERE ar.date='$date'");
                $serialnumber = 0;
                while($row = mysqli_fetch_array($result)) {
                    $serialnumber++;
                ?>
                <tr>
                    <td><?php echo $serialnumber; ?></td>
                    <td><?php echo $row['student_name']; ?></td>
                    <td><?php echo $row['roll_number']; ?></td>
                    <td>
                        <input type="radio" name="attendance_status[<?php echo $row['roll_number']; ?>]" 
                            value="Present" <?php echo ($row['attendance_status'] == "Present") ? "checked" : ""; ?>> Present
                        <input type="radio" name="attendance_status[<?php echo $row['roll_number']; ?>]" 
                            value="Absent" <?php echo ($row['attendance_status'] == "Absent") ? "checked" : ""; ?>> Absent
                    </td>
                </tr>
                <?php } ?>
            </table>
            <input type="submit" name="submit" value="Update Attendance" class="btn btn-primary">
        </form>
    </div>
</div>
<?php include("footer.php"); ?>