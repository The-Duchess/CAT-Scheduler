<?php

// deactivate a student in the students table
// set the Visible value to false
// all other values can be default and are not required to change
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

?>
