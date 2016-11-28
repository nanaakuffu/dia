<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();
    $con = $db->connect_to_db();

    $fields = array('exam_subject', 'class_work_score', 'exam_score', 'total_score', 'average_score', 'grade',
              'remark', 'teacher_initials');

    base_header('Student Score');

    create_header();

    $year_array = academic_year();
    $term_array = array('First Term', 'Second Term', 'Third Term');
    $type_array = $db->create_data_array($con, 'exam_type', 'exam_name');
    $name_array = $db->create_data_array($con, 'students', 'full_name');
    $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
    $records = [];
    $form_name = $_SESSION['form_name'];

    // defailt values
    $year = (isset($_POST['submit'])) ? $_POST['academic_year'] : $year_array[0] ;
    $term = (isset($_POST['submit'])) ? $_POST['academic_term'] : $term_array[0] ;
    $exam = (isset($_POST['submit'])) ? $_POST['exam_type'] : $type_array[0] ;
    $class = (isset($_POST['submit'])) ? $_POST['class_name'] : $class_array[0] ;
    $name = (isset($_POST['submit'])) ? $_POST['student_full_name'] : $retVal = (sizeof($name_array) > 0 ) ? $name_array[0] : '' ;

    echo "<div class='container'>
              <form class='form-inline' action='view_class_scores.php' method='POST' id='search' style='margin-top: 15px'>
                    <div class='form-group'>", select_data($year_array, 'academic_year', $year, 100), "</div>
                    <div class='form-group'>", select_data($term_array, 'academic_term', $term, 100), "</div>
                    <div class='form-group'>", select_data($type_array, 'exam_type', $exam, 100), "</div>
                    <div class='form-group'>
                      <select class='w3-select w3-border w3-round' name='class_name' onchange='getstudent(this.value)'>
                          <option value=''> Select a class </option>
                          <option value='Year 7'> Year 7 </option>
                          <option value='Year 8'> Year 8 </option>
                          <option value='Year 9'> Year 9 </option>
                          <option value='IGCSE 1'> IGCSE 1 </option>
                          <option value='IGCSE 2'> IGCSE 2 </option>
                          <option value='AS Level'> AS Level </option>
                          <option value='A Level'> A Level </option>
                      </select>
                    </div>
                    <div class='form-group'>
                      <select class='w3-select w3-border w3-round' name='student_full_name' id='second_choice'>
                          <option> Student Full Name </option>
                      </select>
                    </div>
                    <button class='btn btn-primary w3-round w3-padding-large' form='search' type='submit' name='submit'> <span class='glyphicon glyphicon-search'></span> </button>
              </form>
          </div>";
          if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $records = $db->search_by_multiple($con, "exams", $fields, $_POST, 'exam_subject');
          }

    if (preg_match("/Level/i", $form_name)) {
      $match = "Level";
    } else {
      $match = $form_name;
    }

    echo "<div class='container'>
            <div class='panel panel-default'>";
            if (isset($_POST['student_full_name'])){
              echo "<div class='panel-heading'>
                      <h5>", $_POST['academic_year'], " ",
                      $_POST['academic_term'], " ", $_POST['exam_type']," Examination result for <b><i>",
                      $_POST['student_full_name'], "</i></b></h5>
                    </div>";
            }
        echo "<div class='panel-body'>
                <div class='table-responsive'>
                  <table class='w3-table w3-striped w3-hoverable' align='center' cellspacing='5'>
                    <thead>
                      <tr class='w3-green'>";
                        $headers = "";
                        foreach ($fields as $key => $value) {
                            $headers .= "<th>".get_column_name($value)."</th>";
                        }
                        echo $headers;
            echo      "</tr>
                    </thead>";

    if (isset($_POST['class_name'])) {
      if (sizeof($records) > 0) {
        if ($_SESSION['is_admin'] or $_SESSION['is_head']) {
          foreach ($records as $key => $record) {
            echo "<tr>";
            foreach ($record as $rkey => $value) {
              echo "<td >", $value, "</td>";
            }
            echo "</tr>";
          }
          echo "</table>";
        } elseif ($_SESSION['is_form_teacher'] and preg_match("/{$match}/i", $_POST['class_name'])) {
          foreach ($records as $key => $record) {
            echo "<tr>";
            foreach ($record as $rkey => $value) {
              echo "<td >", $value, "</td>";
            }
            echo "</tr>";
          }
          echo "</table>";
        } else {
          echo "</table><br />
                <div class='panel panel-default w3-red'>
                  <div class='panel-body w3-text-white'> You are not the form teacher of <b><i>", $_POST['student_full_name'], "</i></b>.
                    Hence you cannot view this data. </div>
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
          </div>
        </div>
      </div>";

    $db->close_connection($con);
    create_footer();
?>
