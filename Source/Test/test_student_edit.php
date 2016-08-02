<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../API/Utility.php";
require_once dirname(__FILE__) . "/../../Update_student.php";

//  Database connection
if (!($CONNECTION = cody_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

	//Edit All
$kwargs = array(
	"student_username" => "cwyatt",
	"join_date" => (new DateTime("1990-09-03")),
	"leave_date" => (new DateTime("2016-07-27")),
	"active" => True);
	
	//No Leave Date
$kwargs2 = array(
	"student_username" => "cwyatt",
	"join_date" => (new DateTime("1990-09-03")),
	"active" => True);
	
	//No Join Date
$kwargs3 = array(
	"student_username" => "cwyatt",
	"leave_date" => (new DateTime("2016-07-27")),
	"active" => True);

	//No Username
$kwargs4 = array(
	"join_date" => (new DateTime("1990-09-03")),
	"leave_date" => (new DateTime("2016-07-27")),
	"active" => True);

	//No Active
$kwargs5 = array(
	"student_username" => "cwyatt",
	"join_date" => (new DateTime("1990-09-03")),
	"leave_date" => (new DateTime("2016-07-27")));

	//No Username or Join Date
$kwargs6 = array(
	"leave_date" => (new DateTime("2016-07-27")),
	"active" => True);

	//No Username or Leave Date
$kwargs7 = array(
	"student_username" => "cwyatt",
	"join_date" => (new DateTime("1990-09-03")),
	"leave_date" => (new DateTime("2016-07-27")),
	"active" => True);	
		
	//No Leave Date or Active
$kwargs8 = array(
	"student_username" => "cwyatt",
	"join_date" => (new DateTime("1990-09-03")));

	//Only Username
$kwargs9 = array(
	"student_username" => "cwyatt");
		
	//Only Active
$kwargs10 = array(
	"active" => False);

//Only the first update should work
//student_update(4, $kwargs);
//student_update(4, $kwargs2);
//student_update(4, $kwargs3);
//student_update(4, $kwargs4);
//student_update(4, $kwargs5);
//student_update(4, $kwargs6);
//student_update(4, $kwargs7);
//student_update(4, $kwargs8);
//student_update(4, $kwargs9);
student_update(4, $kwargs10);
?>
</body>
</html>
