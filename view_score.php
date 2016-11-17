<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();

    $con = $db->connect_to_db();

    $fields = array('exam_id', 'academic_year', 'student_full_name', 'exam_subject', 'class_name', 'total_score', 'grade', 'teacher_initials');

    base_header('Display Score')

    create_header();

    if (isset($_POST['submit'])) {
      $records = $db->search_data($con, "exams", $fields, "student_full_name", $_POST['search'], 'student_full_name');
    } else {
      $records = $db->display_data($con, "exams", $fields, "student_full_name");
    }

    echo "<div class='w3-container'>
          <div class='w3-container w3-red'>",
            search_bar('view_score.php'),
          "</div><br />
          <table class='w3-table w3-striped w3-hoverable' align='center' cellspacing='5'>
          <tr class='w3-green'>";
          $headers = "";
          foreach ($fields as $key => $value) {
            if ($value != 'exam_id') {
                $headers .= "<th>".get_column_name($value)."</th>";
            }
          }
          echo $headers;
          echo "</tr>";

    if (sizeof($records) != 0) {
      foreach ($records as $key => $record) {
        echo "<tr>";
        foreach ($record as $rkey => $value) {
          if ($rkey != 'exam_id') {
            $new_id = encrypt_data($record['exam_id']);
            $up_1 = encrypt_data('1');
            if ($rkey != 'student_full_name') {
              echo "<td ><a href=student_score_page.php?id={$new_id}&up_d={$up_1}>", $value, "</a></td>";
            } else {
              echo "<td ><a href=student_score_page.php?id={$new_id}&up_d={$up_1}>", $value, "</a></td>";
            }
          }
        }
          echo "</tr>";
      }
    }

    echo "</table></div>";

    create_footer();
?>
</body></html>
