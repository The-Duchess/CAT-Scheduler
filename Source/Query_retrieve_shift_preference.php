<?php

// retrieves the shift preference for a specific student by a specific term
function retrieve_shift_preference($studentid, $termid) {

	//Selecet the shift preference from the shift preference table when
	//student Id matches the entered Id and term Id matches the term ID
	$query = "SELECT shift_preference FROM shift_preference WHERE student_id = '$studentid' AND term_id = '$termid'";
    
    return pg_query($GLOBALS['CONNECTION'], $query);
}

?>
