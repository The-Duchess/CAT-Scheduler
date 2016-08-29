<?php
/*

Copyright 2016 Cat Capstone Team
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Licensed to the Computer Action Team (CAT) under one
or more contributor license agreements.  See the NOTICE file
distributed with this work for additional information
regarding copyright ownership.  The CAT Captstone Team
licenses this file to you under the Apache License, Version 2.0 (the
"License"); you may not use this file except in compliance
with the License.  You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing,
software distributed under the License is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
KIND, either express or implied.  See the License for the
specific language governing permissions and limitations
under the License.





********************************************************************************



MIT License (MIT)

Copyright (c) 2016 Capstone CAT Team

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
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

          mail($to, $subject, $message, $headers);
     }

     return true;
}
?>
