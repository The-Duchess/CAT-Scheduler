<?php

//  Returns result object of query if successful, FALSE otherwise
function add_term($name, $start, $end, $due) {
    //  Assumes we want the default values for visible and editable fields
    $query = 'INSERT into Terms (term_name, start_date, end_date, due_date) VALUES($1, $2, $3, $4)';

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($name, $start, $end, $due));
}

?>
