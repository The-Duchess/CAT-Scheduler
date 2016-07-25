<?php

require_once dirname(__FILE__) . "/../../Query/Availability.php";
require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
// TODO: remove this when we have a true user auth
if (empty($_SESSION['PHP_AUTH_USER'])) {
    $_SESSION['PHP_AUTH_USER'] = "dog01";
}

$kwargs2 = array(
    "student_username" => 'dog01'
);

$student_uname = $_SESSION['PHP_AUTH_USER'];
$res = get_student_id_by_username($student_uname);
$arr = pg_fetch_array($res);
$student_id = $arr['student_id'];
$input_term_id = $_POST['term_id'];
$pref = "";
$input_blocks = array();

//console.log("initialized");

// things needed for time submission
// insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null)

foreach ($_POST as $key => $val) {

    //console.log("creating blocks");

    if ($key == "term_name" || $key == "term_id" || $key == "shift_pref" || $key == "Submit") {
        // do nothing if the key is term_id, shift_pref, or Submit
        if ($key == "shift_pref") {
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
        $input_day     = substr($key, 0, ($pos + 1));
        $input_hour    = (int)substr($key, ($pos + 1), strlen($key));
        $input_pref    = $val;
        $args          = array("student_id" => $student_id);

        //console.log($input_day);
        //console.log($input_hour);
        //console.log($input_pref);

        if ($val == "A") {
            array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Available'));
        } elseif ($val == "P") {
            array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Preferred'));
        } else {
            // do nothing
        }

    }

    //console.log("finished creating blocks");

}

//print_r($student_id);
//print(" - ");
//print_r($input_blocks);

if(update_availability_blocks($input_term_id, $input_blocks, $kwargs2)){
    print("Shift availability successfully updated.");
} else {
    print("Error in submitting the new availability.");
}
echo "<br>";
//print(" - ");
//print_r($res);
//print(" end");

if(add_student_shift_preference($student_id, $input_term_id, $pref)) {
    print("Shift preference successfully updated.");
} else {
    print("Error in submitting shift preference.");
}
echo "<br>";

?>
<a href="Student_submit_availability.php">Submit Availability For Another Term</a>
<br>
<a href="../login_home.php">Return Home</a>
