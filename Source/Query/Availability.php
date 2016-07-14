<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
function retrieve_availability_for_student($student_id, $term_id, $kwargs=null) {
	// Default values
	if (!is_int($student_id) OR (!is_int($term_id))) {
		return false;
	}
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

	$params = array($student_id, $term_id);
    $query = "SELECT student_username, term_name, block_day, block_hour, block_preference
	FROM hour_block, student, term
	WHERE student.student_id = hour_block.student_id
	AND term.term_id = hour_block.term_id
	AND student.student_id = $1
	AND term.term_id = $2";
	
/*	
	array_push($params, days $block_day);
	array_push($params, hours $block_hour);
if ($block_day){
	$query .= " AND block_day = $3";
}
if ($block_hour){
    $query .= " AND block_hour = $4";
}
*/
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

    // Return null if any of the given parameters are null
    if (empty($day) || empty($hour) || empty($pref)) {
        return null;
    }

    // Use the given username if specified in kwargs, defaulting to session username (if set). null otherwise.
    if (isset($kwargs['student_username'])) {
        $student_user = $kwargs['student_username'];
    } else if (isset($_SESSION['PHP_AUTH_USER'])) {
        $student_user = $_SESSION['PHP_AUTH_USER'];
    } else {
        return null;
    }

    // The table stores a student_id, so we need to extract it from the username if not given
    // in kwargs
    if (isset($kwargs['student_id'])) {
        $student_id = $kwargs['student_id'];
    } else {
        $temp_query = 'SELECT student_id FROM student WHERE student_username = $1';
        $temp = pg_query_params($GLOBALS['CONNECTION'], $temp_query, array($student_user));
        $student_id = pg_fetch_row($temp)[0]; //this may not work...
    }
    array_push($params, $student_id);

    array_push($params, $term_id);
    array_push($params, $day);
    array_push($params, $hour);
    array_push($params, $pref);

    $query = 'INSERT INTO hour_block (student_id, term_id, block_day, block_hour, block_preference) VALUES($1,$2,$3,$4,$5)';

    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}
?>
