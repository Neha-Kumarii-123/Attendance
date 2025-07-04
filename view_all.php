<?php
include("db.php");
include("header.php");
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>
            <a class="btn btn-success" href="add.php">Add Student</a>
            <a class="btn btn-info pull-right" href="index.php">Back</a>
        </h2>
    </div>

    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>#Serial</th>
                <th>Date</th>
                <th>Show Attendance</th>
            </tr>
            <?php
            $result = mysqli_query($con, "SELECT DISTINCT date FROM attendance_records ORDER BY date DESC");
            $serialnumber = 0;
            while($row = mysqli_fetch_array($result)) {
                $serialnumber++;
            ?>
            <tr>
                <td><?php echo $serialnumber; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>
                    <form action="show_attendance.php" method="post">
                        <input type="hidden" name="date" value="<?php echo $row['date']; ?>">
                        <input type="submit" value="View Attendance" class="btn btn-primary">
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php include("footer.php"); ?>