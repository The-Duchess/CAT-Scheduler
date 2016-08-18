<?php
function show_students($student_res) {
?>
<?php foreach ($student_res as $student_uname) {
               echo "<input type=\"radio\" checked=\"checked\" name=$student_uname value=\"TRUE\"" . "<?= (1 == 1 ? ' checked ' : ' checked ') ?>";
               echo "<label for=$student_uname> $student_uname </label>";
?>
          <br>
<?php } ?>
<?php } ?>
