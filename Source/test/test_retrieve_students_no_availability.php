<?php

include "../Query/Student.php";

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

/*
function cleanup_test_students() {
    $query = "DELETE FROM student WHERE student_username LIKE $1";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array("RSNA_TEST_STUDENT_%"));
}

function cleanup_test_hourblocks() {
    $query = "DELETE FROM hour_block WHERE student_id IN (SELECT student_id FROM student WHERE student_username LIKE $1)";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array("RSNA_TEST_STUDENT_%"));
}
 */

if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "Connection to Database Failed\n";
    exit();
} else {
    echo "Connection to Database Established\n";
}

/*
echo "Adding 3 Students to Database\n";
if (!temp_add_student("RSNA_TEST_STUDENT_1")) {
    echo "Adding Student 1 Failed\n";
    cleanup_test_students();
    exit();
} else if (!temp_add_student("RSNA_TEST_STUDENT_2")) {
    echo "Adding Student 2 Failed\n";
    cleanup_test_students();
    exit();
} else if (!temp_add_student("RSNA_TEST_STUDENT_3")) {
    echo "Adding Student 3 Failed\n";
    cleanup_test_students();
    exit();
}
 */


/*
$query = "SELECT student_id, student_username FROM Student WHERE student_username LIKE $1 LIMIT 3";
if (!($result = pg_query_params($CONNECTION, $query, array("RSNA_TEST_STUDENT_%")))) {
    echo "Failed to pull test students\n";
    cleanup_test_students();
    exit();
}
$students_initial = pg_fetch_all($results);
 */

$query = "SELECT student_id, student_username FROM student ORDER BY student_id ASC LIMIT 3";
if (!($results = pg_query($query))) {
    echo "Failed to pull test students\n";
    exit();
}
$students_initial = pg_fetch_all($results);

/*
echo "Test running on the following " . count($students_initial) . " students:\n";
foreach ($students_initial as $student) {
    echo "    " . $student['student_id'] . " -- " . $student['student_username'] . "\n";
    temp_remove_avail(1, $student['student_id']);
}
 */

echo "Test 1, should display all test students:\n";
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
