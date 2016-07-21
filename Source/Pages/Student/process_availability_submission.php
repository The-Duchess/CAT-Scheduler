<?php

include "../../Query/Student.php"
include "../../Query/Availability.php"

function submit_availabilities(){

	if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    	echo "<p>Connection Failed</p>\n";
    	exit();
	}

	$student_uname = $_SERVER['PHP_AUTH_US'];
	$student_id = get_student_id_by_username(student_uname);
	$input_term_id = $_POST['term_id'];
	$pref = "";
	$input_bocks = array();

	// things needed for time submission
	// insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null)

	foreach ($_POST as $key => $val) {
		if ($key == "term_name" || $key == "term_id" || $key == "shift_preference") {
			// do nothing
			if ($key == "shift_preference") {
				$pref = $val;

				// add shift_preference
				// $ret = 
			}
		} else {
			// determine split point on $key to get day and number
			// split $key into day + hour
			// get term_id
			// get input pref
			// get student_id

			// this is sort of a hack
			// TODO: if it is possible make this clean
			$pos = strpos($key, 'y');
			$input_day     = substr($key, 0, $pos);
			$input_hour    = substr($key, ($pos + 1), strlen($key));
			$input_pref    = $val;
			$args          = array('student_id' => $student_id);

			if ($val == "A") {
				array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Available'));
			} elseif ($val == "P") {
				array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Prefered'));
			} else {
				// do nothing
			}

		}

		update_availability_blocks($input_term_id, $input_bocks, array("student_id" => $student_id));
	}

}

?>