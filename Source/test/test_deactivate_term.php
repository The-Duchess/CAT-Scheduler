<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../Query_deactivate_term.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$t_id = 1;

$query = "select term_id, editable from term where term_id = $t_id";

$res = pg_query($GLOBALS['CONNECTION'], $query);

print($res);

$res = deactivate_term($t_id);

print($res);

$res = pg_query($GLOBALS['CONNECTION'], $query);

print($res);

?>
</body>
</html>
