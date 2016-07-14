<html>
<head>
    <title>Test</title>
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../Query/Student.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=db.cecs.pdx.edu port=5432 dbname=simca user=simca password=hk8#9Yyced"))) {
//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

echo "<h1>Using simcas DB</h1>";
echo "<h2>Testing get_student_id_by_username</h2>";

$s1 = pg_fetch_row(get_student_id_by_username("simca"))[0];
$s2 = pg_fetch_row(get_student_id_by_username("sim6"))[0];
$s3 = pg_fetch_row(get_student_id_by_username("bowzr"))[0];

//  Just a basic echo to show that the data was gathered and submitted
if (!empty($s1)) {
    echo "<p>" . $s1 . "</p>\n";
}
if (!empty($s2)) {
    echo "<p>" . $s2 . "</p>\n";
}
if (!empty($s3)) {
    echo "<p>" . $s3 . "</p>\n";
}
?>
</body>
</html>
