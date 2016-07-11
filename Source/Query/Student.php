<?php

// deactivate a student in the students table
// set the Visible value to false
// all other values can be default and are not required to change
function deactivate_student($id) {


	//Throw an error if student does not exist in the data base
	
	$query = "SELECT * FROM student WHERE student_id = '$id'";
	$result = pg_query($GLOBALS['CONNECTION'], $query);
	if(pg_num_rows($result) == 0) {
	    echo "Student Does not exist<br>";
	    return false;
	}
	//Throw an error if the student is already activated
	$query = "SELECT * FROM student WHERE student_id = '$id' AND visible is FALSE";
	$result = pg_query($GLOBALS['CONNECTION'], $query);
	if (pg_num_rows($result) != 0) {
	    echo "Student is already deactivated<br>";
	    return false;
	}
	//The only value is chanhing is the visibily since we are going to keep all
	//of the student information
	$query = "UPDATE student SET visible = false WHERE student_id = '$id'";

	return pg_query($GLOBALS['CONNECTION'], $query);
}

// --

// add a student to the students table
// set id, first name, last name and email
// all other values can be default and are not required for entry
function add_student($id, $firstname, $lastname, $email) {

	// values assumed to be default
	// - cat nick
	// - join / leave date (leave may not be available)
	// - visible should be defaulted to yes
	$query = 'INSERT into Students (Student_id, Student_FirstName, Student_LastName, Student_Email) VALUES ($1, $2, $3, $4)';

	return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $firstname, $lastname, $email));
}

// --

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

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	function edit_student_firstname($id, $new) {
		// this will edit the student's first name

		$query = 'UPDATE Student SET Student_FirstName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	function edit_student_lastname($id, $new) {
		// this will edit the student's last name

		$query = 'UPDATE Student SET Student_LastName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	function edit_student_nick($id, $new) {
		// this will edit the student's cat nick

		$query = 'UPDATE Student SET Cat_Nickname=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	function edit_student_visible($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET Visible=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

?>