<html>
<head>
    <title>Retreive Availability Test</title>
	        <style type="text/css">
            tab1 { padding-left: 4em; }
            tab2 { padding-left: 8em; }
        </style>
</head>

<body>
    <p>Availability</p>
<?php
require_once dirname(__FILE__) . "/../Query/Availability.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=db.cecs.pdx.edu port=5432 dbname=simca user=simca password=hk8#9Yyced"))) {
//if (!($CONNECTION = pg_connect("host=localhost port=5432 dbname=Cat user=guest password=Fido"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs = array();
$kwargs2 = array("block_hour" => (8));
$kwargs3 = array("block_day" => ('Monday'));
$kwargs4 = array("block_day" => ('Monday'), "block_hour" => (8));

$student_id = 4;
$term_id = 2;

if (!($result = retrieve_availability_for_student($student_id, $term_id, $kwargs))) {
        return false;
    } else if (!($students = pg_fetch_all($result))) {
        return false;
    }

		echo "<p>Term: " . $students[0]['term_name'] ."</p>\n";
		echo "<p><tab1>Student: ".$students[0]['student_username'] ."<tab1></p>\n";
	
	
    foreach ($students as $student) {
		echo "<p><tab2>". $student['block_day']." ".$student['block_hour']. " " .$student['block_preference'] ."<tab2></p>\n";
		}
			
?>
</body>
</html>
