<html>
<head>
    <title>Test</title>
</head>

<body>
    <p>It begins</p>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include "Dropdown_select_term.php";

//  Database connection
if (!($CONNECTION = pg_connect("host=capstonecatteam.hopto.org port=5432 dbname=Cat user=guest password=FIDO"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//  Caller is responsible for form initialization
echo "<form action=\"" . htmlentities($_SERVER['PHP_SELF']) . "\" method=\"post\">\n";
echo "<label>Select Two Terms</label><br>\n";
$term1 = dropdown_select_term("formSubmit");
$term2 = dropdown_select_term("formSubmit");
$term3 = dropdown_select_term("formSubmit");
//  Caller is responsible for submission button, notice that the
//  calls to dropdown_select_term() pass the name of the submission
//  button so that the generated dropdown menus will function
//  properly with the button
echo "<input type=\"submit\" name=\"formSubmit\" value=\"Select\" />\n";
echo "</form>\n";

//  Just a basic echo to show that the data was gathered and submitted
if (!empty($term1)) {
    echo "<p>" . $term1['term_name'] . "</p>\n";
}
if (!empty($term2)) {
    echo "<p>" . $term2['term_name'] . "</p>\n";
}
if (!empty($term3)) {
    echo "<p>" . $term3['term_name'] . "</p>\n";
}
?>
</body>
</html>
