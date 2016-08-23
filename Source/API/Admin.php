<?php
require_once dirname(__FILE__) . "/../Query/Student.php";
//  Generates HTML code for displaying a list of students
//  who have not submitted availability for a given term,
//  returns TRUE if successful otherwise FALSE.
//  PARAMETERS:
//      term_id:    the id of the term in question
function list_students_no_availability($term_id) {
    //  Get students
    if (!($results = retrieve_students_no_availability($term_id))) {
        return false;
    }
    $students = pg_fetch_all($results);
    //Generate HTML code
    echo "<ul>\n";
    foreach ($students as $student) {
        echo "<li>" . $student['student_username'] . "</li>\n";
    }
    echo "</ul>\n";
    return true;
}
function bootstrapped_list_students_no_availability($term_id) {
    //  Get students
    if (!($results = retrieve_students_no_availability($term_id))) {
        return false;
    }
    $students = pg_fetch_all($results);
    //Generate HTML code
    echo "<ul class=\"list-group\">\n";
    foreach ($students as $student) {
        echo "<li class=\"list-group-item\">" . $student['student_username'] . "</li>\n";
    }
    echo "</ul>\n";
    return true;
}

// generates a list of student usernames for those that
// have not submitted availability for a term
// PARAMETERS:
//        - term_id
function get_student_uname_no_availability($term_id) {
     if (!($results = retrieve_students_no_availability($term_id))) {
         return false;
     }
     $students = pg_fetch_all($results);
     $students_uname = array();

     foreach ($students as $student) {
          array_push($students_uname, $student['student_username']);
     }

     return $students_uname;
}

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
          $params = array();

          // extra params are hardset for now
          // this prevents security issues with shelling
          // these params are
          //   the mailhost: mailhost.cecs.pdx.edu
          //   the port: 25
          //
          // the arguments should look like
          //

          //  read the configuration file
          //$ini_arr = parse_ini_file(dirname(__FILE__) . "/../../fidoconfig.ini", true);
          //$dbconf = $ini_arr['database'];

          /*
          // --
               $mail = new PHPMailer();
               $mail->IsSMTP();
               $mail->CharSet = 'UTF-8';
               $mail->Host = "mailhost.cecs.pdx.edu";
               $mail->SMTPDebug = 0;
               $mail->SMTPAuth = true;
               $mail->Port = 25;
               $mail->Username = {$dbconf['mail_username']};
               $mail->Password = {$dbconf['mail_pass']};
          // --
          */

          mail($to, $subject, $message, $headers);
     }

     return true;
}

?>
