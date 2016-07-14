<?php

require_once('Dropdown_select_term.php');

if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//generate the dropdown form for selecting a term to submit availability for
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
echo "<label>Select which term you would like to submit availabilities for</label><br>\n";
$selected_term = dropdown_select_term("termSelect");
echo "<input type=\"submit\" name=\"termSelect\" value=\"Select\" />\n";
echo "</form>\n";

// page wrapper statement, we dont want to load the availability blocks until a term is selected
if (!empty($selected_term)) {

    echo "<h1>" . $selected_term['term_name'] . "</h1>";
    echo "<h2>" . $selected_term['start_date'] . " - " . $selected_term['end_date'] . "</h2>";

//this array will have to be populated via the information in the database once the script is in place
//TODO: change source of this array to data retrieved from script of US125
$db_info = array();
function format_term_submissions($term) {
    //$data = getTermSubmission($term, $user);

    //the parts below this will need to be removed once the database section is implemented
    $db_info['mon9a'] = 'A';
    $db_info['wed9a'] = 'P';
    $db_info['wed10a'] = 'P';
    return $db_info;
}

$db_info = format_term_submissions($selected_term['term_id']);

//these arrays will be used for generating the calendar grid
$hours = array(
    '8a'  => '8:00AM - 9:00AM',
    '9a'  => '9:00AM - 10:00AM',
    '10a' => '10:00AM - 11:00AM',
    '11a' => '11:00AM - 12:00PM',
    '12p' => '12:00PM - 1:00PM',
    '1p'  => '1:00PM - 2:00PM',
    '2p'  => '2:00PM - 3:00PM',
    '3p'  => '3:00PM - 4:00PM',
    '4p'  => '4:00PM - 5:00PM',
    '5p'  => '5:00PM - 6:00PM'
);

$days = array('mon', 'tues', 'wed', 'thu', 'fri', 'sat');

?>

<html>
    <head>
        <title>Submit Availability</title>
    </head>
    <body>
        <div class='container'>

            <div class='main_form'>
                <form action='process_availability_submission.php' method='POST'>

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
                                        if ($day == 'sat') {
                                            if ($hour == '8a' || $hour == '9a' || $hour == '10a' || $hour == '11a' || $hour == '5p') {
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
                                        if(array_key_exists($cur_id, $db_info)) {
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

                    <div>
                        <h3>KEY:</h3>
                        <p>A  - Available</p>
                        <p>P  - Prefered</p>
                        <p>NA - Not Available</p>
                    </div>

                    <input type='submit' value='Submit' />
                </form>
            </div>
<?php
} //closing the page wrapper if statement
?>
        </div>
        <script type='text/javascript' src='jquery-3.0.0.min.js'></script>
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
