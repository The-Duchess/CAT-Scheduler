<?php

//  Returns a results object of query if successful, FALSE otherwise
function retrieve_active_terms() {
    //  We want to retrieve a list of all terms that are both visibile
    //  and editable
    $query = 'SELECT term_name, term_id FROM Terms ' .
             'WHERE visible IS true and editable is TRUE';

    return pg_query($CONNECTION, $query);
}

?>
