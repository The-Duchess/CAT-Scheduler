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

//  Retrieves a results object containing all terms regardless of visibility
//  or editability, otherwise false
//  PARAMETERS:
//      kwargs: associative array of keyword arguments
//          order_by:   what field(s) to order the results by, default term_id
//          ascend:     if the results should be in ascending order, default false
//          limit:      the max number of terms to retrieve, default none (null)
function term_retrieve_all($kwargs=null) {
    //  Default values
    $order_by = "term_id";
    $ascend = false;
    $limit = null;

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['order_by'])) {
            $order_by = $kwargs['order_by'];
        }
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term ORDER BY $1 " . ($ascend ? "ASC" : "DESC");
    $params = array($order_by);

    //  Add limit clause and limit param if necessary
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return the results.
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);

}


//  Retrieves a results object containing all terms regardless of editability,
//  otherwise false
//  PARAMETERS:
//      kwargs: associative array of keyword arguments
//          order_by:   what field(s) to order the results by, default term_id
//          ascend:     if the results should be in ascending order, default false
//          limit:      the max number of terms to retrieve, default none (null)
function term_retrieve_visible_all($kwargs=null) {
    //  Default values
    $order_by = "term_id";
    $ascend = false;
    $limit = null;

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['order_by'])) {
            $order_by = $kwargs['order_by'];
        }
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term WHERE visible IS true ORDER BY $1 " . ($ascend ? "ASC" : "DESC");
    $params = array($order_by);

    //  Add limit clause and limit param if necessary
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return the results.
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);

}

//  Retrieves a results object containing terms that are not currently editable
//  PARAMETERS:
//      kwargs: associative array of keyword arguments
//          term_id: search for a specific term by id instead of a general list
function term_retrieve_editable_terms($kwargs=null) {

    $query = "SELECT * FROM Term WHERE editable IS true";
    $params = array();

    if (isset($kwargs['term_id'])) {
        $query .= " AND term_id = $1";
        array_push($params, $kwargs['term_id']);
    }

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

//  Retrieves a results object containing all terms that start before
//  or at a certain date, otherwise FALSE
//  PARAMETERS:
//      kwargs: associative array of keyword arguments
//          start:  the start date to compare to, default 1 year before now
//          ascend: if the results should be in ascending order, default false
//          limit:  the max number of terms to retrieve, default none (null)
function term_retrieve_by_start($kwargs=null) {
    //  Default values
    $start = null;
    $ascend = false;
    $limit = null;

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['start'])) {
            $start = $kwargs['start'];
        }
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Initialize start to default value if not passed
    if (!$start) {
        $start = (new DateTime("now"))->modify("-1 year");
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term WHERE start_date >= $1 ORDER BY start_date " . ($ascend ? "ASC" : "DESC");
    $params = array($start->format("Y-m-d"));

    //  Add limit clause and limit param if necessary
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return the results.
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);

}


//  Retrieves a results object containing all terms that start before
//  or at a certain date and are visible, otherwise FALSE
//  PARAMETERS:
//      kwargs: associative array of keyword arguments
//          start:      the start date to compare to, default 1 year before now
//          ascend:     if the results should be in ascending order, default false
//          editable:   if the returned terms should require being editable, default false
//          limit:      the max number of terms to retrieve, default none (null)
function term_retrieve_visible_by_start($kwargs=null) {
    //  Default values
    $start = null;
    $ascend = false;
    $editable = false;
    $limit = null;
    $where = array("visible IS true", "start_date >= $1");

    //  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['start'])) {
            $start = $kwargs['start'];
        }
        if (isset($kwargs['ascend'])) {
            $ascend = $kwargs['ascend'];
        }
        if (isset($kwargs['editable'])) {
            $editable = $kwargs['editable'];
        }
        if (isset($kwargs['limit'])) {
            $limit = $kwargs['limit'];
        }
    }

    //  Initialize start to default value if not passed
    if (!$start) {
        $start = (new DateTime("now"))->modify("-1 year");
    }

    if ($editable) {
        array_push($where, "editable IS true");
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM term WHERE " . implode(" and ", $where) . "ORDER BY start_date " . ($ascend ? "ASC" : "DESC");
    $params = array($start->format("Y-m-d"));

    //  Add limit clause and limit param if necessary
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }

    //  Return the results.
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);

}


//  Updates a term in the database.  Returns TRUE on success, otherwise FALSE
//  PARAMETERS:
//      id:     the id of the term to update
//      fields: an associative array of db data fields and their
//              updated values. only fields passed as keys in
//              this array will be updated
//      check:  whether to verify there are no invalid fields in
//              the fields parameter, small hit to performance.
//              default TRUE
//  EXCEPTIONS:
//      Will raise an exception containing an error message if an error occurs
//      before querying the database.
//  RETURN VALUES:
//      true:   success
//      false:  failure
function term_update($id, $fields) {
    //  Check for validity
    $check_string = function ($var) { return ((is_string($var)) ? true : false); };
    $check_DateTime = function ($var) { return (($var instanceof DateTime) ? true : false); };
    $check_boolean = function ($var) { return ((is_bool($var)) ? true : false); };
    $valid_fields = array(
        "term_name" => $check_string,
        "start_date" => $check_DateTime,
        "end_date" => $check_DateTime,
        "due_date" => $check_DateTime,
        "visible" => $check_boolean,
        "editable" => $check_boolean,
        "mentoring" => $check_boolean
    );
    foreach ($fields as $field => $val) {
        if (!is_string($field)) {
            throw new Exception("ERROR: non-string used as index for fields array");
        } else if (!array_key_exists(strtolower($field), $valid_fields)) {
            throw new Exception("ERROR: {$field} is not a valid field");
        } else if (is_null($val)) {
            throw new Exception("ERROR: {$field} cannot be assigned to null");
        } else if (!($valid_fields[$field]($val))) {
            throw new Exception("ERROR: incorrect type assigned to {$field} field");
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
    $query = "UPDATE term SET {$assignments} WHERE term_id=\${$counter}";

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

// --

//  adds a term to the database
//        PARAMETERS:
//             - name
//             - start date
//             - end date
//             - due date
//             - mentor session
//  Returns result object of query if successful, FALSE otherwise
function add_term($name, $start, $end, $due, $mentor=false) {
    //  Assumes we want the default values for visible and editable fields
    $query = "INSERT INTO term (term_name, start_date, end_date, due_date, mentoring) VALUES($1, $2, $3, $4, $5)";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($name, $start->format("Y-m-d"), $end->format("Y-m-d"), $due->format("Y-m-d"), var_export($mentor, true)));
}

// deactivate a student in the students table
// set the Visible value to false
// all other values can be default and are not required to change
//        PARAMETERS:
//             - student id
//
function deactivate_term($id) {

    //Throw an error if student does not exist in the data base
    //  $query = "SELECT * FROM term WHERE term_id = '$id'";
    $query = "SELECT * FROM term WHERE term_id=$1";
    //  $result = pg_query($GLOBALS['CONNECTION'], $query);
    $result = pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
    if(pg_num_rows($result) == 0) {
        echo "Term Does not exist<br>";
        return false;
    }

    //Throw an error if the student is already activated
    //  $query = "SELECT * FROM term WHERE term_id = '$id' AND editable is FALSE";
    $query = "SELECT * FROM term WHERE term_id=$1 AND editable is FALSE";
    //  $result = pg_query($GLOBALS['CONNECTION'], $query);
    $result = pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
    if (pg_num_rows($result) != 0) {
        echo "term is already deactivated<br>";
        return false;
    }
    //The only value is chanhing is the visibily since we are going to keep all
    //of the student information
    //  $query = "UPDATE term SET editable = false WHERE term_id = '$id'";
    $query = "UPDATE term SET editable=false WHERE term_id=$1";

    //  return pg_query($GLOBALS['CONNECTION'], $query);
    return pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
}

// performs a query to get a term row by id
//        PARAMETERS:
//             - term id
function term_retrieve_by_id($id) {
    $query = "SELECT * FROM term WHERE term_id=$1 LIMIT 1";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
}


?>
