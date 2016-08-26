<!--
Copyright 2016 Cat Capstone Team
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Licensed to the Computer Action Team (CAT) under one
or more contributor license agreements.  See the NOTICE file
distributed with this work for additional information
regarding copyright ownership.  The CAT Captstone Team
licenses this file to you under the Apache License, Version 2.0 (the
"License"); you may not use this file except in compliance
with the License.  You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing,
software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, either express or implied.  See the License for the
specific language governing permissions and limitations
under the License.
-->

<?php
// this first php block initializes the variables used by the page

require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../Query/Availability.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";
require_once dirname(__FILE__) . "/process_availability_submission.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
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
        <!-- Bootstrap -->
        <link href="../../css/bootstrap_current/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/custom/custom-buttons.css" rel="stylesheet">
        <link href="../../css/custom/custom-list-group.css" rel="stylesheet">
        <title>Submit Availability</title>
    </head>
    <body>
        <div class="container">
            <br>
            <div class="row"><!-- beginning of row 1-->
                <a class="btn btn-primary" href="../login_home.php">Back to Home</a>
<?php
    // display an alert indicating the success or fail of availability submission if the submit button was pressed
    if(isset($_POST['submit_main'])) {
        $submission_result = process_submissions();
        $term_name = unserialize($_POST['original_Term']);
        $message = "";
        if($submission_result) {
            echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">\n";
            $message = "Your availability for term \"" . $term_name['term_name'] . "\" was updated successfully!";
        } else {
            echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">\n";
            $message = "There was an error in processing your availability update for " . $term_name['term_name'] . "...";
        }
        echo "<button class=\"close\" type=\"button\" data-dismiss=\"alert\" aria-label=\"Close\">";
        echo "<span aria-hidden=\"true\">x</span>\n";
        echo "</button>\n";
        echo $message;
        echo "</div>\n";
    }
?>

                <h1>Submit Availabilities</h1>
            </div><!-- end of row 1-->
            <div class="row"><!-- beginning of row 2-->

<?php

//generate the dropdown form for selecting a term to submit availability for
echo "<form class=\"form-inline\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
$selected_term = dropdown_select_term("termSelect", array("view_only_alert" => true));

echo "<input class=\"btn ben-default\" type=\"submit\" name=\"termSelect\" value=\"Select\" onclick=\"unsaved(event)\"/>\n";
echo "</form>\n";



// if the copy button was pressed then the selected term to edit will be erased
// we need to set the displayed term manually
if(!empty($_POST['copy']) || !empty($_POST['submit_main'])){
    if(!empty($_POST['original_Term'])) {
        $selected_term = unserialize($_POST['original_Term']);
    }
}

?>

            </div><!-- beginning of row 2-->
            <hr>
<?php

// page wrapper statement, we dont want to load the availability blocks until a term is selected
if (!empty($selected_term)) {

    //initialize the variables for querying the database for the submissions
    $student_id = (int)pg_fetch_row(get_student_id_by_username($_SERVER['PHP_AUTH_USER']))[0];
    $term_id = (int)$selected_term['term_id'];

    //if the copy term button was clicked, we want to display the submissions for the term selected
    //in that dropdown, not the one on the top
    if(!empty($_POST['copy'])){
        $term_id_new = (int)$_POST['formTerm2'];
    } else {
        $term_id_new = (int)$selected_term['term_id'];
    }
    $editable = ($selected_term['editable'] == "t" ? true:false);
    $mentoring = ($selected_term['mentoring'] == "t" ? true : false);

    // TODO: This formatting step should be done in a API function, make a Story about it ****
    $result = retrieve_availability_for_student($student_id, $term_id_new);
    $rows = pg_fetch_all($result);
    ///////////////////

    // prepare the information for the selected term by formatting it into a array
    // that the calendar will use to display the initial table
    $db_info = array();
    if ($rows) {
        foreach ($rows as $row) {
            $id = $row['block_day'] . $row['block_hour'];
            $val = "";
            if ($row['block_preference'] == 'Available') {
                $val .= 'A';
            } else if ($row['block_preference'] == 'Preferred') {
                $val .= 'P';
            }
            $db_info[$id] = $val;
        }
    }

    //set the defualt radio button for the shift preference
    //'One 4-Hour', 'Two 2-Hour', 'No Preference'
    $shift_pref_results = retrieve_shift_preference($student_id, $term_id);
    $pref = '0h';
    $shift_pref = pg_fetch_row($shift_pref_results);
    if ($shift_pref) {
        if ($shift_pref[0] == 'One 4-Hour') {
            $pref = '4h';
        } else if ($shift_pref[0] == 'Two 2-Hour') {
            $pref = '2h';
        }
    }

    // till here ****

    $start_date = strtotime($selected_term['start_date']);
    $end_date = strtotime($selected_term['end_date']);



?>
            <div class="row"><!-- beginning of row 3-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title"><?=$selected_term['term_name']?></h2>
                    </div>
                    <div class="panel-body">
                        <h3><?=date('F jS, Y', $start_date)?>
                            <small><span class="glyphicon glyphicon-arrow-right"></span></small>
                            <?=date('F jS, Y', $end_date)?>
                        </h3>
                    </div>
                </div>
            </div><!-- end of row 3-->
            <div class='main_form'> <!-- beginning of main_form-->
                    <div class="row"> <!-- beginning of row 4-->
                        <div class="col-md-3"> <!-- beginning of row 4 column 1-->
                            <div class="row"> <!-- beginning of row 4 column 1 row 1-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Copy previous submission</h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        echo "<form class=\"form-inline\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
                                        echo "<fieldset".($editable == false ? ' disabled="disabled" ':'').">";
                                        $copy_term = dropdown_select_term("copy", array("view_only_alert" => true));

                                        // pass along the term to display if the copy button will be pressed
                                        $original_term = htmlentities(serialize($selected_term));
                                        echo "<input type=\"hidden\" name=\"original_Term\" value=\"$original_term\" />\n";

                                        echo "<input class=\"btn btn-default\" type=\"submit\" name=\"copy\" value=\"Copy\"
                                              onclick=\"confirmCopy(event)\" />\n";
                                        echo "</fieldset>";
                                        echo "</form>\n";
                                        ?>
                                    </div>
                                </div>
                            </div> <!-- end of row 4 column 1 row 1-->
                            <div class="row"> <!-- beginning of row 4 column 1 row 2-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Key</h3>
                                    </div>
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-available">A  - Available</li>
                                        <li class="list-group-item list-group-item-preferred">P  - Preferred</li>
                                        <li class="list-group-item list-group-item-defualt">NA - Not Available</li>
                                    </ul>
                                </div>
                            </div> <!-- end of row 4 column 1 row 2-->
                            <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST">
                            <div class="row"> <!-- beginning of row 4 column 1 row 3-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Shift Preference</h3>
                                    </div>
                                    <div class="panel-body">
                                        <fieldset id="shift-pref"<?= ($editable == false ? ' disabled="disabled" ':'')?>
                                                  <?php echo ($mentoring ? "hidden" : ""); ?>>
                                            <input type='radio' id='4h' value='One 4-Hour' name='shift_pref' <?= ($pref == '4h' ? ' checked ' :' ')?>>
                                            <label for='4h'>One 4 Hour Shift</label>
                                            <br>
                                            <input type='radio' id='2h' value='Two 2-Hour' name='shift_pref' <?= ($pref == '2h' ? ' checked ' :' ')?>>
                                            <label for='2h'>Two 2 Hour Shifts</label>
                                            <br>
                                            <input type='radio' id='0h' value='No Preference' name='shift_pref' <?= ($pref == '0h' ? ' checked ' :' ')?>>
                                            <label for='0h'>No Preference</label>
                                        </fieldset>
                                        <div class="alert alert-warning text-center"
                                             <?php echo ($mentoring ? "" : "hidden"); ?>>
                                            <strong>This is a Mentoring Term</strong>
                                            <br>
                                            You will be assigned 2 hour shifts for the duration of this term
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end of row 4 column 1 row 3-->
                        </div> <!-- end of row 4 column 1-->
                        <div class="col-md-9"> <!-- beginning of row 4 column 2-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Calendar</h3>
                                </div>
                                <div class="panel-body">
                                    <input type='hidden' name='term_name' value='<?= $selected_term['term_name']?>' />
                                    <input type='hidden' name='term_id' value='<?= $selected_term['term_id']?>' />
                                    <table class="table" id="avail_table">
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

                                                        //generate the id strings for the input fields
                                                        $cur_id = $day . $hour;
                                                        $id_A = $cur_id . 'A';
                                                        $id_P = $cur_id . 'P';
                                                        $id_NA = $cur_id . 'NA';

                                                        //check if the current time block has any info from the database
                                                        $default = "NA";
                                                        if(isset($db_info) && array_key_exists($cur_id, $db_info)) {
                                                            $default = $db_info[$cur_id];
                                                        }

                                                        echo "<td class=$cur_id>";
                                                        echo "<fieldset class='btn-group' data-toggle='buttons'".($editable == false ? ' disabled="disabled" ':'').">";

                                                        echo "<label class='btn btn-xs'>";
                                                        echo "<input type='radio' id=$id_A value='A' name=$cur_id" . ($default == 'A' ? ' checked ':' ') . "/>";
                                                        echo "A</label>";
                                                        echo "<label class='btn btn-xs'>";
                                                        echo "<input type='radio' id=$id_P value='P' name=$cur_id" . ($default == 'P' ? ' checked ':' ') . "/>";
                                                        echo "P</label>";
                                                        echo "<label class='btn btn-xs'>";
                                                        echo "<input type='radio' id=$id_NA value='NA' name=$cur_id" . ($default == 'NA' ? ' checked ':' ') . "/>";
                                                        echo "NA</label>";

                                                        echo "</fieldset>";
                                                        echo "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end of row 4 column 2-->
                    </div> <!-- end of row 4-->
                    <div class="row"> <!-- beginning of row 5-->
                        <?php
                        $original_term = htmlentities(serialize($selected_term));
                        ?>
                            <input type="hidden" name="original_Term" value="<?=$original_term?>" />
                        <input class="btn btn-default" type='submit' value='Submit' name='submit_main'<?= ($editable == false ? ' disabled="disabled" ':'')?>/>
                        <input class="btn btn-primary" type='button' value='Clear' onclick="clear_submission()"<?= ( $editable == false ? ' disabled=disabled ':'')?>/>
                    </div> <!-- end of row 5-->
                </form>
            </div> <!-- end of main_form-->
<?php

} //closing the page wrapper if statement


?>
        </div> <!-- End of container div -->

        <script type='text/javascript' src='../../jquery-3.0.0.min.js'></script>
        <script src="../../css/bootstrap_current/js/bootstrap.min.js"></script>
	    <script type='text/javascript'>
            // Clears all the submissions on the page (setting them to 'NA'
            // and recoloring them (white))
            function clear_submission(){
                //get the table from the page

                var table = document.getElementById("avail_table");

                //ask the user if they are sure
                //if they are, set all radio buttons to 'NA'

                var r = confirm("Are you sure you want to clear all submissions? (This will not effect your saved availability until you submit)");
                if(r!=true){
                    return;
                }

                //iterate through the available blocks (mon-fri, 08:00 - 18:00, sat, 12:00 - 17:00)
                //check the 'NA' radio and reset the repaint the colors
                var daystrings = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                var ds_len = daystrings.length; 
                for(var d = 0; d < ds_len; d++){
                    for(var i = 8; i < 18 ; i++){
                        if(daystrings[d] == "Saturday" && (i == 8 || i==9 || i==10 || i==11 || i==17)){
                            continue;
                        }else{
                            var t = daystrings[d].concat(i.toString(),"NA");
                            document.getElementById(t).checked = 'true'; 
                        }
                    }
                }

                recolorCalendar();

                // set shift pref to no pref
                document.getElementById("0h").checked = 'true';
            }
        </script>
        <script>
            function confirmCopy(e){
                var result = confirm('Are you sure you want to overwrite the entries of the current term with the selected one? (This will not effect your saved availability until you submit)');
                if (result == false) {
                    e.preventDefault();
                }
            }
        </script>
        <script type='text/javascript'>
            // flag to track whether a change was made in the submission for or not
            var val_change = false;

            //apply a color to the time entry based on value
            var colorCell = function (cell) {
                var selected = cell.find('input[type=radio]:checked');
                if (selected.val() == "A") {
                    cell.children("label").attr("class", "btn btn-xs btn-available");
                    selected.parent().addClass("active");
                } else if (selected.val() == "P") {
                    cell.children("label").attr("class", "btn btn-xs btn-success");
                    selected.parent().addClass("active");
                } else {
                    cell.children("label").attr("class", "btn btn-xs btn-default");
                    selected.parent().addClass("active");
                }
            };

            // color the entire calender buttons based on current value
            var recolorCalendar = function() {
                $('fieldset').each(function() {
                    if ($(this).attr("id") != "shift-pref") {
                        colorCell($(this));
                    }
                });
            };

            // called if the user tries to change the term being viewed with unsaved changes
            function unsaved(e){
                if (val_change == true) {
                    var result = confirm('There are unsubmitted changes! are you sure you want to continue?');
                    if (result == false) {
                        e.preventDefault();
                    }
                }
	        }

            $(document).ready(function() {
                recolorCalendar();

                // color the changed timeslot entry based on the new value
                $('fieldset').change(function () {
                    val_change = true;
                    if ($(this).attr("id") != "shift-pref") {
                        colorCell($(this));
                    }
                    console.log
                });
            });
        </script>
    </body>
</html>
