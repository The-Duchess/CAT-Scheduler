<?php

// add the shift preference for a student
function add_student_shift_preference($student_id, $term_id, $pref) {

	// student_id, term_id and shift_preference for a student's shift pref
	$query = 'INSERT into Shift_Preference (student_id, term_id, shift_preference) VALUES ($1, $2, $3)';

	return pg_query_params($GLOBALS['CONNECTION'], $query, array($student_id, $term_id, $pref));
}

?>
