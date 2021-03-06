<?php

require_once dirname(__FILE__) . "/../Query/Student.php";
require_once dirname(__FILE__) . "/../API/Utility.php";

//  Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

//check if user is in the database, if not insert them
if (!($result = get_student_id_by_username($_SERVER['PHP_AUTH_USER']))) {
    echo "Could not determine if user is in database or not";
} else if (pg_num_rows($result) == 0) {
    $new_user = $_SERVER['PHP_AUTH_USER'];
    $join_date = new Datetime("now");
    if (!add_student($new_user, $join_date)) {
        echo "ERROR: Logged in student not in database and insertion failed";
    }
} else {
}

//check if the user is an admin or not
$admin = false;
$admins = array(
    'bowzr',
    'shrugz4life',
    'dog01',
    'unix4life',
    'princessz',
    'koopaking'
);

//TODO: when LDAP is intigrated, change logic to set $admin flag
if (in_array($_SERVER['PHP_AUTH_USER'], $admins)) {
    $admin = true;
}

?>
