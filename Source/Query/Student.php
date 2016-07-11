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

?>