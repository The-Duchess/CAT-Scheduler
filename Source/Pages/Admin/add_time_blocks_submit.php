<!--
Copyright 2016 Cat Capstone Team
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
http://www.apache.org/licenses/LICENSE-2.0
Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
	    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	    See the License for the specific language governing permissions and
	    limitations under the License.
	    Licensed to the Computer Action Team (CAT) under one
	    or more contributor license agreements.  See the NOTICE file
	    distributed with this work for additional information
	    regarding copyright ownership.  The CAT Captstone Team
	    licenses this file to you under the Apache License, Version 2.0 (the
		    "License"); you may not use this file except in compliance
	    with the License.  You may obtain a copy of the License at
	    http://www.apache.org/licenses/LICENSE-2.0
	    Unless required by applicable law or agreed to in writing,
	    software distributed under the License is distributed on an
	    "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
	    KIND, either express or implied.  See the License for the
	    specific language governing permissions and limitations
	    under the License.
	    -->


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
// this will pass the $_POST and check to see if it was setup and then run the term add
// then you will get back the return value

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../../API/Utility.php";
require_once dirname(__FILE__) . "/../../Query/Term.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//Check to see if the $_POST is setup
function test() {
	if (isset($_POST[])) {
		return submit();
	}
	else {
		echo "<p>Data Submitting Failed</p>\n";
		exit();
	}
}

//Run to add the term
function submit() {
	add_term($_POST["name"], $_POST["start"], $_POST["end"], $_POST["due"]);
}

?>
