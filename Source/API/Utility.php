<?php
/*

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






********************************************************************************



MIT License (MIT)

Copyright (c) 2016 Capstone CAT Team

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../Query/Term.php";

//  Creates a dropdown menu with a list of terms
//  and returns an associative array of that terms data fields from
//  the database, otherwise FALSE.
//  PARAMETERS:
//      subIdent:   identifier of submission button
//      kwargs:     associative array of keyword arguments to change functionality,
//                  this is also passed as a parameter to the internal query, see
//                  Source/Query/Term.php::term_retrieve_visible_by_start for more
//                  information
//          view_only_alert:    if the dropdown should indicate terms that are
//                              non-editable, default false
//          all_terms:          if the dropdown should also display non visible terms
//  OTHER:
//      See test/test_Dropdown_student_term.php for an example of use
function dropdown_select_term($subIdent, $kwargs=null) {
    //  This variable ensures that multiple calls in
    //  the same form or file won't interfere
    static $id = 0;
    $id++;

    //  Default values
    $view_only_alert = false;
    $all_terms = false;

    //  Read from kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['view_only_alert'])) {
            $view_only_alert = $kwargs['view_only_alert'];
        }
        if (isset($kwargs['all_terms'])) {
            $all_terms = $kwargs['all_terms'];
        }
    }

    //  First we gather the terms and convert them to an array
    if ($all_terms) {
      if (!($result = term_retrieve_by_start($kwargs))) {
        return false;
      } else if (!($terms = pg_fetch_all($result))) {
        return false;
      }
    } else {
      if (!($result = term_retrieve_visible_by_start($kwargs))) {
          return false;
      } else if (!($terms = pg_fetch_all($result))) {
          return false;
      }
    }

    //  Output HTML for the dropdown menu using our gathered terms
    //  The caller must provide the form initialization and
    //  submission button/object
    echo "<select class=\"form-control\" name=\"formTerm" . $id . "\">\n";
    echo "<option value=\"\">Please select a term...</option>\n";
    foreach ($terms as $term) {
        if ($view_only_alert and $term['editable'] == "f") {
            echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . " -- VIEW ONLY</option>\n";
        } else {
            echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . "</option>\n";
        }
    }
    echo "</select>\n";

    //  Check to see if the user has selected a term, and then
    //  return the array holding the data
    if (isset($_POST[$subIdent])) {
        $selected = $_POST['formTerm' . $id];

        if (!empty($selected)) {
            $query = "SELECT * FROM term
            WHERE term_id=$1
            LIMIT 1";
            if (!($result = pg_query_params($GLOBALS['CONNECTION'], $query, array($selected)))) {
                return false;
            }
            $term = pg_fetch_array($result);
            return $term;
        }
    }
}

//  Creates a dropdown menu with a list of students with availability submissions
//  for a specified termand returns an associative array of that students data
//  fields from the database, otherwise FALSE.
//  PARAMETERS:
//      subIdent:   identifier of submission button
//      termId:     id of term to search by
//      kwargs:     associative array of keyword arguments to change functionality,
//                  this is also passed as a parameter to the internal query, see
//                  Source/Query/Student.php::retrieve_students_with_availability for more
//                  information
function dropdown_select_student($subIdent, $termID, $kwargs = null) {
    //  This variable ensures that multiple calls in
    //  the same form or file won't interfere
    static $id = 0;
    $id++;

    //  First we gather the students and convert them to an array
    if (!($result = retrieve_students_with_availability($termID, $kwargs))) {
        return false;
    } else if (!($students = pg_fetch_all($result))) {
        return false;
    }

    //  Output HTML for the dropdown menu using our gathered students
    //  The caller must provide the form initialization and
    //  submission button/object
    echo "<select class=\"form-control\" name=\"formStudent" . $id . "\">\n";
    echo "<option value=\"\">Select a student to focus on...</option>\n";
    foreach ($students as $student) {
       echo "<option value=" . $student['student_id'] . ">" . $student['student_username'] . "</option>\n";
    }
    echo "</select>\n";

    //  Check to see if the user has selected a student, and then
    //  return the array holding the data
    if (isset($_POST[$subIdent])) {
        $selected = $_POST['formStudent' . $id];

        if (!empty($selected)) {
            $query = "SELECT * FROM student
            WHERE student_id=$1
            LIMIT 1";
            if (!($result = pg_query_params($GLOBALS['CONNECTION'], $query, array($selected)))) {
                return false;
            }
            $student = pg_fetch_array($result);
            return $student;
        }
    }
}


// uses the fidoconfig.ini in the dir root to set the connection data
function fido_db_connect() {
    //  read the configuration file
    $ini_arr = parse_ini_file(dirname(__FILE__) . "/../../fidoconfig.ini", true);
    $dbconf = $ini_arr['database'];

    //  generate the connection string
    $conn = "
    host='{$dbconf['host']}'
    port='{$dbconf['port']}'
    dbname='{$dbconf['dbname']}'
    user='{$dbconf['user']}'
    password='{$dbconf['password']}'";

    //  connect to the database
    return pg_connect($conn);
}

// deprecated function to use cody's db
function cody_db_connect() {
	//  Cody's DB Connection Info
    $host = "capstonecatteam.hopto.org";
    $port = "5432";
    $database = "Cat";
    $username = "guest";
    $password = "Fido";

	//  Generate connection string
    $conn_string = "host={$host} port={$port} dbname={$database} user={$username} password={$password}";

    return pg_connect($conn_string);
}

// given a term check if it is editable by id
//        PARAMETERS:
//             - term id
//
function is_term_editable($termid) {
    $kwargs = array(
        'term_id' => $termid
    );

    $result = term_retrieve_editable_terms($kwargs);
    $ret = true;
    if ($result && pg_num_rows($result) == 0) {
        $ret = false;
    }

    return $ret;
}
?>
