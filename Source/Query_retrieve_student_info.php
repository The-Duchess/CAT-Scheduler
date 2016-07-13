<?php


function retrieve_student_info($studentid) {


	$query = "SELECT *,student_email FROM hour_block ".
	"JOIN student ON hour_block.student_id=student.student_id WHERE hour_block.student_id='$studentid'";
    return pg_query($GLOBALS['CONNECTION'], $query);
}

?>