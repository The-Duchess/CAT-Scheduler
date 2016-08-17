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
if (!($CONNECTION = cody_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//simulating user session
if(empty($_SERVER['PHP_AUTH_USER'])) {
    $_SERVER['PHP_AUTH_USER'] = "ealkadi";
}

//these arrays will be used for generating the calendar grid
$hours = array(
    '8'  => '8AM - 9AM',
    '9'  => '9AM - 10AM',
    '10' => '10AM - 11AM',
    '11' => '11AM - 12PM',
    '12' => '12PM - 1PM',
    '13'  => '1PM - 2PM',
    '14'  => '2PM - 3PM',
    '15'  => '3PM - 4PM',
    '16'  => '4PM - 5PM',
    '17'  => '5PM - 6PM'
);
$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

?>


<html>
    <head>
        <link href="../../css/bootstrap_current/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/custom/custom-list-group.css" rel="stylesheet">
        <link href="../../css/custom/custom-table.css" rel="stylesheet">
        <title>View Availability</title>
        <script src="../../JQuery-1.2/jquery-1.12.4.js"></script>
        <script src="../../JavaScript/Admin/view_term_availabilities.js"></script>
    </head>
    <body>
        <div class='container'>
            <br>
            <div class="row">
                <a class="btn btn-primary" href="../login_home.php">Return Home</a><br>
                <h1>View Availability Submissions</h1>
            </div>
            <!--<h1>USING Cody's DB</h1>-->
            <div class="row">
<?php

//generate the dropdown form for selecting a term to submit availability for
echo "<form class=\"form-inline\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
$selected_term = dropdown_select_term("termSelect");
echo "<input class=\"btn btn-default\" type=\"submit\" name=\"termSelect\" value=\"Select\" />\n";
echo "</form>\n";
?>

            </div>
            <hr>

<?php
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

    //generate the dropdown form for selecting a student to focus on
    echo "<form id=\"studentsForm\"action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
    $selected_student = dropdown_select_student("studentSelect", $term_id);
    echo "<input type=\"submit\" name=\"studentSelect\" value=\"Select student\" />\n";
    echo "<input type=\"button\" id=\"studentReset\" name=\"studentReset\" value=\"Reset focus\"/>\n";
    echo "</form>\n";

?>

            <div class="row">
                <div class="col-md-3">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Key</h3>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item list-group-item-available">A  - Available</li>
                                <li class="list-group-item list-group-item-preferred">P  - Preferred</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Shift Preferences</h3>
                            </div>
                            <ul class="list-group">
                                <?php
                                //Display shift preferences for students who have submitted their availability for this term
                                foreach($pref_info as $username => $pref){
                                    echo "<li class=\"list-group-item list-group-item-default\">";
                                    echo "$username ";
                                    echo "<small><span class=\"glyphicon glyphicon-arrow-right\"</span></small>";
                                    echo " $pref";
                                    echo "</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Shift Preferences</h3>
                        </div>
                    <div class="panel-body">
                        <table id='termAvailabilities' class="table table-scrollable table-condensed table-bordered table-responsive">
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
                    </div>
                </div>
            </div>
    <!--Display student usernames who have yet to submit availability for this term-->
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <h3 class="panel-title">Students who have not submitted availability for this term</h3>
                    </div>
                    <?php
                    bootstrapped_list_students_no_availability($term_id);
                    ?>
                </div>
            </div>
        </div>
<?php
} //closing the page wrapper if statement
?>
    </body>
</html>
