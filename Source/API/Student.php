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
//  Returns a results object containing info on the students in
//  the database if successful, otherwise FALSE
function  get_students() {
    //  We want all information from the students, it can be
    //  pruned later
    $query = 'SELECT * FROM Student';

    return pg_query($GLOBALS['CONNECTION'], $query);
}


//  Returns an array of all student information from a results
//  object, otherwise FALSE.
function get_student_array($result=null) {
    if (!$result and !($result = get_students())) {
        return false;
    }

    return pg_fetch_all($result);
}


//  Echos an html list of all students in the database.
//  Returns TRUE if successful, otherwise FALSE
function display_students_list($students=null) {
    if (!$students and !($students = get_student_array())) {
        return false;
    }

    echo "<ul>";
    foreach ($students as $student) {
	echo "<li>";
        echo $student['student_firstname'] . " " . $student['student_lastname'];
        echo "</li>";
    }
    echo "</ul>";

    return true;
}

?>
