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

	foreach ($_POST as $entry) {

		if ($entry['name'] == "term_name") {
			// do nothing
		} if ($entry['name'] == "term_id") {
			// do nothing
		} elseif ($entry['name'] == "shift_preference") {
			// do nothing
		} else {
			$input_term_id = $entry['term_id'];
			$input_day = substr($entry['id'], 0, -1);
			$input_hour = substr($entry['id'], -1);
			$input_pref = $entry['value'];
			$args { student_id => $student_id };

			$ret = insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $args);
		}
	}
}

?>