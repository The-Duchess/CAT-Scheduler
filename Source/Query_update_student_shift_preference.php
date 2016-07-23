<?php

// update a student's shift preference
function add_student_shift_preference($student_id, $term_id, $pref) {

	$delete_query = 'DELETE FROM shift_preference where student_id = $1 AND term_id = $2';

	$res = pg_query_params($GLOBALS['CONNECTION'], $delete_query, array($student_id, $term_id));

	$add_query = 'INSERT into Shift_Preference (student_id, term_id, shift_preference) VALUES ($1, $2, $3)';

	$res_ =  pg_query_params($GLOBALS['CONNECTION'], $add_query, array($student_id, $term_id, $pref));

	if ($res_ == "SOME ERROR") {
		pg_query($GLOBALS['CONNECTION'], 'ROLLBACK');
		return false;
	}

	pg_query($GLOBALS['CONNECTION'], "COMMIT");

	return true;
}


?>