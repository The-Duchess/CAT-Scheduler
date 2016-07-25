<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once dirname(__FILE__) . "/../Query/Term.php";

//  Creates a dropdown menu with a list of visible and editable terms
//  and returns an associative array of that terms data fields from
//  the database, otherwise FALSE.
//  PARAMETERS:
//      subIdent:   identifier of submission button
//      kwargs:     associative array of keyword arguments to change functionality,
//                  this is also passed as a parameter to the internal query, see
//                  Source/Query/Term.php::term_retrieve_visible_by_start for more
//                  information
//          view_only_alert:    if the dropdown should indicate terms that are
//                              non-editable, default false
//  OTHER:
//      See test/test_Dropdown_student_term.php for an example of use
function dropdown_select_term($subIdent, $kwargs=null) {
    //  This variable ensures that multiple calls in
    //  the same form or file won't interfere
    static $id = 0;
    $id++;

    //  Default values
    $view_only_alert = false;

    //  Read from kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['view_only_alert'])) {
            $view_only_alert = $kwargs['view_only_alert'];
        }
    }

    //  First we father the terms and convert them to an array
    if (!($result = term_retrieve_visible_by_start($kwargs))) {
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
        if ($view_only_alert and $term['editable']) {
            echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . " -- VIEW ONLY</option>\n";
        } else {
            echo "<option value=" . $term['term_id'] . ">" . $term['term_name'] . "</option>\n";
        }
    }
    echo "</select>\n";

    //  Check to see if the user has selected a term, and then
    //  return the array holding the data
    if (isset($_POST[$subIdent])) {
        $selected = $_POST['formTerm' . $id];

        if (!empty($selected)) {
            $query = "SELECT * FROM term
            WHERE term_id=$1
            LIMIT 1";
            if (!($result = pg_query_params($GLOBALS['CONNECTION'], $query, array($selected)))) {
                return false;
            }
            $term = pg_fetch_array($result);
            return $term;
        }
    }
}

function fido_db_connect() {
	// Cody's Database
    $host = "capstonecatteam.hopto.org";
    $port = "5432";
    $database = "Cat";
    $username = "guest";
    $password="Fido";
	
	// Simca Database
	/*

	$host = "db.cecs.pdx.edu";
    $port = "5432";
    $database = "simca";
    $username = "simca";
    $password="hk8#9Yyced";
	*/
	
	//Connect String
	$conn_string = "host={$host} port={$port} dbname={$database} user={$username} password={$password}";
    return pg_connect($conn_string);
}
?>
