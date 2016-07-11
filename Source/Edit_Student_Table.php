<?php

	// update a student's info
	// this provides a number of functions to alter a specific student's value other than their primary key
	// and the selection is done by the primary key

	// these functions operate in a generic way where you feed the id of the student
	// that is going to have a value changed and a new value for that column
	// parameters:
	//		id: the student id
	// 		new: the new value

	function edit_student_email($id, $new) {
		// this will edit the student's email

		$query = 'UPDATE Student SET Student_Email=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, $array($id, $new));
	}

	function edit_student_firstname($id, $new) {
		// this will edit the student's first name

		$query = 'UPDATE Student SET Student_FirstName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, $array($id, $new));
	}

	function edit_student_lastname($id, $new) {
		// this will edit the student's last name

		$query = 'UPDATE Student SET Student_LastName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, $array($id, $new));
	}

	function edit_student_nick($id, $new) {
		// this will edit the student's cat nick

		$query = 'UPDATE Student SET Cat_Nickname=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, $array($id, $new));
	}

	function edit_student_visible($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET Student_Visible=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, $array($id, $new));
	}

?>