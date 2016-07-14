<?php

// retrieves an array of students constrained by a specific term
function retrieve_student_info($termid) {

    $query = "SELECT student_id, student_username FROM student WHERE student_id IN (SELECT student_id FROM hour_block WHERE term_id=$1)";

return pg_query_params($GLOBALS['CONNECTION'], $query, array($termid));
}
?>
