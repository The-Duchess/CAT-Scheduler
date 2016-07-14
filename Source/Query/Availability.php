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
?>
