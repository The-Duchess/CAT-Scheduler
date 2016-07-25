<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../API/Utility.php";
require_once dirname(__FILE__) . "/../Query_retrieve_student_info.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs2 = array(
    "start" => ((new DateTime("2016-08-01"))->format("Y-m-d")));
$kwargs3 = array(
    "ascend" => true);

//  Caller is responsible for form initialization
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";

$info = retrieve_student_info(2);
//$result = pg_fetch_row($info);
echo "<p>". pg_num_rows($info) ."</p>";

//while($result = pg_fetch_row($info))
for($i = 0; $i < pg_num_rows($info); $i++)
{
	$result = pg_fetch_row($info);
	echo "<p>". $result[0] . $result[1] ."</p>";
}


?>
</body>
</html>
