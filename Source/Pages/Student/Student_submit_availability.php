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
            <div class="row">
                <a class="btn btn-primary" href="../login_home.php">Back to Home</a>
                <h1>Submit Availabilities</h1>
            </div>
            <div class="row">

<?php

$unsaved_flag=false;
//generate the dropdown form for selecting a term to submit availability for
echo "<form class=\"form-inline\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
$selected_term = dropdown_select_term("termSelect");


echo "<input class=\"btn ben-default\" type=\"submit\" name=\"termSelect\" value=\"Select\" onclick=\"unsaved()\"" . ($unsaved_flag ? unsaved_prompt():''). " />\n";
//echo "<input class=\"btn ben-default\" type=\"submit\" name=\"termSelect\" value=\"Select\" onclick=\"\"" . ( !empty($selected_term) ? unsaved():'') . "/>\n";

echo "</form>\n";



// if the copy button was pressed then the selected term to edit will be erased
// we need to set the displayed term manually
if(!empty($_POST['copy']) && !empty($_POST['original_Term'])){
    $selected_term = unserialize($_POST['original_Term']);
}

?>

            </div>
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

    // TODO: This formatting step should be done in a API function, make a Story about it ****
    $result = retrieve_availability_for_student($student_id, $term_id_new);
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



?>
            <div class="row">
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
            </div>
            <div class='main_form'> <!-- beginning of main_form-->
                    <div class="row"> <!-- beginning of row 1-->
                        <div class="col-md-3"> <!-- beginning of column 1-->
                            <div class="row"> <!-- beginning of column 1 row 1-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Copy previous submission</h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php
                                        echo "<form class=\"form-inline\" action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
                                        echo "<fieldset".($editable == false ? ' disabled="disabled" ':'').">";
                                        $copy_term = dropdown_select_term("copy");

                                        // pass along the term to display if the copy button will be pressed
                                        $original_term = htmlentities(serialize($selected_term));
                                        echo "<input type=\"hidden\" name=\"original_Term\" value=\"$original_term\" />\n";

                                        echo "<input class=\"btn btn-default\" type=\"submit\" name=\"copy\" value=\"Copy\"
                                              onclick=\"return confirm('Are you sure you want to overwrite the entries of the current term with the selected one? (This will not effect your saved availability until you submit');\" />\n";
                                        echo "</fieldset>";
                                        echo "</form>\n";
                                        ?>
                                    </div>
                                </div>
                            </div> <!-- end of column 1 row 1-->
                            <div class="row"> <!-- beginning of column 1 row 2-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Key</h3>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-available">A  - Available</li>
                                            <li class="list-group-item list-group-item-preferred">P  - Preferred</li>
                                            <li class="list-group-item list-group-item-defualt">NA - Not Available</li>
                                        </ul>
                                    </div>
                                </div>
                            </div> <!-- end of row 1.2-->
                <form action="process_availability_submission.php" method="POST">
                            <div class="row"> <!-- beginning of column 1 row 3-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Shift Preference</h3>
                                    </div>
                                    <div class="panel-body">
                                        <fieldset id="shift-pref"<?= ($editable == false ? ' disabled="disabled" ':'')?>>
                                            <input type='radio' id='4h' value='One 4-Hour' name='shift_pref' <?= ($pref == '4h' ? ' checked ' :' ')?> onchange="unsaved()">
                                            <label for='4h'>One 4 Hour Shift</label>
                                            <br>
                                            <input type='radio' id='2h' value='Two 2-Hour' name='shift_pref' <?= ($pref == '2h' ? ' checked ' :' ')?> onchange="unsaved()">
                                            <label for='2h'>Two 2 Hour Shifts</label>
                                            <br>
                                            <input type='radio' id='0h' value='No Preference' name='shift_pref' <?= ($pref == '0h' ? ' checked ' :' ')?> onchange="unsaved()">
                                            <label for='0h'>No Preference</label>
                                        </fieldset>
                                    </div>
                                </div>
                            </div> <!-- end of column 1 row 3-->
                        </div> <!-- end of column 1-->
                        <div class="col-md-9"> <!-- beginning of column 2-->
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
                        </div> <!-- end of column 2-->
                    </div> <!-- end of row 1-->
                    <div class="row"> <!-- beginning of row 2-->
                        <input class="btn btn-default" type='submit' value='Submit' name='Submit'<?= ($editable == false ? ' disabled="disabled" ':'')?>/>
                        <input class="btn btn-primary" type='button' value='Clear' onclick="clear_submission()"<?= ( $editable == false ? ' disabled=disabled ':'')?>/>
                    </div> <!-- end of row 2-->
                </form>
            </div> <!-- end of main_form-->
<?php

} //closing the page wrapper if statement

//Check to see if there is nay difference 
function unsaved_prompt(){
	echo "<script> confirm('There are unsubmitted changes! are you sure you want to continue?') </script>";
    return;
}

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
        <script>
            function confirmCopy(){
                return confirm('Are you sure you want to overwrite the entries of the current term with the selected one? (This will not effect your saved availability until you submit)');
            }
        </script>
        <script type='text/javascript'>
            var val_change = false;

            var colorCell = function (cell) {
                var selected = cell.find('input[type=radio]:checked');
                if (selected.val() == "A") {
                    //cell.css("background-color", "green");
                    cell.children("label").attr("class", "btn btn-xs btn-available");
                    selected.parent().addClass("active");
                } else if (selected.val() == "P") {
                    //cell.css("background-color", "orange");
                    cell.children("label").attr("class", "btn btn-xs btn-success");
                    selected.parent().addClass("active");
                } else {
                    //cell.css("background-color", "white");
                    cell.children("label").attr("class", "btn btn-xs btn-default");
                    selected.parent().addClass("active");
                }
            };

            var recolorCalendar = function() {
                $('fieldset').each(function() {
                    if ($(this).attr("id") != "shift-pref") {
                        colorCell($(this));
                    }
                });
            };
            function unsaved(){
                if (val_change == true) {
                    alert(val_change);
                }
	        }

            $(document).ready(function() {
                recolorCalendar();


                $('fieldset').change(function () {
                    val_change = true;
                    if ($(this).attr("id") != "shift-pref") {
                        colorCell($(this));
                    }
                    console.log
                });
            });
        </script>
	<script>
	</script>
    </body>
</html>
