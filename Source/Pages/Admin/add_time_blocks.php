<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>


<?php


error_reporting(E_ALL);
ini_set('display_errors', 'on');
include "../Query/term.php";

//  Database connection
/*
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}
*/

$s_time = "";
$e_time = "";
$name_v = "";
$due_v  = "";

echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">
Name: <input type="text" name="name_v"><br>
<input type="submit">
</form>";


$name_v = $_POST["name_v"];

// echo "$name_v";

?>

title: <?php echo ($_POST["name_v"]); ?>

</body>
</html>
