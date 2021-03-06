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
//if (!($CONNECTION = fido_db_connect())) {
//    echo "<p>Connection Failed</p>\n";
//    exit();
//}

?>

<html>
     <head>
         <meta charset="utf-8">
         <title>Email Notify</title>
         <link rel="stylesheet" href="../../css/bootstrap_current/css/bootstrap.min.css">
         <link rel="stylesheet" href="../../JQuery-1.2/UI/jquery-ui-darkness/jquery-ui.min.css">
    </head>
    <body>
         <br>
          <div class='container'> <!--  begin container  -->
               <?php
                    if (!($CONNECTION = @fido_db_connect())) {?>
                         <div class="alert alert-danger text-center" role="alert">
                             <strong>FAILURE!</strong>
                             <br>
                             ERROR: unable to connect to database
                         </div>
                    <?php
                    } else {

                    }
               ?>
               <div class='panel panel-primary'> <!-- begin panel -->
                    <div class='panel panel-heading'>
                         <h2 class='panel-title'>Email Notify<h2>
                    </div>
                    <div class='panel-body'> <!--  begin panel body  -->
                    <div class='row'> <!--  begin row  -->

<!-- generate a dropdown to select a term for selecting what students to email -->
     <?php
     // var setup
     // get list of students who have not submitted availability
     // based upon term_id
     $term_id = (int)$_GET['term_id'];
     $student_res = get_student_uname_no_availability($term_id);

     if (!($student_res = get_student_uname_no_availability($term_id))) { ?>
          <div class="alert alert-danger text-center" role="alert">
              <strong>FAILURE!</strong>
              <br>
              ERROR: unable to fetch students
          </div>
     <?php }

     //$student_res_unames = pg_fetch_all($student_res);
     $student_list = array(); // to be completed from the subset of $student_res the admin wishes to email
     $admin_uname = $_SERVER['PHP_AUTH_USER']; // collected from the $_SERVER
     $email_text = "";
     $subject_text = "";
     ?>

<!-- display a form to collect $subject_text -->
<!-- display a form to collect the email_text -->
<!-- display a list of students with a check box -->
<!--
     the list of students that end up selected
     will fill in the $student_list and will get posted
     to the email
-->
     <form action="" method="post">

<!--
          <div style="height:300px;
          width:300px;border:0px solid
          #0F0; overflow:auto">
-->
          <div class='form-group'> <!--  begin checkbox form group  -->
               <div class='col-md-3'>
                    <div style='max-height:275px; overflow: auto'>
                         <?php
                         foreach ($student_res as $student_uname) { ?>
                              <div class='input-group has-success'>
                                   <span class='input-group-addon'>
                                        <input type="checkbox" name="students[]" value="<?php echo $student_uname; ?>" checked />
                                   </span>
                                   <span class='input-group-addon' style='min-width: 100px'><?php echo $student_uname; ?></span>
                              </div> <?php
                         }
                         ?>
                    </div>
               </div>
          </div> <!--  end checkbox form group  -->
     <!--  </div end scrollbox  -->
     <div class='col-md-9'> <!--  text area  -->
     <div class="form-group has-error" id="subject-form"> <!--  begin subject form group  -->
          <div class='input-group'>
               <span class="input-group-addon" id='subject-addon'>Subject</span>
               <input class="form-control" type="text" name="subject" id="subject" aria-describedby='subject-addon' />
          </div>
     </div> <!--  end subject form group  -->

<!--
     <label>Subject:</label>
     <br>
     <input type="text" name="subject" class="form-control" size="80" style'float: left'><br>
-->

     <div class="form-group has-error" id="text-form"> <!--  begin subject form group  -->
          <textarea name="text" class="form-control" id="text" rows="10" style'float: left' placeholder='Message body'></textarea>
     </div> <!--  end subject form group  -->

<!--
     <label>Text:</label>
     <br>
     <textarea name="text" class="form-control" cols="80" rows="10" style'float: left'></textarea><br>
-->

     </div> <!--  end text area  -->
     </div> <!--  end row  -->
          </div> <!--  end panel body  -->
     </div> <!--  end panel  -->
     <input type="submit" class='btn btn-primary' name="email_information" value="send email(s)">
     <a href="../login_home.php" class='btn btn-primary' style='float: right'>Back to Home</a>
</div> <!--  end container  -->
          <script src="../../JQuery-1.2/jquery-1.12.4.js"></script>
          <script src="../../JQuery-1.2/UI/jquery-ui-darkness/jquery-ui.min.js"></script>
          <script type="text/javascript">

               $("#subject").change( function() {
                    if ($(this).val() != "") {
                         $("#subject-form").removeClass("has-error");
                         $("#subject-form").addClass("has-success");
                    } else {
                         $("#subject-form").removeClass("has-success");
                         $("#subject-form").addClass("has-error");
                    }
               });

               $("#text").change( function() {
                    if ($(this).val() != "") {
                         $("#text-form").removeClass("has-error");
                         $("#text-form").addClass("has-success");
                    } else {
                         $("#text-form").removeClass("has-success");
                         $("#text-form").addClass("has-error");
                    }
               });

          </script>
     </body>
</html>

<?php

     if(isset($_POST['email_information'])) {

          // provide notification that email was sent

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

          if (isset($_POST['students']) ) {
               foreach ($_POST['students'] as $uname) {
                    array_push($student_list, $uname);
               }
          }

          if (empty($student_list)) {
          ?>
               <div class="alert alert-danger text-center" role="alert">
                   <strong>You Must Have Students Selected</strong>
                   <br>
                   ERROR: You Need To Select At Least 1 Student
               </div>
          <?php
          } else {
               @send_mail($admin_uname, $student_list, $email_text, $subject_text);
          ?>
               <div class="alert alert-success text-center" role="alert">
                   <strong>SUCCESS!</strong>
                   <br>
                   "Emails Sent
               </div>
          <?php
          }
     }

?>
