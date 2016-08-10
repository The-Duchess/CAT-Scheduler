<?php
// this first php block initializes the variables used by the page

require_once dirname(__FILE__)."/../../API/Utility.php";
require_once dirname(__FILE__)."/../../API/Admin.php";
require_once dirname(__FILE__)."/../../Query/Availability.php";
require_once dirname(__FILE__)."/../../Query/Student.php";
//require_once dirname(__FILE__)."/../../Query_retrieve_availability_for_term.php";
//require_once('process_availability_submission.php');
// require_once('../../Query_retrieve_shift_preference.php');

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
if(empty($_SERVER['PHP_AUTH_USER'])) {
    $_SERVER['PHP_AUTH_USER'] = "ealkadi";
}

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
            <a href="../login_home.php">Return Home</a><br>
            <!--<h1>USING Cody's DB</h1>-->
            
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
    $pref_info = array();
    $students = array();
    //get all the availabilities and shift prefs ready to be echoed
    if ($info) {
        foreach ($info as $data) {
          if(pg_fetch_all($data)){
            foreach(pg_fetch_all($data) as $student_id => $student){
              $id = $student['block_day'] . $student['block_hour'];
              $uname = $student['student_username'];
              $pref = $student['block_preference']; 
              if(!in_array($uname, $students)){
                $res = get_student_id_by_username($uname);
                $arr = pg_fetch_array($res);
                $student_id = $arr['student_id'];
                $students[$student_id] = $uname;
              }
              $db_blocks[$id][$uname] = $pref;
            }
          }
        }
    }
    foreach($students as $student_id => $student_uname){
      $res = retrieve_shift_preference((int) $student_id, $term_id);
      $arr = pg_fetch_array($res);
      $pref_info[$student_uname] = $arr['shift_preference'];      
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

                                        //generate the id string for the table element
                                        $cur_id = $day . $hour;
                                        echo "<td id=$cur_id>";
                                        //populate the table element with names of available students if there are any
                                        if(array_key_exists($cur_id, $db_blocks)){
                                          $block = $db_blocks[$cur_id];
                                          //display preffered availabilities first
                                          foreach($block as $name => $pref){
                                            if($pref == 'Preferred') {
                                              echo "<font color = 'blue'>$name</font><br>";
                                            }
                                          }
                                          //then display normal availabilities
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
    //Display shift preferences for students who have submitted their availability for this term
    echo "<div> <h3>Shift Preferences: </h3><ul>";
    foreach($pref_info as $username => $pref){
      echo "<li> $username --> $pref  </li>";
    }
    echo "</ul></div>";
    //Display student usernames who have yet to submit availability for this term
    echo "<div> <h3>Students who have not submitted availability for this term: </h3>";
    list_students_no_availability($term_id);
    echo "</div>";
    
} //closing the page wrapper if statement

?>
        </div>
        <div> <h3>KEY:</h3>
                        <p><font color = 'blue'>Blue - Preferred</font></p>
                        <p><font color = 'green'>Green - Available</font></p>
        </div>
    </body>
</html>
