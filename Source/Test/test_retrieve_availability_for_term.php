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
require_once dirname(__FILE__) . "/../Query/Availability.php";

//  Database connection
if (!($CONNECTION =cody_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs2 = array(
    "start" => ((new DateTime("2016-08-01"))->format("Y-m-d")));
$kwargs3 = array(
    "ascend" => true);

//  Caller is responsible for form initialization
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";

$info = retrieve_availability_for_term(2);

if (empty($info)){
    echo "<p> EMPTY </p>";
}

foreach($info as $data) {
    echo "<p> Next Student  </p>";

    if(!pg_fetch_all($data)){
	echo "<p> Student hasn't inputted any availabilities!! </p>";
    }else {
	foreach (pg_fetch_all($data) as $student) {
	    echo "<p> " . $student['student_username'] ." ". $student['block_preference'] ." ". $student['block_day']." ".$student['block_hour']."</p>\n";
	}
    }
}	

?>
</body>
</html>
