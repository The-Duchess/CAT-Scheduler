<?php
// this first php block initializes the variables used by the page

require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../Query/Availability.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
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
        <!-- Bootstrap -->
        <link href="../../css/bootstrap_current/css/bootstrap.min.css" rel="stylesheet">
        <title>Submit Availability</title>
    </head>
    <body>
        <div class='container'>
            <a href="../login_home.php">Back to Home</a>
<?php

//generate the dropdown form for selecting a term to submit availability for
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
echo "<label>Select which term you would like to submit availabilities for</label><br>\n";
$selected_term = dropdown_select_term("termSelect");
echo "<input type=\"submit\" name=\"termSelect\" value=\"Select\" />\n";
echo "</form>\n";

// page wrapper statement, we dont want to load the availability blocks until a term is selected
if (!empty($selected_term)) {

    $student_id = (int)pg_fetch_row(get_student_id_by_username($_SERVER['PHP_AUTH_USER']))[0];
    $term_id = (int)$selected_term['term_id'];
    $editable = ($selected_term['editable'] == "t" ? true:false);

    // TODO: This formatting step should be done in a API function, make a Story about it ****
    $result = retrieve_availability_for_student($student_id, $term_id);
    $rows = pg_fetch_all($result);

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

    echo "<h1>User: " . $_SERVER['PHP_AUTH_USER'] . "</h1>";
    echo "<h1>" . $selected_term['term_name'] . "</h1>";
    echo "<h2>" . date('Y-m-d', $start_date) . " - " . date('Y-m-d', $end_date) . "</h2>";

?>
            <div class='main_form'> <!-- beginning of main_form-->
                <form action="process_availability_submission.php" method="POST">
                    <div class="row"> <!-- beginning of row 1-->
                        <div class="col-md-3"> <!-- beginning of column 1-->
                            <div class="row"> <!-- beginning of row 1.1-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Key</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>A  - Available</p>
                                        <p>P  - Prefered</p>
                                        <p>NA - Not Available</p>
                                    </div>
                                </div>
                            </div> <!-- end of row 1.1-->
                            <div class="row"> <!-- beginning of row 1.2-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Shift Preference</h3>
                                    </div>
                                    <div class="panel-body">
                                        <fieldset<?= ($editable == false ? ' disabled="disabled" ':'')?>>
                                            <input type='radio' id='4h' value='One 4-Hour' name='shift_pref' <?= ($pref == '4h' ? ' checked ' :' ')?>>
                                            <label for='4h'>One 4 Hour Shift</label>
                                            <br>
                                            <input type='radio' id='2h' value='Two 2-Hour' name='shift_pref' <?= ($pref == '2h' ? ' checked ' :' ')?>>
                                            <label for='2h'>Two 2 Hour Shifts</label>
                                            <br>
                                            <input type='radio' id='0h' value='No Preference' name='shift_pref' <?= ($pref == '0h' ? ' checked ' :' ')?>>
                                            <label for='0h'>No Preference</label>
                                        </fieldset>
                                    </div>
                                </div>
                            </div> <!-- end of row 1.2-->
                        </div> <!-- end of column 1-->
                        <div class="col-md-9"> <!-- beginning of column 2-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Calendar</h3>
                                </div>
                                <div class="panel-body">
                                    <input type='hidden' name='term_name' value='<?= $selected_term['term_name']?>' />
                                    <input type='hidden' name='term_id' value='<?= $selected_term['term_id']?>' />
                                    <table id="avail_table">
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
                                                        echo "<fieldset".($editable == false ? ' disabled="disabled" ':'').">";

                                                        echo "<input type='radio' id=$id_A value='A' name=$cur_id" . ($default == 'A' ? ' checked ':' ') . "/>";
                                                        echo "<label for=$id_A>A</label>";
                                                        echo "<input type='radio' id=$id_P value='P' name=$cur_id" . ($default == 'P' ? ' checked ':' ') . "/>";
                                                        echo "<label for=$id_P>P</label>";
                                                        echo "<input type='radio' id=$id_NA value='NA' name=$cur_id" . ($default == 'NA' ? ' checked ':' ') . "/>";
                                                        echo "<label for=$id_NA>NA</label>";

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
                        </div> <!-- end of column 2-->
                    </div> <!-- end of row 1-->
                    <div class="row"> <!-- beginning of row 2-->
                        <input type='submit' value='Submit' name='Submit'<?= ($editable == false ? ' disabled="disabled" ':'')?>/>
                        <input type='button' value='Clear' onclick="clear_submission()"<?= ( $editable == false ? ' disabled=disabled ':'')?>/>
                    </div> <!-- end of row 2-->
                </form>
            </div> <!-- end of main_form-->
<?php

} //closing the page wrapper if statement

?>
        </div>
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
        <script type='text/javascript'>
            var colorCell = function (cell) {
                var selected = cell.find('input[type=radio]:checked');
                console.log("Given cell: " + selected.val());
                if (selected.val() == "A") {
                    cell.css("background-color", "green");
                } else if (selected.val() == "P") {
                    cell.css("background-color", "orange");
                } else {
                    cell.css("background-color", "white");
                }
            };

            var recolorCalendar = function() {
                $('td').each(function() {
                    colorCell($(this));
                });
            };

                $(document).ready(function() {
                recolorCalendar();

                $('td').change(function () {
                    colorCell($(this));
                });
            });
        </script>
    </body>
</html>
