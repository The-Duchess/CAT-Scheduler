<html>
<head>
    <title>Test</title>
</head>

<body>
    <h1>Testing Update_availability_blocks()</h1>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../Query/Availability.php";

//  Database connection
//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
$_SESSION['PHP_AUTH_USER'] = "ealkadi";

$kwargs2 = array(
    "student_username" => 'ealkadi'
);


//  Caller is responsible for form initialization
$input_blocks = array();
array_push($input_blocks, array("block_day" => 'Monday', "block_hour" => 13, "block_preference"=>'Preferred'));
array_push($input_blocks, array("block_day" => 'Monday', "block_hour" => 14, "block_preference"=>'Preferred'));
array_push($input_blocks, array("block_day" => 'Monday', "block_hour" => 15, "block_preference"=>'Preferred'));
array_push($input_blocks, array("block_day" => 'Wednesday', "block_hour" => 16, "block_preference"=>'Available'));
array_push($input_blocks, array("block_day" => 'Friday', "block_hour" => 12, "block_preference"=>'Available'));

if(update_availability_blocks(1, $input_blocks, $kwargs2) != true){
	echo "Something went wrong.";
}


//  Just a basic echo to show that the data was gathered and submitted
$result = pg_query($CONNECTION, "SELECT * FROM hour_block ORDER BY student_id DESC, term_id DESC;");
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
