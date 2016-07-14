<?php

//  Creates HTML code for dropdown menus for selecting dates, returns
//  a DateTime object
//  PARAMETERS:
//      submit_ident:   indetifying name for submit object
//      future:         how many years into the future to allow, default 1
function date_selector($submit_ident, $future=1) {
    //  ID to ensure unique forms
    static $id = 0;
    $id++;

    //  Initialize local variables
    $year_ident = "selectYearDateSelector" . $id;
    $month_ident = "selectMonthDateSelector" . $id;
    $day_ident = "selectDayDateSelector" . $id;
    $months = array("January" => 1, "February" => 2, "March" => 3, "April" => 4, "May" => 5, "June" => 6,
        "July" => 7, "August" => 8, "September" => 9, "October" => 10, "November" => 11, "December" => 12);
    $months31 = array(1, 3, 5, 7, 8, 10, 12);

    //  Create year dropdown
    echo "<select name=\"" . $year_ident . "\" onchange=\"this.form.submit()\">\n";
    $thisyear = intval((new DateTime("now"))->modify("+" . $future . " year")->format("Y"));
    for ($year=$thisyear; $year>=1946; $year--) {
        if (isset($_POST[$year_ident]) and $_POST[$year_ident] == $year) {
            echo "<option value=" . $year . " selected >" . $year . "</option>\n";
        } else {
            echo "<option value=" . $year . ">" . $year . "</option>\n";
        }
    }
    echo "</select>\n";

    //  Create month dropdown
    echo "<select name=\"" . $month_ident . "\" onchange=\"this.form.submit()\">\n";
    foreach ($months as $name => $number) {
        if (isset($_POST[$month_ident]) and $_POST[$month_ident] == $number) {
            echo "<option value=\"" . $number . "\" selected>" . $name . "</option\n>";
        } else {
            echo "<option value=\"" . $number . "\">" . $name . "</option\n>";
        }
    }
    echo "</select>\n";

    //  Create day dropdown
    echo "<select name=\"" . $day_ident . "\">\n";
    if ($_POST[$month_ident] == $months["February"]) {
        $intyear = intval($_POST[$year_ident]);
        if (($intyear % 4 == 0 and $intyear % 100 != 0) or $intyear % 400 == 0) {
            for ($day=1; $day<=29; $day++) {
                if (isset($_POST[$day_ident]) and $_POST[$day_ident] == $day) {
                    echo "<option value=" . $day . " selected>" . $day . "</option>\n";
                } else {
                    echo "<option value=" . $day . ">" . $day . "</option>\n";
                }
            }
        } else {
            for ($day=1; $day<=28; $day++) {
                if (isset($_POST[$day_ident]) and $_POST[$day_ident] == $day) {
                    echo "<option value=" . $day . " selected>" . $day . "</option>\n";
                } else {
                    echo "<option value=" . $day . ">" . $day . "</option>\n";
                }
            }
        }
    } else if (in_array($_POST[$month_ident], $months31)) {
        for ($day=1; $day<=31; $day++) {
            if (isset($_POST[$day_ident]) and $_POST[$day_ident] == $day) {
                echo "<option value=" . $day . " selected>" . $day . "</option>\n";
            } else {
                echo "<option value=" . $day . ">" . $day . "</option>\n";
            }
        }
    } else {
        for ($day=1; $day<=30; $day++) {
            if (isset($_POST[$day_ident]) and $_POST[$day_ident] == $day) {
                echo "<option value=" . $day . " selected>" . $day . "</option>\n";
            } else {
                echo "<option value=" . $day . ">" . $day . "</option>\n";
            }
        }
    }
    echo "</select>";

    //  Check if submission criteria are met and return
    if (isset($_POST[$submit_ident])) {
        $selected_year = $_POST[$year_ident];
        $selected_month = $_POST[$month_ident];
        $selected_day = $_POST[$day_ident];

        if (!empty($selected_year) and !empty($selected_month) and !empty($selected_day)) {
            $datestr = implode("-", array($selected_year, $selected_month, $selected_day));
            $ret = new DateTime($datestr);
            return $ret;
        }
    }
}

?>
