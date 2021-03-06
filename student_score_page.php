<?php
  if (isset($_GET['up_d'])) {
    session_start();
  }

  unset($_SESSION['update_score']);

  require_once 'public_functions.php';
  require_once 'form.php';
  require_once 'db_functions.php';

  login_check();

  base_header('Student Score');

  create_header();

  $db = new Database();
  $con = $db->connect_to_db();

  //Define arrays to fill combo boxes
  $year_array = academic_year();
  $term_array = array('First Term', 'Second Term', 'Third Term');
  $type_array = $db->create_data_array($con, 'exam_type', 'exam_name');
  $name_array = $db->create_data_array($con, 'students', 'full_name', TRUE, TRUE);
  $subject_array = $db->create_data_array($con, 'subjects', 'subject_name', TRUE, TRUE);
  $remark_array = array('Excellent', 'Very Good', 'Good', 'Satisfactory', 'Fair', 'Fail');
  // $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS/A Level");

  if (isset($_GET['id'])) {
    $exam_id = decrypt_data($_GET['id']);
    $_POST = $db->view_data($con, "exams", "exam_id", $exam_id);
    $_POST['add_score'] = 'Update Scores';
    $_SESSION['update_score'] = TRUE;
  }

  if (isset($_SESSION['id'])) {
    $exam_id = $_SESSION['id'];
    $_SESSION['update_score'] = TRUE;
  }

  // Set default values
  $year = (isset($_POST['add_score'])) ? $_POST['academic_year'] : $_year = (isset($_SESSION['academic_year'])) ? $_SESSION['academic_year'] : $year_array[0];
  $term = (isset($_POST['add_score'])) ? $_POST['academic_term'] : $_term = (isset($_SESSION['academic_term'])) ? $_SESSION['academic_term'] : $term_array[0] ;
  $ex_type = (isset($_POST['add_score'])) ? $_POST['exam_type'] : $_type = (isset($_SESSION['exam_type'])) ? $_SESSION['exam_type'] : $type_array[0] ;
  $subject = (isset($_POST['add_score'])) ? $_POST['exam_subject'] : $_subject = (isset($_SESSION['exam_subject'])) ? $_SESSION['exam_subject'] : $subject_array[0] ;
  $full_name = (isset($_POST['add_score'])) ? $_POST['student_full_name'] : "" ;
  $initials = (isset($_POST['add_score'])) ? $_POST['teacher_initials'] : "" ;
  $class = (isset($_POST['add_score'])) ? $_POST['class_work_score'] : "" ;
  $class_name = (isset($_POST['add_score'])) ? $_POST['class_name'] : "" ;
  $exam = (isset($_POST['add_score'])) ? $_POST['exam_score'] : "" ;
  $total = (isset($_POST['add_score'])) ? $_POST['total_score'] : "" ;
  $grade = (isset($_POST['add_score'])) ? $_POST['grade'] : "" ;
  $remarks = (isset($_POST['add_score'])) ? $_POST['remark'] : "" ;

  echo "<br /><div class='container'>";
          if (isset($_SESSION['message'])) {
                echo "<div class='panel panel-default'>
                        <div class='panel-heading'>Input Error(s)</div>
                        <div class='panel-body'><ul class='fa-ul'>", $_SESSION['message'], "</ul></div>
                      </div>";
                unset($_SESSION['message']);
          }
  echo "  <div class='w3-container w3-red'>";
              if (isset($_SESSION['update_score'])) {
                echo "<h3> Update Student Score </h3>";
              } else {
                echo "<h3> Add Student Score </h3>";
              }
  echo "  </div>
          <form class='w3-form w3-border w3-round' action='add_scores.php' method='POST'>
            <div class='row'>
              <div class='col-sm-4'>";
                if (isset($_SESSION['update_score'])) {
                   echo "<input type='hidden' name='exam_id' value='{$exam_id}'>";
                }
            echo "<div class='form-group'><label for='academic_year'>Academic Year : </label><br />
                        ", select_data($year_array, 'academic_year', $year, 100), "</div>
                  <div class='form-group'><label for='academic_term'>Academic Term : </label><br />
                        ", select_data($term_array, 'academic_term', $term, 100), "</div>
                  <div class='form-group'><label for='exam_type'>Exam Type : </label><br />
                        ", select_data($type_array, 'exam_type', $ex_type, 100), "</div>
                  <div class='form-group'><label for='exam_subject'> Subject : </label><br />
                        ", select_data($subject_array, 'exam_subject', $subject, 100, TRUE), "</div>
              </div>
              <div class='col-sm-4'>
                  <div class='form-group'><label for='class_name'> Class: </label><br />";
                    if (isset($_SESSION['update_score'])) {
                      echo "<input class='form-control' type='text' id='remark' name='class_name' value='{$class_name}'
                               readonly>";
                    } else {
                      echo "<select class='form-control' name='class_name' onchange='getstudent(this.value)'>
                                <option value=''> Please select a class </option>
                                <option value='Year 7'> Year 7 </option>
                                <option value='Year 8'> Year 8 </option>
                                <option value='Year 9'> Year 9 </option>
                                <option value='IGCSE 1'> IGCSE 1 </option>
                                <option value='IGCSE 2'> IGCSE 2 </option>
                                <option value='AS Level'> AS Level </option>
                                <option value='A Level'> A Level </option>
                            </select>";
                    }
            echo "</div>
                  <div class='form-group'><label for='student_full_name'>Student Full Name : </label><br />";
                    if (isset($_SESSION['update_score'])) {
                      echo "<input class='form-control' type='text' id='remark' name='student_full_name' value='{$full_name}'
                               readonly>";
                    } else {
                      echo "<select class='form-control' name='student_full_name' id='second_choice'>
                                <option> Student Full Name </option>
                            </select>";
                    }
            echo "</div>
                  <div class='form-group'><label class='w3-validate' for='cscore'>Class Score : </label><br />
                        <input class='form-control' type='text' name='class_work_score' value='{$class}'
                          id='classscore' required></div>
                  <div class='form-group'><label class='w3-validate' for='escore'>Exam Score : </label><br />
                        <input class='form-control' type='text' name='exam_score' value='{$exam}'
                          id='examscore' required></div>
              </div>
              <div class='col-sm-4'>
                  <div class='form-group'><label class='w3-validate' for='totalscore'>Total Score : </label><br />
                        <input class='form-control' type='text' id='totalscore'
                        onfocus='getscoreandgrade(totalscore, classscore, examscore, grades, remarks)' name='total_score'
                        value='{$total}' readonly></div>
                  <div class='form-group'><label class='w3-validate' for='grade'>Grade : </label><br />
                      <input class='form-control' type='text' id='grades' name='grade' value='{$grade}' readonly></div>
                  <div class='form-group'><label class='w3-validate' for='remark'>Remark : </label><br />
                      <input class='form-control' type='text' id='remarks' name='remark' value='{$remarks}' readonly></div>
                  <input type='hidden' name='teacher_initials' value='{$initials}'><br />";
                  if (!isset($_SESSION['update_score'])) {
                    echo "<input class='btn btn-primary w3-round w3-padding-medium' type='submit' name='add_score'
                          value='Add Score'>";
                  } else {
                    if ($_SESSION['is_admin']) {
                      echo "<div class='btn-group'>
                              <input class='btn btn-primary w3-padding-medium' type='submit' name='add_score'
                                  value='Update Score'>
                              <input class='btn btn-primary w3-padding-medium' type='submit' name='add_score'
                                  value='Delete Score'>
                              <a class='btn btn-primary w3-padding-medium' href='teachers_view.php'>Back</a>
                            </div>";
                    } else {
                      echo "<div class='btn-group'>
                              <input class='btn btn-primary w3-padding-medium' type='submit' name='add_score'
                                  value='Update Score'>
                              <a class='btn btn-primary w3-padding-medium' href='teachers_view.php'>Back</a>
                            </div>";
                    }
                  }
    echo "      </div>
            </div>
          </form>

        </div>";

        unset($_SESSION['id']); // Unset the id

        create_footer();
?>
