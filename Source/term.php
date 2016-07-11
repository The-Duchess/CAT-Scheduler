<?php

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
        $start = (new DateTime("now"))->modify("-1 year")->format("Y-m-d");
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term WHERE start_date >= $1 ORDER BY start_date " . ($ascend ? "ASC" : "DESC");
    $params = array($start);

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
//          start:  the start date to compare to, default 1 year before now
//          ascend: if the results should be in ascending order, default false
//          limit:  the max number of terms to retrieve, default none (null)
function term_retrieve_visible_by_start($kwargs=null) {
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
        $start = (new DateTime("now"))->modify("-1 year")->format("Y-m-d");
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term WHERE visible IS true AND start_date >= $1 ORDER BY start_date " . ($ascend ? "ASC" : "DESC");
    $params = array($start);

    //  Add limit clause and limit param if necessary
    if ($limit) {
        $query .= " LIMIT $2";
        array_push($params, $limit);
    }
    
    //  Return the results.
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);

}


//  Updates a term in the database, returns FALSE on a failure
//  PARAMETERS:
//      id:     the id of the term to update
//      fields: an associative array of db data fields and their
//              updated values. only fields passed as keys in
//              this array will be updated
//      check:  whether to verify there are no invalid fields in
//              the fields parameter, small hit to performance.
//              default TRUE
function term_update($id, $fields, $check=true) {
    //  Check that all fields are valid if required
    if ($check) {
        $valid_fields = array("term_name", "start_date", "end_date", "due_date", "visible", "editable");
        foreach ($fields as $field => $val) {
            if (!in_array(strtolower($field), $valid_fields)) {
                return false;
            }
        }
    }

    //  Generate the query and its parameters
    $field_arr = array();
    $params = array($id);
    $counter = 2;
    foreach ($fields as $field => $val) {
        array_push($field_arr, $field . "=$" . $counter);
        array_push($params, $val);
        $counter++;
    }
    $query = "UPDATE Term SET " . implode(", ", $field_arr) . " WHERE term_id=$1";
    echo $query . "\n";
    foreach ($params as $param) {
        echo $param . "\n";
    }

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}
?>
