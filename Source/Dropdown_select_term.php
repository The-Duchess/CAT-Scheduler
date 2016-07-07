<?php

include "Query_retrieve_active_terms.php";

//  Displays a dropdown menu with a list of visible and editable terms
//  and returns an associatie array of that terms data fields from
//  the database, otherwise FALSE
function dropdown_select_term() {
    //  First we get gather the terms and convert them to an array
    if (!($result = retrieve_active_terms())) {
        return false;
    } else if (!($terms = pg_fetch_all($result))) {
        return false;
    }


    //  Output HTML for the dropdown menu with our gathered terms
    echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
    echo "<label for=\"formTerm\">Select a Term</label><br>\n";
    echo "<select name=\"formTerm\">\n";
    echo "<option value=\"\">Please select a term...</option>\n";
    foreach ($terms as $term) {
        echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . "</option>\n";
    }
    echo "</select>\n";
    echo "<input type=\"submit\" name=\"formSubmit\" value=\"Select\" />\n";
    echo "</form>\n";


    //  Check to see if the user has selected a term, and then
    //  return the array holding the data
    if (isset($_POST['formSubmit'])) {
        $selected = $_POST['formTerm'];

        if (!empty($selected)) {
            $query = 'SELECT * FROM Term ' .
                     'WHERE term_id=' . $selected . " " .
                     'LIMIT 1';
            if (!($result = pg_query($GLOBALS['CONNECTION'], $query))) {
                return false;
            }
            if (!($ret = pg_fetch_array($result))) {
                return false;
            }
            
            return $ret;
        }
    }
}

?>
