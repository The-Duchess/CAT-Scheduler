<html>
<head>
    <title>Test</title>
</head>

<body>
    <h1>Testing insert_availability_block()</h1>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../Query/Availability.php";

//  Database connection
//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
if (!($CONNECTION = pg_connect("host=db.cecs.pdx.edu port=5432 dbname=simca user=simca password=hk8#9Yyced"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
$_SESSION['PHP_AUTH_USER'] = "simca";

echo '<p>There will be several warnings about conflicting entries, if you want to try to see new inserts modify the inputs of the test cases</p>';

$kwargs2 = array(
    "student_username" => 'sim6'
);

$kwargs3 = array(
    "student_id" => 3
);

//testing whether student_id overrides student_username correctly
$kwargs4 = array(
    "student_username" => 'simca',
    "student_id" => 3
);
//  Caller is responsible for form initialization
$add1 = insert_availability_block(1,'Monday',10,'Available');
$add2 = insert_availability_block(1,'Wednesday',12,'Available',$kwargs2);
$add3 = insert_availability_block(1,'Wednesday',12,'Available',$kwargs3);
$add4 = insert_availability_block(2,'Friday',16,'Preferred',$kwargs3);

//  Just a basic echo to show that the data was gathered and submitted
$result = pg_query($CONNECTION, "SELECT * FROM hour_block;");
$table = pg_fetch_all($result);

echo "<h2>Table after inserts</h2>";
echo "<table>";
echo "<tr>";
echo "<td>student_id</td>";
echo "<td>term_id</td>";
echo "<td>block_day</td>";
echo "<td>block_hour</td>";
echo "<td>block_preference</td>";
echo "</tr>";
foreach ($table as $row) {
    echo "<tr>";
    foreach($row as $key => $val) {
        echo "<td>";
        echo $val;
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";


?>
</body>
</html>
