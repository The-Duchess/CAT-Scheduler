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
include "../Query_retrieve_shift_preference.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=Fido"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs2 = array("block_hour" => (8));
//$kwargs3 = array("block_day" => ('Monday');
//$kwargs4 = array("block_day" => ('Monday'), "block_hour" => (8));

$student_id = 5;
$term_id = 1;

if (!($result = retrieve_shift_preference($student_id, $term_id))) {
        return false;
    } else if (!($shifts = pg_fetch_all($result))) {
        return false;
    }
    foreach ($shifts as $shift) {
	echo "<p>Student ID: " . $student_id . " shift preference is:  ";
	print_r($shift);
	echo " For term ID: " . $term_id . "</p>\n";
    }

$student_id = 6;
$term_id = 3;

if (!($result = retrieve_shift_preference($student_id, $term_id))) {
    return false;
    } else if (!($shifts = pg_fetch_all($result))) {
	return false;
    }
    foreach ($shifts as $shift) {
    echo "<p>Student ID: " . $student_id . " shift preference is:  ";
    print_r($shift);
    echo " For term ID: " . $term_id . "</p>\n";
    }   
			
?>
</body>
</html>