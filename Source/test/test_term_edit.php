<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../term.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

$kwargs = array(
    "start_date" => (new DateTime("2012-10-10")),
		"end_date" => (new DateTime("2014-10-10")),
		"due_date" => (new DateTime("2016-10-10")),
    "term_name" => "test term2",
    "visible" => True,
    "editable" => True);


$kwargs2 = array(
    "start_date" => null,
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => False,
    "editable" => False);
		
$kwargs3 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => null,
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => False,
    "editable" => False);

$kwargs4 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => null,
    "term_name" => "test",
    "visible" => False,
    "editable" => False);

$kwargs5 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => null,
    "visible" => False,
    "editable" => False);

$kwargs6 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => null,
    "editable" => False);

$kwargs7 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => False,
    "editable" => null);	
		
$kwargs8 = array(
    "start_date" => "test",
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => False,
    "editable" => False);

$kwargs9 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => 42,
    "visible" => False,
    "editable" => False);
		
$kwargs10 = array(
    "start_date" => (new DateTime("2000-10-10")),
    "end_date" => (new DateTime("2000-10-10")),
    "due_date" => (new DateTime("2000-10-10")),
    "term_name" => "test",
    "visible" => (new DateTime("2000-10-10")),
    "editable" => False);

//Only the first update should work
term_update(11, $kwargs);
term_update(11, $kwargs2);
term_update(11, $kwargs3);
term_update(11, $kwargs4);
term_update(11, $kwargs5);
term_update(11, $kwargs6);
term_update(11, $kwargs7);
term_update(11, $kwargs8);
term_update(11, $kwargs9);
term_update(11, $kwargs10);
?>
</body>
</html>
