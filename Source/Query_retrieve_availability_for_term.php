<?php

include "Query/Availability.php";

error_reporting(E_ALL);
ini_set('display_errors', 'on');
function retrieve_availability_for_term($term_id, $kwargs=null) {
	// Default values
	if (!is_int($term_id)) {
		return false;
	}

	//get the list of all studnet ids
	$list = "SELECT student_id FROM student";
	$students = pg_query_params($GLOBALS['CONNECTION'], $list, array());
	
	//fetch student ids one by one and then get their availability
	//save the avaialabity in an array
	for($i=0; $i < pg_num_rows($students); $i++)
	{
	    $student = pg_fetch_row($students);
	    $studentid = (int) $student[0];
	    $query[$studentid] = retrieve_availability_for_student($studentid, $term_id);        
	}
    //return the array of the availabilities
    return $query;
}
?>
