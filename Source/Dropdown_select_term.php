<?php

error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "Query_retrieve_active_terms.php";

//  Creates a dropdown menu with a list of visible and editable terms
//  and returns an associative array of that terms data fields from
//  the database, otherwise FALSE.
//  PARAMETERS:
//      $subIdent is the identifying name of the submission button
//  OTHER:
//      See test/test_Dropdown_student_term.php for an example of use
function dropdown_select_term($subIdent) {
    //  This variable ensures that multiple calls in
    //  the same form or file won't interfere
    static $id = 0;
    $id++;

    //  First we father the terms and convert them to an array
    if (!($result = retrieve_active_terms())) {
        return false;
    } else if (!($terms = pg_fetch_all($result))) {
        return false;
    }

    //  Output HTML for the dropdown menu using our gathered terms
    //  The caller must provide the form initialization and
    //  submission button/object
    echo "<select name=\"formTerm" . $id . "\">\n";
    echo "<option value=\"\">Please select a term...</option>\n";
    foreach ($terms as $term) {
        echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . "</option>\n";
    }
    echo "</select>\n";

    //  Check to see if the user has selected a term, and then
    //  return the array holding the data
    if (isset($_POST[$subIdent])) {
        $selected = $_POST['formTerm' . $id];

        if (!empty($selected)) {
            $query = 'SELECT * FROM Term ' .
                     'WHERE term_id=' . $selected . ' ' .
                     'LIMIT 1';
            if (!($result = pg_query($GLOBALS['CONNECTION'], $query))) {
                return false;
            }
            $term = pg_fetch_array($result);
            return $term;
        }
    }
}

?>