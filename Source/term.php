<?php

//  Retrieves a results object containing all terms regardless of visibility
//  or editability, otherwise false
//  parameters:
//      order_by:   what field(s) to order the results by, default term_id
//      ascend:     if the results should be in ascending order, default false
//      limit:      the max number of terms to retrieve, default none (null)
function term_retrieve_all($order_by="term_id", $ascend=false, $limit=null) {
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
//  parameters:
//      order_by:   what field(s) to order the results by, default term_id
//      ascend:     if the results should be in ascending order, default false
//      limit:      the max number of terms to retrieve, default none (null)
function term_retrieve_visible_all($order_by="term_id", $ascend=false, $limit=null) {
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
//      start:  the start date to compare to, default 1 year before now
//      ascend: if the results should be in ascending order, default false
//      limit:  the max number of terms to retrieve, default none (null)
function term_retrieve_by_start($start=null, $ascend=false, $limit=null) {
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
//      start:  the start date to compare to, default 1 year before now
//      ascend: if the results should be in ascending order, default false
//      limit:  the max number of terms to retrieve, default none (null)
function term_retrieve_visible_by_start($start=null, $ascend=false, $limit=null) {
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

?>
