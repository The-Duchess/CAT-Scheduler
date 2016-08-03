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
require_once dirname(__FILE__) . "/../../Query/Availability.php"
require_once dirname(__FILE__) . "/../../API/Utility.php"
require_once dirname(__FILE__) . "/../../Query/Term.php"

// this php block defines the function that takes the email and student list of usernames
// this function then sends the emails

// send_mail
// PARAMETERS:
//   - $admin
//   - $student_list (an array of student usernames)
//   - $email_block  (a string)
//   - $subject
// OUTPUT:
//   - true if the emails sent correctly and inputs were valid
//   - false if
//        - $student_list is empty
//        - $email_block is empty
//        - emails didn't send correctly (cannot be evaluated)
function send_mail ($admin, $student_list, $email_block, $subject) {

     if (empty($student_list) || $email_block == "") {
          return false;
     }

     $admin_u_name = $admin . "@cat.pdx.edu";

     foreach ($student_list as $u_name) {
          $to = $u_name . "@cat.pdx.edu";
          $message = $email_block;
          $headers = 'From: ' . $admin_u_name . "\r\n" .
                     'Reply-To: ' . $admin_u_name . "\r\n" .
                     'X-Mailer: PHP/' . phpversion();

          mail($to, $subject, $message, $headers);
     }

     return true;
}

// variables

?>
