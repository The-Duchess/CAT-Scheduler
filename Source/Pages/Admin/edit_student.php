<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";

$report = "";
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create New Student</title>
    <link rel="stylesheet" href="../../JQuery-1.2/UI/jquery-ui-darkness/jquery-ui.min.css">
    <script src="../../JQuery-1.2/jquery-1.12.4.js"></script>
    <script src="../../JQuery-1.2/UI/jquery-ui-darkness/jquery-ui.min.js"></script>
    <script src="../../JavaScript/Admin/create_student.js"></script>
</head>

<body>
    <a href="../login_home.php">Return Home</a><br><br>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="studentusername">Username:</label><br>
        <input type="text" id="studentusername" name="studentusername" /><br>
        <label for="joindate">Join Date:</label><br>
        <input type="text" id="joindate" name="joindate"><br>
        <label for="leavedate">Leave Date:</label><br>
        <input type="text" id="leavedate" name="leavedate"><br>
        <label for="activestate">Active State:</label><br>
		True<input type="radio" name="activestate" value="True" checked="checked">
		<input type="radio" name="activestate" value="False">  False<br>
        <input type="submit" name="submit" value="Create Student" />
    </form>
<?php
//  check for submission
if (isset($_POST['submit'])) {
    if (isset($_POST['studentusername']) and $_POST['studentusername'] != "" and isset($_POST['joindate']) and isset($_POST['activestate'])) {
        $name = $_POST['studentusername'];
        $join = new DateTime($_POST['joindate']);
        $leave = new DateTime($_POST['leavedate']);
        $active = $_POST['activestate'];

		
		//  Database connection
        if (!($CONNECTION = cody_db_connect())) {
            $report = "ERROR: Failed to connect to database";
        } else if (!add_student($name, $join, $leave, $active)) {
            $report = "ERROR: Failed to add student to database";
        } else {
            $report = "Student Created";
        }
    } else {
        $report = "ERROR: Cannot create a Student with empty fields";
    }
}
?>
    <p><?php echo $report; ?></p>
</body>
</html>
