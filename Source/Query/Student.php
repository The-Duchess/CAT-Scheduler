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






// retrieves a student id based on the give username
// PARAMETERS:
//      input_username: username of the student you want to get the id of
function get_student_id_by_username($input_username) {
    $query = "SELECT student_id FROM student WHERE student_username = $1";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($input_username));
}

// deactivate a student in the students table
// set the Active value to false
// all other values can be default and are not required to change
		//  PARAMETERS:
		//      id: The id of the student you want to set active flag to false
function deactivate_student($id) {
	//Throw an error if student does not exist in the data base
    $query = "SELECT * FROM student WHERE student_id=$1";
    $result = pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
	if(pg_num_rows($result) == 0) {
	    echo "Student Does not exist<br>";
	    return false;
    }
	//Throw an error if the student is already activated
    $query = "SELECT * FROM student WHERE student_id=$1 AND active is FALSE";
    $result = pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
	if (pg_num_rows($result) != 0) {
	    echo "Student is already deactivated<br>";
	    return false;
	}
	//The only value is chanhing is the visibily since we are going to keep all
	//of the student information
    $query = "UPDATE student SET active=false WHERE student_id=$1";
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
}

// add a student to the students table
//
// DEV NOTE: join date might be simply when user is being inserted into db, might want
//           to integrate that directly into this function directly
//
// PARAMETERS:
// 		| - student id
// 		| - student uname
// 		| - student's join time
// TODO: the formatting of Datetime obj to a string should be done outside of this function, refacter ALL queries...
function add_student($student_uname, $joind) {
	$query = 'INSERT into student (student_username, join_date) VALUES ($1, $2)';
	$new_joind = $joind->format("Y-m-d");
	return pg_query_params($GLOBALS['CONNECTION'], $query, array($student_uname, $new_joind));
}
	
//  Updates a student in the database, returns FALSE on a failure
//  PARAMETERS:
//      id:     the id of the student to update
//      fields: an associative array of db data fields and their
//              updated values. only fields passed as keys in
//              this array will be updated
//			"student_username" => string,
//				String to change username to
//			"join_date" => DateTime,
//				Date to set the Join date to
//			"leave_date" => DateTime,
//				Date to set the Leave date to
//			"active" => boolean);
//				Either True or False to set the active flag
//      check:  whether to verify there are no invalid fields in
//              the fields parameter, small hit to performance.
//              default TRUE
function student_update($id, $fields) {
    //  Check for validity
    $check_string = function ($var) { return ((is_string($var)) ? true : false); };
    $check_DateTime = function ($var) { return (($var instanceof DateTime) ? true : false); };
    $check_boolean = function ($var) { return ((is_bool($var)) ? true : false); };
    $valid_fields = array(
        "student_username" => $check_string,
        "join_date" => $check_DateTime,
        "leave_date" => $check_DateTime,
        "active" => $check_boolean);
    foreach ($fields as $field => $val) {
        if (!is_string($field)) {
            throw new Exception("ERROR: non-string used as index for fields array");
        } else if (!array_key_exists(strtolower($field), $valid_fields)) {
            throw new Exception("ERROR: {$field} is not a valid field");
        } else if (is_null($val)) {
            throw new Exception("ERROR: {$field} cannot be assigned to null");
        } else if (!($valid_fields[$field]($val))) {
            throw new Exception("ERORR: incorrect type assigned to {$field} field");
        }
    }
    //  Generate the query and its parameters
    $field_arr = array();
    $params = array();
    $counter = 1;
    foreach ($fields as $field => $val) {
        array_push($field_arr, "{$field}=\${$counter}");
        if ($val instanceof DateTime) {
            array_push($params, $val->format("Y-m-d"));
        } else if (is_bool($val)) {
            array_push($params, var_export($val, true));
        } else {
            array_push($params, $val);
        }
        $counter++;
    }
    array_push($params, $id);
    $assignments = implode(", ", $field_arr);
    $query = "UPDATE student SET {$assignments} WHERE student_id=\${$counter}";
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

//  Retrieves a results object containing all active students with no availabilitiy
//  for the specified term sorted by student username, otherwise FALSE
//  PARAMETERS:
//      id:     the id of the term
//      kwargs: associative array of keyword arguments
//          ascend:     if the results should be in ascending order, default TRUE
//          limit:      the max number of students to retrieve, default none (null)
function retrieve_students_no_availability($id, $kwargs=null) {
    //  Default values
    $ascend = true;
    $limit = null;

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Create basic query
    $query = "
    SELECT student_id, student_username FROM student 
    WHERE active=true AND student_id NOT IN (SELECT student_id FROM hour_block WHERE term_id=$1)
    ORDER BY student_username " . ($ascend ? "ASC" : "DESC");
    $params = array($id);

    //  Add limit clause and parameter if desired
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return results object
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

//  Retrieves a results object containing all active students with availability
//  for the specified term sorted by student username, otherwise FALSE
//  PARAMETERS:
//      id:     the id of the term
//      kwargs: associative array of keyword arguments
//          ascend:     if the results should be in ascending order, default TRUE
//          limit:      the max number of students to retrieve, default none (null)
function retrieve_students_with_availability($id, $kwargs=null) {
    //  Default values
    $ascend = true;
    $limit = null;

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Create basic query
    $query = "
    SELECT student_id, student_username FROM student 
    WHERE active=true AND student_id IN (SELECT student_id FROM hour_block WHERE term_id=$1)
    ORDER BY student_username " . ($ascend ? "ASC" : "DESC");
    $params = array($id);

    //  Add limit clause and parameter if desired
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return results object
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

// retrieves a results object containing all students' username and id pairs constrained by a specific term id
//  PARAMETERS:
//      termid:     the id of the term
function retrieve_student_info($termid) {

    $query = "SELECT student_id, student_username FROM student WHERE student_id IN (SELECT student_id FROM hour_block WHERE term_id=$1)";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($termid));
}

// retrieves a results object containing all students in the student table
function student_retrieve_all() {
    $query = "SELECT * FROM student";

    return pg_query($GLOBALS['CONNECTION'], $query);
}

// retrieves a results object containing the student constrained by a specific student id
//  PARAMETERS:
//      id:     the id of the student
function student_retrieve_by_id($id) {
    $query = "SELECT * FROM student WHERE student_id=$1 LIMIT 1";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
}


?>
