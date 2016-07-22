<?php
// this first php block initializes the variables used by the page

require_once dirname(__FILE__)."/../../Dropdown_select_term.php";
//require_once dirname(__FILE__)."/../../Query/Availability.php";
require_once dirname(__FILE__)."/../../Query/Student.php";
require_once dirname(__FILE__)."/../../Query_retrieve_availability_for_term.php";
//require_once('process_availability_submission.php');
// require_once('../../Query_retrieve_shift_preference.php');


//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
$_SESSION['PHP_AUTH_USER'] = "ealkadi"; 


//these arrays will be used for generating the calendar grid
$hours = array(
    '8'  => '8:00AM - 9:00AM',
    '9'  => '9:00AM - 10:00AM',
    '10' => '10:00AM - 11:00AM',
    '11' => '11:00AM - 12:00PM',
    '12' => '12:00PM - 1:00PM',
    '13'  => '1:00PM - 2:00PM',
    '14'  => '2:00PM - 3:00PM',
    '15'  => '3:00PM - 4:00PM',
    '16'  => '4:00PM - 5:00PM',
    '17'  => '5:00PM - 6:00PM'
);
$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

?>


<html>
    <head>
        <title>View Availability</title>
    </head>
    <body>
        <div class='container'>
            <h1>USING Cody's DB</h1>
						
<?php

//generate the dropdown form for selecting a term to submit availability for
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
echo "<label>Select which term you would like to view availabilities for</label><br>\n";
$selected_term = dropdown_select_term("termSelect");
echo "<input type=\"submit\" name=\"termSelect\" value=\"Select\" />\n";
echo "</form>\n";

// page wrapper statement, we dont want to load the availability blocks until a term is selected
if (!empty($selected_term)) {

    $term_id = (int)$selected_term['term_id'];
    $info = retrieve_availability_for_term($term_id);
    $db_blocks = array();
		//get all the availabilities ready to be echoed
    if ($info) {
        foreach ($info as $data) {
					if(pg_fetch_all($data)){
						foreach(pg_fetch_all($data) as $student){
							$id = $student['block_day'] . $student['block_hour'];
							$uname = $student['student_username'];
							$pref = $student['block_preference'];
							if(array_key_exists($id, $db_blocks)) {
								$db_blocks[$id][$uname] = $pref;
							}else {
								$db_blocks[$id] = array();
								$db_blocks[$id][$uname] = $pref;
							}
						}
					}
        }
    }

    $start_date = strtotime($selected_term['start_date']);
    $end_date = strtotime($selected_term['end_date']);

    echo "<h1>User: " . $_SERVER['PHP_AUTH_USER'] . "</h1>";
    echo "<h1>" . $selected_term['term_name'] . "</h1>";
    echo "<h2>" . date('Y-m-d', $start_date) . " - " . date('Y-m-d', $end_date) . "</h2>";

?>

            <div class='main_form'>
               <table border="1">
                        <thead>
                            <tr>
                                <td></td>
                                <td>Monday</td>
                                <td>Tuesday</td>
                                <td>Wednesday</td>
                                <td>Thursday</td>
                                <td>Friday</td>
                                <td>Saturday</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($hours as $hour => $label) {
                                    echo "<tr>";
                                    echo "<td>$label</td>";
                                    foreach($days as $day) {
                                        //saturday has less hours so we want to skip creating blocks for those hours
                                        if ($day == 'Saturday') {
                                            if ($hour == '8' || $hour == '9' || $hour == '10' || $hour == '11' || $hour == '17') {
                                                continue;
                                            }
                                        }

                                        //generate the id strings for the table elements
                                        $cur_id = $day . $hour;
																				//populate the table element with names if there are any
                                        echo "<td id=$cur_id>";
                                        if(array_key_exists($cur_id, $db_blocks)){
																					$block = $db_blocks[$cur_id];
																					foreach($block as $name => $pref){
																						if($pref == 'Preferred') {
																							echo "<font color = 'blue'>$name</font><br>";
																						}
																					}
																					foreach($block as $name => $pref){
																						if($pref == 'Available') {
																							echo "<font color = 'green'>$name</font><br>";
																						}
																					}
																				}
                                        echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
            </div>
<?php

} //closing the page wrapper if statement

?>
        </div>
    </body>
</html>
						
						