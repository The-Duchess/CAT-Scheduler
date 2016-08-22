<?php

require_once dirname(__FILE__) . "/../Query/Student.php";

//  Generates HTML code for displaying a list of students
//  who have not submitted availability for a given term,
//  returns TRUE if successful otherwise FALSE.
//  PARAMETERS:
//      term_id:    the id of the term in question
function list_students_no_availability($term_id) {
    //  Get students
    if (!($results = retrieve_students_no_availability($term_id))) {
        return false;
    }
    $students = pg_fetch_all($results);

    //Generate HTML code
    echo "<ul>\n";
    foreach ($students as $student) {
        echo "<li>" . $student['student_username'] . "</li>\n";
    }
    echo "</ul>\n";

    return true;
}

function bootstrapped_list_students_no_availability($term_id) {
    //  Get students
    if (!($results = retrieve_students_no_availability($term_id))) {
        return false;
    }
    $students = pg_fetch_all($results);

    //Generate HTML code
    echo "<ul class=\"list-group\">\n";
    foreach ($students as $student) {
        echo "<li class=\"list-group-item\">" . $student['student_username'] . "</li>\n";
    }
    echo "</ul>\n";

    return true;
}

?>
