<?php

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