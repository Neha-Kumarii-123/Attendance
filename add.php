<?php
include("header.php");
include("db.php");

$flag = 0;
$error = '';

if(isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $roll = mysqli_real_escape_string($con, $_POST['roll']);
    
    $check_query = "SELECT * FROM attendance WHERE roll_number = '$roll'";
    $check_result = mysqli_query($con, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $error = "Error: Student with roll number $roll already exists!";
    } else {
        $result = mysqli_query($con, "INSERT INTO attendance (student_name, roll_number) VALUES ('$name','$roll')");
        if($result) {
            $flag = 1;
        } else {
            $error = "Error: Failed to add student - " . mysqli_error($con);
        }
    }
}
?>

<div class="panel panel-default">
    <?php if($flag) { ?>
        <div class="alert alert-success">Student added successfully.</div>
    <?php } ?>
    <?php if(!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <div class="panel-heading">
        <h2>
            <a class="btn btn-success" href="add.php">Add Student</a>
            <a class="btn btn-info pull-right" href="index.php">Back</a>
        </h2>
    </div>

    <div class="panel-body">
        <form action="add.php" method="post">
            <div class="form-group">
                <label>Student Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Roll Number</label>
                <input type="text" name="roll" class="form-control" required>
            </div>
            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>
</div>
<?php include("footer.php"); ?>