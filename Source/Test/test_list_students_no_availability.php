<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>Test for Source/API/Admin.php::list_students_no_availability()</p>
<?php
error_reporting(E_ALL);
ini_set("display_errors", "on");

require_once dirname(__FILE__) . "/..API/Admin.php";

function temp_add_avail($term_id, $student_id) {
    $query = "INSERT INTO hour_block (term_id, student_id, block_day, block_hour, block_preference) VALUES ($1, $2, $3, $4, $5)";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($term_id, $student_id, "Monday", "12", "Preferred"));
}
function temp_remove_avail($term_id, $student_id) {
    $query = "DELETE FROM hour_block WHERE term_id=$1 AND student_id=$2";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($term_id, $student_id));
}

if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection to Database Failed</p>\n";
    exit();
}
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection to Database Failed</p>\n";
    exit();
} else {
    echo "<p>Connection to Database Established</p>\n";
}

$query = "SELECT student_id, student_username FROM student ORDER BY student_id ASC LIMIT 3";
if (!($results = pg_query($query))) {
    echo "<p>Failed to pull test students</p>\n";
    exit();
}
$students_initial = pg_fetch_all($results);

echo "<p>Test 1, should display all test students:</p>\n";
if (!list_students_no_availability(1)) {
    echo "<p>Failed to display list</p>\n";
    exit();
}

echo "<p>Adding Availability for " . $students_initial[0]['student_username'] . "</p>\n";
if (!temp_add_avail(1, $students_initial[0]['student_id'])) {
    echo "<p>Failed to add availability</p>\n";
    exit();
}
echo "<p>Test 2, should display all test students except " . $students_initial[0]['student_username'] . "</p>\n";
if (!list_students_no_availability(1)) {
    echo "<p>Failed to retrieve students</p>\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    exit();
}

echo "<p>Adding Availability for " . $students_initial[2]['student_username'] . "</p>\n";
if (!temp_add_avail(1, $students_initial[2]['student_id'])) {
    echo "<p>Failed to add availability</p>\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    exit();
}

echo "<p>Test 3, should display all test students except " . $students_initial[0]['student_username'] . " and " . $students_initial[2]['student_username'] . "</p>\n";
if (!list_students_no_availability(1)) {
    echo "<p>Failed to retrieve students</p>\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    temp_remove_avail(1, $students_inital[2]['student_id']);
    exit();
}

echo "<p>Cleaning up</p>\n";
foreach ($students_initial as $student) {
    temp_remove_avail(1, $student['student_id']);
}
echo "<p>Done</p>\n";

?>
</body>
</html>
