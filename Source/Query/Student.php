<?php


// retrieves a student id based on the give username
// PARAMETERS:
//      input_username: username of the student you want to get the id of
function get_student_id_by_username($input_username) {
    $query = "SELECT student_id FROM student WHERE student_username = $1";

    return pg_query_params($GLOBALS['CONNECTION'], $query, array($input_username));
}

// deactivate a student in the students table
// set the Visible value to false
// all other values can be default and are not required to change
function deactivate_student($id) {


	//Throw an error if student does not exist in the data base
	
	$query = "SELECT * FROM student WHERE student_id = '$id'";
	$result = pg_query($GLOBALS['CONNECTION'], $query);
	if(pg_num_rows($result) == 0) {
	    echo "Student Does not exist<br>";
	    return false;
	}
	//Throw an error if the student is already activated
	$query = "SELECT * FROM student WHERE student_id = '$id' AND active is FALSE";
	$result = pg_query($GLOBALS['CONNECTION'], $query);
	if (pg_num_rows($result) != 0) {
	    echo "Student is already deactivated<br>";
	    return false;
	}
	//The only value is chanhing is the visibily since we are going to keep all
	//of the student information
	$query = "UPDATE student SET active = false WHERE student_id = '$id'";

	return pg_query($GLOBALS['CONNECTION'], $query);
}

// --

// add a student to the students table
//
// PARAMETERS:
// 		| - student id
// 		| - student uname
// 		| - student's join time
function add_student($id, $student_uname, $joind) {

	$query = 'INSERT into Students (Student_id, Student_username, join_date) VALUES ($1, $2, $3)';

	return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $student_uname, $joind));
}

// --

	// update a student's info
	// this provides a number of functions to alter a specific student's value other than their primary key
	// and the selection is done by the primary key

	// these functions operate in a generic way where you feed the id of the student
	// that is going to have a value changed and a new value for that column
	// parameters:
	//		id: the student id
	// 		new: the new value
/*
	// DEPRECATED
	function edit_student_email($id, $new) {
		// this will edit the student's email

		$query = 'UPDATE Student SET Student_Email=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	// DEPRECATED
	function edit_student_firstname($id, $new) {
		// this will edit the student's first name

		$query = 'UPDATE Student SET Student_FirstName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	// DEPRECATED
	function edit_student_lastname($id, $new) {
		// this will edit the student's last name

		$query = 'UPDATE Student SET Student_LastName=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	// DEPRECATED
	function edit_student_nick($id, $new) {
		// this will edit the student's cat nick

		$query = 'UPDATE Student SET Cat_Nickname=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}
*/

	function edit_student_visible($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET Active=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	function edit_student_uname($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET Student_username=$2 WHERE Student_id=$1';

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $new));
	}

	// passed a date time
	function edit_student_join_date($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET join_date=$2 WHERE Student_id=$1';

		$newV = $new->format("Y-m-d");

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $newV));
	}

	// also passed a date time
	function edit_student_leave_date($id, $new) {
		// this will edit the student's state s visible

		$query = 'UPDATE Student SET leave_date=$2 WHERE Student_id=$1';

		$newV = $new->format("Y-m-d");

		return pg_query_params($GLOBALS['CONNECTION'], $query, array($id, $newV));
    }


    //  Retrieves a results object containing all terms regardless of editability
    //  sorted by student username, otherwise FALSE
    //  PARAMETERS:
    //      id:     the id of the term
    //      kwargs: associative array of keyword arguments
    //          ascend:     if the results should be in ascending order, default TRUE
    //          limit:      the max number of students to retrieve, default none (null)
    function retrieve_students_no_availability($id, $kwargs=null) {
        //  Default values
        $ascend = true;
        $limit = null;

        //  Read kwargs if necessary
        if ($kwargs) {
            if (isset($kwargs['ascend'])) {
                $ascend = $kwargs['ascend'];
            }
            if (isset($kwargs['limit'])) {
                $limit = $kwargs['limit'];
            }
        }

        //  Create basic query
        $query = "SELECT student_id, student_username FROM student " .
            "WHERE student_id NOT IN (SELECT student_id FROM hour_block WHERE term_id=$1) " .
            "ORDER BY student_username " . ($ascend ? "ASC" : "DESC");
        $params = array($id);

        //  Add limit clause and parameter if desired
        if ($limit) {
            $query .= " LIMIT $2";
            array_push($params, $limit);
        }

        //  Return results object
        return pg_query_params($GLOBALS['CONNECTION'], $query, $params);
    }
?>
