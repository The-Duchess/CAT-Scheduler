<?php

// handle the logic of determining whether logged in user is an admin or not
require_once dirname(__FILE__) . "/login_handler.php";

?>

<html>
    <head>
        <link href="../css/bootstrap_current/css/bootstrap.min.css" rel="stylesheet">
        <title>FIDO Main</title>
    </head>
    <body>
        <div class='container'>
            <h1 class="text-center">FIDO</h1>
            <h4 class="text-center">Fully Integrated DOG Organizer</h4>

            <?php
            // display the admin links if the logged in user was determined to be
            // a scheduler admin.
            if (isset($admin) && $admin) {
            ?>
            <div class="row"><!-- beginning of row 1 -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Manage Terms</h2>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-default" href='Admin/create_term.phtml'>Create Term</a>
                        <a class="btn btn-default" href='Admin/edit_term.phtml'>Edit Term</a>
                    </div>
                </div>
            </div><!-- end of row 1 -->
            <div class="row"><!-- beginning of row 2 -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Manage Students</h2>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-default" href='Admin/edit_student.phtml'>Edit Student</a>
                        <a class="btn btn-default" href='Admin/view_term_availabilities.php'>View Submissions</a>
                    </div>
                </div>
            </div><!-- end of row 1 -->
            <?php
            }
            ?>
            <div class="row"><!-- beginning of row 3 -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Student Pages</h2>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-default" href='Student/Student_submit_availability.php'>Submit Availability</a>
                    </div>
                </div>
            </div><!-- end of row 1 -->
        </div>
    </body>
</html>
