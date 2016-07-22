<?php
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "Connection to Database Failed\n";
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
