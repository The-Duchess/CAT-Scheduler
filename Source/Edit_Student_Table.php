<?php

	// update a student's info
	// this provides a number of functions to alter a specific student's value other than their primary key
	// and the selection is done by the primary key
	function edit_student_email($id, $new) {
		// this will edit the student's email

		$query = 'UPDATE Student SET Student_Email=$new WHERE Student_id=$id';

		return pg_query($GLOBALS['CONNECTION'], $query);
	}

	function edit_student_firstname($id, $new) {
		// this will edit the student's first name

		$query = 'UPDATE Student SET Student_FirstName=$new WHERE Student_id=$id';

		return pg_query($GLOBALS['CONNECTION'], $query);
	}

	function edit_student_lastname($id, $new) {
		// this will edit the student's last name

		$query = 'UPDATE Student SET Student_LastName=$new WHERE Student_id=$id';

		return pg_query($GLOBALS['CONNECTION'], $query);
	}

	function edit_student_nick($id, $new) {
		// this will edit the student's cat nick

		$query = 'UPDATE Student SET Cat_Nickname=$new WHERE Student_id=$id';

		return pg_query($GLOBALS['CONNECTION'], $query);
	}

	function edit_student_visible($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET Visible=$new WHERE Student_id=$id';

		return pg_query($GLOBALS['CONNECTION'], $query);
	}

?>