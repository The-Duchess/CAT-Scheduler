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

require_once dirname(__FILE__) . "/../../Query/Availability.php";
require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//this function handles the formattiong of the submitted form to be fed into the update availbility functions
function process_submissions() {

    //prepare the variables
    $student_uname = $_SERVER['PHP_AUTH_USER'];
    $res = get_student_id_by_username($student_uname);
    $arr = pg_fetch_array($res);
    $student_id = $arr['student_id'];
    $input_term_id = $_POST['term_id'];
    $pref = "";
    $input_blocks = array();

    foreach ($_POST as $key => $val) {

        // other than these four, the values in post will be availability submissions, these need to be handled differently
        if ($key == "term_name" || $key == "term_id" || $key == "shift_pref" || $key == "Submit") {
            // do nothing if the key is term_id, shift_pref, or Submit
            if ($key == "shift_pref") {
                $pref = $val;
            }
        } else {
            // determine split point on $key to get day and number
            // split $key into day + hour
            // get term_id
            // get input pref
            // get student_id

            // this is sort of a hack
            // TODO: if it is possible make this clean
            $pos = strpos($key, 'y');
            $input_day     = substr($key, 0, ($pos + 1));
            $input_hour    = (int)substr($key, ($pos + 1), strlen($key));
            $input_pref    = $val;
            $args          = array("student_id" => $student_id);

            //create input array for the update availability script
            if ($val == "A") {
                array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Available'));
            } elseif ($val == "P") {
                array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Preferred'));
            } else {
                // do nothing
            }

        }

    }

    //return false if either of the availability updates to the database fail
    if(!update_availability_blocks($input_term_id, $input_blocks)){
        return false;
    }

    if(!add_student_shift_preference($student_id, $input_term_id, $pref)) {
        return false;
    }

    return true;
}

?>
