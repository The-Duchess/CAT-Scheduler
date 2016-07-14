<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
include "../Query/Availability.php";
//  Database connection
if (!($CONNECTION = pg_connect("host=localhost port=5432 dbname=Cat user=guest password=Fido"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs2 = array("block_hour" => (8));
//$kwargs3 = array("block_day" => ('Monday');
//$kwargs4 = array("block_day" => ('Monday'), "block_hour" => (8));

$student_id = 4;
$term_id = 2;

if (!($result = retrieve_availability_for_student($student_id, $term_id, $kwargs2))) {
        return false;
    } else if (!($students = pg_fetch_all($result))) {
        return false;
    }
    foreach ($students as $student) {
			echo "<p>" . $student['term_name']." ".$student['student_username'] ." ". $student['block_preference'] ." ". $student['block_day']." ".$student['block_hour']."</p>\n";
			}
			
?>
</body>
</html>
