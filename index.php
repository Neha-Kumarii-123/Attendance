<?php
include("db.php");
include("header.php");

$flag = 0;
$update = 0;

// Success/error messages
if(isset($_GET['success'])) {
    echo '<div class="alert alert-success">Attendance saved successfully!</div>';
}
if(isset($_GET['error'])) {
    echo '<div class="alert alert-danger">Error saving attendance!</div>';
}// ADD THIS NEW MESSAGE FOR DELETE SUCCESS
if(isset($_GET['delete']) && $_GET['delete'] == 'success') {
    echo '<div class="alert alert-success">Student deleted successfully!</div>';
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>
            <a class="btn btn-success" href="add.php">Add Student</a>
            <a class="btn btn-info pull-right" href="view_all.php">View All</a>
            <a class="btn btn-primary" href="summary.php" style="margin-right: 10px;">View Summary</a>
        </h2>
        <h3><div class="well text-center">Date: <?php echo date("Y-m-d"); ?></div></h3> 
    </div>

    <div class="panel-body">
        <form action="update_attendance.php" method="post">
            <table class="table table-striped">
                <tr>
                    <th>#Serial</th>
                    <th>Student Name</th>
                    <th>Roll Number</th>
                    <th>Attendance Status</th>
                    <th>Action</th> 
                </tr>
                <?php 
                $result = mysqli_query($con, "SELECT * FROM attendance");
                $serialnumber = 0;
                while($row = mysqli_fetch_array($result)) {
                    $serialnumber++;
                    // Check if attendance already marked
                    $attendance_check = mysqli_query($con, "SELECT attendance_status FROM attendance_records 
                                                          WHERE roll_number='".$row['roll_number']."' 
                                                          AND date='".date("Y-m-d")."'");
                    $attendance_status = mysqli_fetch_assoc($attendance_check);
                ?>
                <tr>
                    <td><?php echo $serialnumber; ?></td>
                    <td>
                        <?php echo $row['student_name']; ?>
                        <input type="hidden" name="student_name[]" value="<?php echo $row['student_name']; ?>">
                    </td>
                    <td>
                        <?php echo $row['roll_number']; ?>
                        <input type="hidden" name="roll_number[]" value="<?php echo $row['roll_number']; ?>">
                    </td>
                    <td>
                        <input type="radio" name="attendance_status[<?php echo $serialnumber-1; ?>]" value="Present" 
                            <?php echo (isset($attendance_status['attendance_status']) && $attendance_status['attendance_status'] == "Present") ? "checked" : ""; ?>> Present
                        <input type="radio" name="attendance_status[<?php echo $serialnumber-1; ?>]" value="Absent"
                            <?php echo (isset($attendance_status['attendance_status']) && $attendance_status['attendance_status'] == "Absent") ? "checked" : ""; ?>> Absent
                    </td>
                    <td>
                        <!-- ADD THIS NEW DELETE BUTTON -->
                        <a href="delete.php?roll=<?php echo $row['roll_number']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <input type="submit" name="submit" value="Submit Attendance" class="btn btn-primary">
        </form>
    </div>
</div>
<?php include("footer.php"); ?>