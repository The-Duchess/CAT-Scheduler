<?php

// retrieves an array of students constrained by a specific term
function retrieve_student_info($termid) {

	// find all students who are in the specified term and return an array of their student ids and student usernames
	$query = "SELECT DISTINCT student.student_id, student_username FROM hour_block INNER JOIN student ON hour_block.student_id=student.student_id WHERE hour_block.term_id='$termid'";
    return pg_query($GLOBALS['CONNECTION'], $query);
}

?>