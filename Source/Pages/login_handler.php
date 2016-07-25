<?php

require_once dirname(__FILE__) . "/../Query/Student.php";

//connect to database
if (!($CONNECTION = pg_connect("host=db.cecs.pdx.edu port=5432 dbname=simca user=simca password=hk8#9Yyced"))) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//check if user is in the database, if not insert them
if ($result = get_student_id_by_username($_SERVER['PHP_AUTH_USER']) && pg_num_rows($result) == 0) {
    $new_user = $_SERVER['PHP_AUTH_USER'];
    $join_date = new Datetime("now");
    if (!add_student($new_user, $join_date)) {
        echo "ERROR: Logged in student not in database and insertion failed";
    }
} else {
    echo "Could not determine if user is in database or not";
}

//check if the user is an admin or not
$admin = false;
//TODO: when LDAP is intigrated, change logic to set $admin flag
if ($_SERVER['PHP_AUTH_USER'] == 'bowzr') {
    $admin = true;
}

?>
