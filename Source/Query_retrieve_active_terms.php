<?php

//  Returns a results object containing the names and unique ids of
//  visible and editable terms in the database if successful,
//  otherwise FALSE
function retrieve_active_terms($kwargs=null) {
    //  We want to retrieve a list of all terms that are both visibile
    //  and editable
    $query = 'SELECT term_name, term_id FROM Term ' .
             'WHERE visible IS true AND editable IS TRUE';

    return pg_query($GLOBALS['CONNECTION'], $query);
}

?>
