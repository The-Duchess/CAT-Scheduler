<?php

    // return a result object of the query if successful, FALSE otherwise.
    function add_student_availability($term, $day, $hour, $type) {
        //this assumes that the primary key is being insterted automatically using a counter
        $query = 'INSERT into student_avail(term_id, weekday, hour, type) VALUES($1,$2,$3,$4)';

        return pg_query_params($CONNECTION, $query, array($term, $day, $hour, $type));
    }
?>
