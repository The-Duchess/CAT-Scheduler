<?php
//  Retrieves availibility for select term and select student
//  PARAMETERS:
//		student_id: the id of the student to get availability for
//		term_id: the id of the term that is being selected for availability listing
//      kwargs: associative array of keyword arguments
//          block_day: specify if we want only a select day from that term for the student
//          block_hour: specify if we want only a select hour from that term for the student
//				Specifying both will allow for selecting a specific day and time for the student
//
//  NOTE: if specifying kwargs, if specifying both will allow for selecting a specific day and time for the studentstudent_username.
function retrieve_availability_for_student($student_id, $term_id, $kwargs=null) {
	// initializing variables for the query, will take default environment varibles
    // if kwargs arent given
	
	// Return false if two main given parameters are not integers
	if (!is_int($student_id) OR (!is_int($term_id))) {
		return false;
	}

	//Makes sure the day and hour are null and initialized before setting them
	$block_day = null;
	$block_hour = null;
	
	//  Read kwargs if necessary
    if ($kwargs) {
        if (isset($kwargs['block_day'])) {
            $block_day = $kwargs['block_day'];
        }
        if (isset($kwargs['block_hour'])) {
            $block_hour = $kwargs['block_hour'];
        }
    }
	
	//Sets the params for base query
	$params = array($student_id, $term_id);
	//Query will return:
		//Student Username: To display which student is which instead of trying to retrieve username later
		//Term Name: To display current selected term
		//Block Day: Day of availability
		//Block Hour: Hour of availability
		//Block Preference: Either Available or Preferred
			//NOTE: Not available is not in database so it will not be displayed
    $query = "SELECT student_username, term_name, block_day, block_hour, block_preference 
	FROM hour_block, student, term 
	WHERE student.student_id = hour_block.student_id 
	AND term.term_id = hour_block.term_id 
	AND student.student_id = $1
	AND term.term_id = $2";
	
	//Checks if block_day was passed in with the kwargs
	if ($block_day){
		array_push($params, $block_day);
		$query .= " AND block_day = $3";
		echo "<p>Block Day Selected: ". $block_day ."</p>\n";}

	//Checks if block_hour was passed in with the kwargs
	if ($block_hour){
		array_push($params, $block_hour);
		//If the block_day was not passed in we want to use $3 for the hour instead
		if (!$block_day){$query .= " AND block_hour = $3";}
		//Else we will know that both kwargs were passed here and will use $4 for the hour
		else{$query .= " AND block_hour = $4";}
		echo "<p>Block Hour Selected: ". $block_hour ."</p>\n";
}
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}


//  Inserts an availibility block into the Hour_Block table
//  PARAMETERS:
//      input_term_id: the id of the term the availability submission is for
//      input_day: the day of the week the entry is for
//      input_hour: the hour block the entry is for
//      input_pref: specify whether this a 'available' or 'preferred' hour block
//      kwargs: associative array of keyword arguments
//          student_username: specify if want to use a username different from the logged in user
//          student_id: specify if want to use a student id different from the logged in user
//
//  NOTE: if specifying kwargs, if both student_username and student_id are specified, student_id will override student_username.
function insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null) {
    // initializing variables for the query, will take default environment varibles
    // if kwargs arent given
    $student_user = null;
    $student_id = null;
    $term_id = $input_term_id;
    $day = $input_day;
    $hour = $input_hour;
    $pref = $input_pref;
    $params = array();

    // Return false if any of the given parameters are null
    if (empty($day) || empty($hour) || empty($pref)) {
        return false;
    }

    // Use the given username if specified in kwargs, defaulting to session username (if set). null otherwise.
    if (isset($kwargs['student_username'])) {
        $student_user = $kwargs['student_username'];
    } else if (isset($_SESSION['PHP_AUTH_USER'])) {
        $student_user = $_SESSION['PHP_AUTH_USER'];
    } else {
        return false;
    }

    // The table stores a student_id, so we need to extract it from the username if not given
    // in kwargs
    if (isset($kwargs['student_id'])) {
        $student_id = $kwargs['student_id'];
    } else {
        $temp_query = 'SELECT student_id FROM student WHERE student_username = $1';
        $temp = pg_query_params($GLOBALS['CONNECTION'], $temp_query, array($student_user));
        $student_id = pg_fetch_row($temp)[0];
    }
    array_push($params, $student_id);

    array_push($params, $term_id);
    array_push($params, $day);
    array_push($params, $hour);
    array_push($params, $pref);

    $query = 'INSERT INTO hour_block (student_id, term_id, block_day, block_hour, block_preference) VALUES($1,$2,$3,$4,$5)';

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

//  Updates the hour_block table to contain only the hour blocks specified in the input
//  PARAMETERS:
//      input_term_id: the id of the term the availability submission is for
//      input_blocks: array of hour blocks to be used to edit the hour_block table
//      kwargs: associative array of keyword arguments
//          student_username: specify if want to use a username different from the logged in user
//          student_id: specify if want to use a student id different from the logged in user
//
//  NOTE: if specifying kwargs, if both student_username and student_id are specified, student_id will override student_username.
function update_availability_blocks($input_term_id, $input_blocks, $kwargs=null) {
    // initializing variables for the query, will take default environment varibles
    // if kwargs arent given
    $student_user = null;
    $student_id = null;
    $term_id = $input_term_id;
    $blocks = $input_blocks;
    $delete_params = array();


    // Use the given username if specified in kwargs, defaulting to session username (if set). null otherwise.
    if (isset($kwargs['student_username'])) {
        $student_user = $kwargs['student_username'];
    } else if (isset($_SESSION['PHP_AUTH_USER'])) {
        $student_user = $_SESSION['PHP_AUTH_USER'];
    } else {
        return false;
    }

    // The table stores a student_id, so we need to extract it from the username if not given
    // in kwargs
    if (isset($kwargs['student_id'])) {
        $student_id = $kwargs['student_id'];
    } else {
        $temp_query = 'SELECT student_id FROM student WHERE student_username = $1';
        $temp = pg_query_params($GLOBALS['CONNECTION'], $temp_query, array($student_user));
        $student_id = pg_fetch_row($temp)[0];
    }
		//BEGIN TRANSACTION before making any changes to ensure atomicity
		pg_query($GLOBALS['CONNECTION'], "BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE");
		
    array_push($delete_params, $student_id);
    array_push($delete_params, $term_id);
		$delete_query = 'DELETE FROM hour_block WHERE student_id = $1 AND term_id = $2';
		
		pg_query_params($GLOBALS['CONNECTION'], $delete_query, $delete_params);
		
		//create an array with the student id specified to be used by insert_availability_block
		$student_kwarg = array( "student_id" => $student_id);
		foreach($blocks as $block){
			if(insert_availability_block($term_id, $block['block_day'], $block['block_hour'], $block['block_preference'], $student_kwarg) == false) {
				//ROLLBACK to before deletion if there is a problem inserting the hour_blocks
				pg_query($GLOBALS['CONNECTION'], 'ROLLBACK');
				return false;
			}
		}
		//COMMIT after deletion and insertion are successful
		pg_query($GLOBALS['CONNECTION'], "COMMIT");
		return true;
}

// retrieves the shift preference for a specific student by a specific term
function retrieve_shift_preference($studentid, $termid) {

    //Selecet the shift preference from the shift preference table when
    //student Id matches the entered Id and term Id matches the term ID
    $query = "SELECT shift_preference FROM shift_preference WHERE term_id=$1 AND student_id=$2";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($termid, $studentid));
}
?>
