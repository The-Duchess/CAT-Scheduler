<?php

require_once dirname(__FILE__) . "/../API/Utility.php";

//  Database connection
if (!($CONNECTION =cody_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
} else {
    echo "Connection to Database Established\n";
}

/*
$query = "INSERT INTO student (student_username) VALUES($1)";
pg_query_params($CONNECTION, $query, array("test4"));
pg_query_params($CONNECTION, $query, array("test5"));
pg_query_params($CONNECTION, $query, array("test6"));
pg_query_params($CONNECTION, $query, array("test7"));
 */

$query = "SELECT * FROM student";
$arr = pg_fetch_all(pg_query($query));
foreach ($arr as $student) {
    echo $student['student_username'] . "\n";
}
?>
