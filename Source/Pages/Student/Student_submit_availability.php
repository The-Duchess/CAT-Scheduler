<?php
// this first php block initializes the variables used by the page

require_once('../../Dropdown_select_term.php');
require_once('../../Query/Availability.php');
require_once('../../Query/Student.php');
//require_once('process_availability_submission.php');
// require_once('../../Query_retrieve_shift_preference.php');

function submit_availabilities(){

    console.log("in file process");

/*
    if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
        echo "<p>Connection Failed</p>\n";
        exit();
    }
*/

    console.log("connection succeeded");

    $student_uname = $_SERVER['PHP_AUTH_US'];
    $student_id = get_student_id_by_username(student_uname);
    $input_term_id = $_POST['term_id'];
    $pref = "";
    $input_bocks = array();

    console.log("initialized");

    // things needed for time submission
    // insert_availability_block($input_term_id, $input_day, $input_hour, $input_pref, $kwargs=null)

    foreach ($_POST as $key => $val) {


        console.log("creating blocks");

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
            $input_hour    = (int)substr($key, ($pos + 1), strlen($key));
            $input_pref    = $val;
            $args          = array('student_id' => $student_id);

            console.log($input_day);
            console.log($input_hour);
            console.log($input_pref);

            if ($val == "A") {
                array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Available'));
            } elseif ($val == "P") {
                array_push($input_blocks, array("block_day" => $input_day, "block_hour" => $input_hour, "block_preference" => 'Prefered'));
            } else {
                // do nothing
            }

        }

        console.log("finished creating blocks");

        update_availability_blocks($input_term_id, $input_bocks);
    }

}

//if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//TODO: remove this line once auth is in place, it will be automatically populated
//$_SERVER['PHP_AUTH_USER'] = "simca";


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
        <title>Submit Availability</title>
    </head>
    <body>
        <div class='container'>
            <h1>USING simca's DB</h1>

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
    //$db_info['mon9a'] = 'A';


    // TODO: This formatting step should be done in a API function, make a Story about it
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
    // till here

    $start_date = strtotime($selected_term['start_date']);
    $end_date = strtotime($selected_term['end_date']);

    echo "<h1>User: " . $_SERVER['PHP_AUTH_USER'] . "</h1>";
    echo "<h1>" . $selected_term['term_name'] . "</h1>";
    echo "<h2>" . date('Y-m-d', $start_date) . " - " . date('Y-m-d', $end_date) . "</h2>";

?>

            <div class='main_form'>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input type='hidden' name='term_name' value='<?= $selected_term['term_name']?>' />
                    <input type='hidden' name='term_id' value='<?= $selected_term['term_id']?>' />
                    <table>
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
                                        echo "<fieldset>";

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
<div> <h3>KEY:</h3>
                        <p>A  - Available</p>
                        <p>P  - Prefered</p>
                        <p>NA - Not Available</p>
                    </div>

                    <div>
                            <h2>Shift Preference</h2>
                            <?php
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
                            ?>
                            <input type='radio' id='4h' value='One 4-Hour' name='shift_pref' <?= ($pref == '4h' ? ' checked ' :' ')?>>
                            <label for='4h'>One 4 Hour Shift</label>
                            <br>
                            <input type='radio' id='2h' value='Two 2-Hour' name='shift_pref' <?= ($pref == '2h' ? ' checked ' :' ')?>>
                            <label for='2h'>Two 2 Hour Shifts</label>
                            <br>
                            <input type='radio' id='0h' value='No Preference' name='shift_pref' <?= ($pref == '0h' ? ' checked ' :' ')?>>
                            <label for='0h'>No Preference</label>
                    </div>

                    <input type='submit' value='Submit'/>
                </form>
            </div>
<?php

} //closing the page wrapper if statement

if (isset($_POST['shift_preference'])) {
    submit_availabilities();
}

?>
        </div>
        <script type='text/javascript' src='../../jquery-3.0.0.min.js'></script>
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
