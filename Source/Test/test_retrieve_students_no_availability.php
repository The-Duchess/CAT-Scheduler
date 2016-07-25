<?php

require_once dirname(__FILE__) . "/../API/Utility.php";
require_once dirname(__FILE__) . "/../Query/Student.php";

function temp_add_student($username) {
    $query = "INSERT INTO student (student_username) VALUES ($1)";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($username));
}
function temp_add_avail($term_id, $student_id) {
    $query = "INSERT INTO hour_block (term_id, student_id, block_day, block_hour, block_preference) VALUES ($1, $2, $3, $4, $5)";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($term_id, $student_id, "Monday", "12", "Preferred"));
}
function temp_remove_avail($term_id, $student_id) {
    $query = "DELETE FROM hour_block WHERE term_id=$1 AND student_id=$2";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($term_id, $student_id));
}

//  Database connection
if (!($CONNECTION =cody_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
} else {
    echo "Connection to Database Established\n";
}

$query = "SELECT student_id, student_username FROM student WHERE active=true ORDER BY student_id ASC LIMIT 3";
if (!($results = pg_query($query))) {
    echo "Failed to pull test students\n";
    exit();
}
$students_initial = pg_fetch_all($results);

$arr = array();
foreach ($students_initial as $student) {
    array_push($arr, $student['student_username']);
}
echo "Test 1, should a list of students INCLUDING AT LEAST " . implode(", ", $arr) . "\n";
if (!($results = retrieve_students_no_availability(1))) {
    echo "Failed to retrieve students\n";
    exit();
}
$students = pg_fetch_all($results);
echo "    Students That Have Not Submitted Availability:\n";
foreach ($students as $student) {
    echo "        " . $student['student_id'] . " -- " . $student['student_username'] . "\n";
}

echo "Adding Availability for " . $students_initial[0]['student_username'] . "\n";
if (!temp_add_avail(1, $students_initial[0]['student_id'])) {
    echo "Failed to add availability\n";
    exit();
}
echo "Test 2, should display all test students except " . $students_initial[0]['student_username'] . "\n";
if (!($results = retrieve_students_no_availability(1))) {
    echo "Failed to retrieve students\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    exit();
}
$students = pg_fetch_all($results);
echo "    Students That Have Not Submitted Availability:\n";
foreach ($students as $student) {
    echo "        " . $student['student_id'] . " -- " . $student['student_username'] . "\n";
}

echo "Adding Availability for " . $students_initial[2]['student_username'] . "\n";
if (!temp_add_avail(1, $students_initial[2]['student_id'])) {
    echo "Failed to add availability\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    exit();
}
// echo "Test 3, should only display student " . $students_initial[1]['student_username'] . "\n";
echo "Test 3, should display all test students except " . $students_initial[0]['student_username'] . " and " . $students_initial[2]['student_username'] . "\n";
if (!($results = retrieve_students_no_availability(1))) {
    echo "Failed to retrieve students\n";
    temp_remove_avail(1, $students_inital[0]['student_id']);
    temp_remove_avail(1, $students_inital[2]['student_id']);
    exit();
}
$students = pg_fetch_all($results);
echo "    Students That Have Not Submitted Availability:\n";
foreach ($students as $student) {
    echo "        " . $student['student_id'] . " -- " . $student['student_username'] . "\n";
}

echo "Cleaning up\n";
foreach ($students_initial as $student) {
    temp_remove_avail(1, $student['student_id']);
}
echo "Done\n";


?>
