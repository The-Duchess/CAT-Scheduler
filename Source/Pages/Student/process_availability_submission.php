<?php

include "../../Query/Student.php"
include "../../Query/Availability.php"

function test($selected_term){

// POST KEY VALUES
// "term_name" => term name
// "term_id"   => term id
// the availabilities are passed in the form
// day and hour are concatonated together as the key to the value
// 			Monday9 => A
// 	to show monday at 9 is A

/*

availabilities = { day => "DAY NAME", hours => [hours], shift => "pref" }

POST.each do |p|
	if p == "term_name" or p == "term_id" or p == "shift_preference"
		next
	else
		hour_v.push p[-1]
		day = p[0..-2]
		availabilities.push { :day => day, ..}
	end
end

student_uname = $_SERVER['PHP_AUTH_US']
student_id = get_student_id_by_username(student_uname)
*/

}

function submit_availabilities(){
	
	$student_uname = $_SERVER['PHP_AUTH_US'];
	$student_id = get_student_id_by_username(student_uname);
	$pref = "";

	// things needed for time submission
	// insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null)
	// $_POST contains
	// value { id => 'dayhour', value => 'availability' }
	// so we want to create
	// input_term_id = $_POST['term_id']
	// input_day = $_Post['id'][0..-1]
	// input_hour = $_POST['id'][-1]
	// input_pref = $_POST['value']
	// create kwargs { student_id => $student_id }

	foreach ($_POST as $key => $val) {
		if ($key == "term_name" || $key == "term_id" || $key == "shift_preference") {
			// do nothing
			if ($key == "shift_preference") {
				$pref = $val;
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
			$input_term_id = $_POST['term_id'];
			$input_day     = substr($key, 0, $pos);
			$input_hour    = substr($key, ($pos + 1), strlen($key));
			$input_pref    = $val;
			$args          = ('student_id' => $student_id);

			$ret = insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $args);
			print ($ret);
		}
	}

}

?>