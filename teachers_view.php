<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();

    $con = $db->connect_to_db();

    $fields = array('exam_id', 'student_full_name', 'class_work_score', 'exam_score', 'total_score', 'average_score', 'grade',
              'remark');

    base_header('Student Scores');

    create_header();

    $year_array = academic_year();
    $term_array = array('First Term', 'Second Term', 'Third Term');
    $type_array = $db->create_data_array($con, 'exam_type', 'exam_name');
    $name_array = $db->create_data_array($con, 'subjects', 'subject_name', TRUE, TRUE);
    $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
    $lower_secondary = array('Year 7', 'Year 8', 'Year 9');
    $upper_secondary = array('IGCSE 1', 'IGCSE 2', 'AS Level', 'A Level');

    $records = [];
    $department = $_SESSION['department'];
    $form_name = $_SESSION['form_name'];

    // defailt values
    $year = (isset($_POST['submit'])) ? $_POST['academic_year'] : $year_array[0] ;
    $term = (isset($_POST['submit'])) ? $_POST['academic_term'] : $term_array[0] ;
    $exam = (isset($_POST['submit'])) ? $_POST['exam_type'] : $type_array[0] ;
    $class = (isset($_POST['submit'])) ? $_POST['class_name'] : $class_array[0] ;
    $name = (isset($_POST['submit'])) ? $_POST['exam_subject'] : $name_array[0] ;

    echo "<div class='container'>
              <form class='form-inline' action='teachers_view.php' method='POST' id='search' style='margin-top: 15px'>
                  <div class='form-group'>", select_data($year_array, 'academic_year', $year, 100), "</div>
                  <div class='form-group'>", select_data($term_array, 'academic_term', $term, 100), "</div>
                  <div class='form-group'>", select_data($type_array, 'exam_type', $exam, 100), "</div>
                  <div class='form-group'>", select_data($class_array, 'class_name', $class, 100), "</div>
                  <div class='form-group'>", select_data($name_array, 'exam_subject', $name, 100, TRUE), "</div>
                  <button class='btn btn-primary w3-padding-medium' form='search' type='submit' name='submit'> <i class='fa fa-search fa-fw'></i> Search </button>
              </form>
          </div><br />";
          if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $records = $db->search_by_multiple($con, "exams", $fields, $_POST, 'exam_subject');
          }

    // get class name and subjects
    if (isset($_POST['exam_subject'])) {
      $class_name = get_class_name($_POST['class_name']);
      $sub_array = get_subject($_POST['class_name']);
    }


    echo "<div class='container'>
            <div class='table-responsive'>
              <table class='w3-table w3-striped w3-hoverable' align='center' cellspacing='5'>
                <thead>
                  <tr class='w3-green'>";
                    $headers = "";
                    foreach ($fields as $key => $value) {
                      if ($value != 'exam_id') {
                          $headers .= "<th>".get_column_name($value)."</th>";
                      }
                    }
                    echo $headers;
          echo    "</tr>
                </thead>";

    if (isset($_POST['class_name'])) {
      if (sizeof($records) != 0) {
        if ($_SESSION['is_admin'] or $_SESSION['is_head']) {
          foreach ($records as $key => $record) {
            echo "<tr>";
            foreach ($record as $rkey => $value) {
              if ($rkey != 'exam_id') {
                $new_id = encrypt_data($record['exam_id']);
                $up_1 = encrypt_data('1');
                echo "<td ><a href=student_score_page.php?id={$new_id}&up_d={$up_1}>", $value, "</a></td>";
              }
            }
            echo "</tr>";
          }
          echo "</table>";
        } elseif (preg_match("/{$_POST['class_name']}/i", $class_name) and is_element($sub_array, $_POST['exam_subject'])) {
          foreach ($records as $key => $record) {
            echo "<tr>";
            foreach ($record as $rkey => $value) {
              if ($rkey != 'exam_id') {
                $new_id = encrypt_data($record['exam_id']);
                $up_1 = encrypt_data('1');
                echo "<td ><a href=student_score_page.php?id={$new_id}&up_d={$up_1}>", $value, "</a></td>";
              }
            }
            echo "</tr>";
          }
          echo "</table>";
        } else {
          echo "</table><br />
                <div class='panel panel-default w3-red'>
                  <div class='panel-body w3-text-white'> Please you do not have account priveleges to view this data. </div>
                </div>";
        }
      } else {
        echo "</table><br />
              <div class='panel panel-default w3-pale-yellow'>
                <div class='panel-body'> No record found for this search. </div>
              </div>";
      }
    }

    echo "</div>
      </div>";

    $db->close_connection($con);
    create_footer();
?>
