<?php

// Availability

//US131
//Retrieves the availability for a select student on a select term
// Is given both the student id and the term id
      //--Needs changing once availability page is created
function retrieve_availability_for_student($kwargs=null) {
    if ($kwargs) {
        if (isset($kwargs['student_id'])) {
            $s_id = $kwargs['student_id'];
        }
        if (isset($kwargs['term_id'])) {
            $t_id = $kwargs['term_id'];
        }
    }
	$params = array($s_id, $t_id);
    $query = "SELECT student_username, term_name, block_day, block_hour, block_preference FROM hour_block, student, term
WHERE student.student_id = hour_block.student_id 
AND term.term_id = hour_block.term_id 
AND term.term_id = $2 AND student.student_id = $1";
    return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
}

?>
