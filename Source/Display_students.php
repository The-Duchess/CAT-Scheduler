<?php

//  Returns a results object containing info on the students in
//  the database if successful, otherwise FALSE
function  get_students() {
    //  We want all information from the students, it can be
    //  pruned later
    $query = 'SELECT * FROM Student';

    return pg_query($GLOBALS['CONNECTION'], $query);
}


//  Returns an array of all student information from a results
//  object, otherwise FALSE.
function get_student_array($result=null) {
    if (!$result and !($result = get_students())) {
        return false;
    }

    return pg_fetch_all($result);
}


//  Echos an html list of all students in the database.
//  Returns TRUE if successful, otherwise FALSE 
function display_students_list($students=null) {
    if (!$students and !($students = get_student_array())) { return false; }

    echo "<ul>";
    foreach ($students as $student) {
        echo "<li>";
        echo $student['student_firstname'] . " " . $student['student_lastname'];
        echo "</li>";
    }
    echo "</ul>";
}
?>
