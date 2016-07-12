<?php

// add a student to the students table
// set id, first name, last name and email
// all other values can be default and are not required for entry
function add_student($id) {

	// values assumed to be default
	// - cat nick
	// - join / leave date (leave may not be available)
	// - visible should be defaulted to yes
	$query = 'INSERT into Students (Student_id) VALUES ($1)';

	return pg_query_params($GLOBALS['CONNECTION'], $query, array($id));
}

?>
