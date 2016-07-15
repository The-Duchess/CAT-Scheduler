<?php

// This script takes input from a form that collects the following params
// PARAMETERS:
// 		name - string
// 		start - datetime
// 		end - datetime
// 		due - datetime
// to use have your form use
// form action="add_time_blocks_submit.php" method="post"
// onclick="test()"
// this will pass the $_POST and check it it was setup and then run the term add
// then you will get back the return value

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "../../Query/term.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

function test() {
	if (isset($_POST[])) {
		return submit();
	}
	else {
		echo "<p>Data Submitting Failed</p>\n";
		exit();
	}
}

function submit() {
	add_term($_POST["name"], $_POST["start"], $_POST["end"], $_POST["due"]);
}

?>