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
        $start = (new DateTime("now"))->modify("-1 year");
    }

    //  Create the query and querys params without limit clause
    $query = "SELECT * FROM Term WHERE visible IS true AND start_date >= $1 ORDER BY start_date " . ($ascend ? "ASC" : "DESC");
    $params = array($start->format("Y-m-d"));

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
function term_update($id, $fields) {
    //  Check for validity
    if (!is_int($id)) {
        return false;
    }
    $check_string = function ($var) { return ((is_string($var)) ? true : false); };
    $check_DateTime = function ($var) { return (($var instanceof DateTime) ? true : false); };
    $check_boolean = function ($var) { return ((is_bool($var)) ? true : false); };
    $valid_fields = array(
        "term_name" => $check_string,
        "start_date" => $check_DateTime,
        "end_date" => $check_DateTime,
        "due_date" => $check_DateTime,
        "visible" => $check_boolean,
        "editable" => $check_boolean);
    foreach ($fields as $field => $val) {
        if (!is_string($field) or
            !array_key_exists(strtolower($field), $valid_fields) or
            is_null($val) or
            !($valid_fields[$field]($val))) {
            return false;
        }
    }

    //  Generate the query and its parameters
    $field_arr = array();
    $params = array($id);
    $counter = 2;
    foreach ($fields as $field => $val) {
        array_push($field_arr, $field . "=$" . $counter);
        if ($val instanceof DateTime) {
            array_push($params, $val->format("Y-m-d"));
        } else if (is_bool($val)) {
            array_push($params, var_export($val, true));
        } else {
            array_push($params, $val);
        }
        $counter++;
    }
    $query = "UPDATE Term SET " . implode(", ", $field_arr) . " WHERE term_id=$1";

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

// --

//  Returns result object of query if successful, FALSE otherwise
function add_term($name, $start, $end, $due) {
    //  Assumes we want the default values for visible and editable fields
    $query = 'INSERT into Terms (term_name, start_date, end_date, due_date) VALUES($1, $2, $3, $4)';

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($name, $start->format("Y-m-d"), $end->format("Y-m-d"), $due->format("Y-m-d")));
}

?>
