<html>
<head>
    <title>Test</title>
</head>

<body>
    <h1>Testing add_student()</h1>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../Query/Student.php";

//  Database connection
//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
if (!($CONNECTION = pg_connect("host=db.cecs.pdx.edu port=5432 dbname=simca user=simca password=hk8#9Yyced"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$new_user = 'dog01';
//  Caller is responsible for form initialization
$join_date = (new DateTime("now"))->modify("-1 year");

if (add_student($new_user, $join_date) == false) {
    echo "<p>ERROR: student insertion failed</p>";
}

//  Just a basic echo to show that the data was gathered and submitted
$result = pg_query($CONNECTION, "SELECT * FROM student;");
$table = pg_fetch_all($result);

echo "<h2>Table after inserts</h2>";
echo "<table>";
echo "<tr>";
echo "<td>student_id</td>";
echo "<td>student_username</td>";
echo "<td>join_day</td>";
echo "<td>leave_day</td>";
echo "<td>active</td>";
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

echo "<p>removing inserted user...</p>";

//using pg_
if (pg_query_params($CONNECTION, "DELETE FROM student WHERE student_username = $1", array($new_user)) == false) {
    echo "<p>Could not remove user ".$new_user."</p>";
}

?>
</body>
</html>
