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
