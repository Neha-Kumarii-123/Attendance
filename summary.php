<?php

include("db.php");
include("header.php");


// Default values (current week)
$start_date = date('Y-m-d', strtotime('monday this week'));
$end_date = date('Y-m-d');
$report_type = "weekly";

// If form is submitted
if(isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'];
    
    if($report_type == "weekly") {
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d');
    } 
    elseif($report_type == "monthly") {
        $start_date = date('Y-m-01'); // Month start
        $end_date = date('Y-m-t');   // Month end
    }
    elseif($report_type == "custom") {
        // Validate and sanitize custom dates
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d');
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d');
        
        // Swap dates if start > end
        if(strtotime($start_date) > strtotime($end_date)) {
            $temp = $start_date;
            $start_date = $end_date;
            $end_date = $temp;
        }
    }
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2>
            <a class="btn btn-success" href="add.php">Add Student</a>
            <a class="btn btn-info pull-right" href="index.php">Back</a>
        </h2>
    </div>

    <div class="panel-body text-center">
        <h3>Attendance Summary (<?php echo $report_type == "custom" ? "Custom" : ucfirst($report_type); ?> Report)</h3>
        
        <!-- Date Selection Form -->
        <form method="post" class="form-inline" style="margin-bottom: 20px;">
            <div class="form-group">
                <label>Report Type: </label>
                <select name="report_type" class="form-control" id="reportType" onchange="toggleCustomDates()">
                    <option value="weekly" <?= ($report_type == "weekly") ? "selected" : "" ?>>This Week</option>
                    <option value="monthly" <?= ($report_type == "monthly") ? "selected" : "" ?>>This Month</option>
                    <option value="custom" <?= ($report_type == "custom") ? "selected" : "" ?>>Custom Range</option>
                </select>
            </div>
            
            <div id="customDates" style="<?= ($report_type != 'custom') ? 'display:none;' : '' ?> margin-left: 10px;">
                <div class="form-group">
                    <label>From: </label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                </div>
                <div class="form-group" style="margin-left: 10px;">
                    <label>To: </label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                </div>
            </div>
            <button type="submit" name="generate_report" class="btn btn-primary" style="margin-left: 10px;">Generate</button>
        </form>
        
        <!-- Summary Table -->
        <table class="table table-striped">
            <tr>
                <th>Roll No.</th>
                <th>Student Name</th>
                <th>Total Days</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Percentage</th>
            </tr>
            
            <?php
            // Get all students
            $students = mysqli_query($con, "SELECT * FROM attendance");
            
            while ($student = mysqli_fetch_array($students)) {
                $roll = $student['roll_number'];
                $name = $student['student_name'];
                
                // Modified query with date range
                $total_days_query = mysqli_query($con, 
                    "SELECT COUNT(*) as total 
                     FROM attendance_records 
                     WHERE roll_number='$roll' 
                     AND date BETWEEN '$start_date' AND '$end_date'");
                
                $total_days = mysqli_fetch_assoc($total_days_query)['total'];
                
                $present_days_query = mysqli_query($con, 
                    "SELECT COUNT(*) as present 
                     FROM attendance_records 
                     WHERE roll_number='$roll' 
                     AND attendance_status='Present' 
                     AND date BETWEEN '$start_date' AND '$end_date'");
                
                $present_days = mysqli_fetch_assoc($present_days_query)['present'];
                
                $absent_days = $total_days - $present_days;
                $percentage = ($total_days > 0) ? round(($present_days / $total_days) * 100, 2) : 0;
                
                echo "<tr>
                        <td>$roll</td>
                        <td>$name</td>
                        <td>$total_days</td>
                        <td>$present_days</td>
                        <td>$absent_days</td>
                        <td>$percentage%</td>
                    </tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
function toggleCustomDates() {
    var reportType = document.getElementById('reportType').value;
    var customDates = document.getElementById('customDates');
    
    if(reportType === 'custom') {
        customDates.style.display = 'inline-block';
    } else {
        customDates.style.display = 'none';
    }
}
</script>