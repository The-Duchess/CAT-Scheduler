<?php
// email automation tool
// this page allow the admin to send out a generic email to all students who
// have yet to submit a given term
// this will be accomplished by the UI having
//   - term drop down and a submit to select
//   - provide a list of all students who haven't submitted for the selected term
//     (those students also will be active; inactive students will not be listed or sent mail)
//   - provide a large text box to type your email
//   - submit button to send the email to those students
// behind the scenes this will call a script to email these students
// using the CAT's tools to do that; the email contents will be that
// which was entered into the input box and  the sender will be the
// admin user@cat url

// includes

require_once dirname(__FILE__) . "/../../Query/Student.php";
require_once dirname(__FILE__) . "/../../Query/Availability.php";
require_once dirname(__FILE__) . "/../../API/Utility.php";
require_once dirname(__FILE__) . "/../../Query/Term.php";
require_once dirname(__FILE__) . "/../../API/Admin.php";

// Database connection
if (!($CONNECTION = fido_db_connect())) {
    echo "<p>Connection Failed</p>\n";
    exit();
}

?>

<html>
    <head>
        <!-- Bootstrap -->
        <link href="../../css/bootstrap_current/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/custom/custom-buttons.css" rel="stylesheet">
        <link href="../../css/custom/custom-list-group.css" rel="stylesheet">
        <title>Email Notify</title>
    </head>
    <body>
          <div class='container'>
               <a href="../login_home.php">Back to Home</a>



<!-- generate a dropdown to select a term for selecting what students to email -->
     <?php
     // var setup
     // get list of students who have not submitted availability
     // based upon term_id
     $term_id = (int)$_GET['term_id'];
     $student_res = get_student_uname_no_availability($term_id);
     //$student_res_unames = pg_fetch_all($student_res);
     $student_list = array(); // to be completed from the subset of $student_res the admin wishes to email
     $admin_uname = $_SERVER['PHP_AUTH_USER']; // collected from the $_SERVER
     $email_text = "";
     $subject_text = "";
     ?>

<!-- UP TO THIS POINT WORKS -->

<!-- display a form to collect $subject_text -->
<!-- display a form to collect the email_text -->
<!-- display a list of students with a radio button -->
<!--
     the list of students that end up selected
     will fill in the $student_list and will get posted
     to the email
-->
<div class="main_form">
     <form action="" method="post">
          <?php
          foreach ($student_res as $student_uname) {
                         echo "<input type=\"checkbox\" checked=\"checked\" name=\"students[]\" value=$student_uname />";
                         echo "<label for=$student_uname> $student_uname </label>";
                         echo "<br>";
          }
          ?>
                    <br>
     <label>Subject:</label>
     <input type="text" name="subject" size="80"><br>
     <label>Text:   </label>
     <textarea name="text" cols="80" rows="10"></textarea><br>

     <input type="submit" name="email_information" value="Submit">
</div>
</div>
<hr>
     </body>
</html>

<?php

     if(isset($_POST['email_information'])) {
          foreach ($_POST as $name => $value) {
               // if name is any of the other settings then do not do student_uname push
               // otherwise push $name onto $student_list if the checkbox was checked
               if ($name == "subject") {
                    $subject_text = $value;
               } else if ($name == "text") {
                    $email_text = $value;
               } else if ($name == "email_information") {
                    // do nothing
               } else if ($name == "termSelect") {

               } else {

               }
          }

          foreach ($_POST['students'] as $uname) {
               array_push($student_list, $uname);
          }

          if (empty($student_list)) {

          } else {
               send_mail($admin_uname, $student_list, $email_text, $subject_text);
          }
     }

?>
