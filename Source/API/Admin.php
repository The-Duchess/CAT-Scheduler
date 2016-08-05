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

// generates a list of student usernames for those that
// have not submitted availability for a term
// PARAMETERS:
//        - term_id
function get_student_uname_no_availability($term_id) {
     if (!($results = retrieve_students_no_availability($term_id))) {
         return false;
     }
     $students = pg_fetch_all($results);
     $students_uname = array();

     foreach ($students as $student) {
          array_push($students_uname, $student);
     }

     return $students_uname;
}

?>
