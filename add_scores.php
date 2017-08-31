<?php
  /* Script name: add student scores */
  session_start();

  require_once "db_functions.php";
  require_once "public_vars.php";
  require_once "public_functions.php";

  if (!isset($_POST['add_score'])) {
    include_once "student_score_page.php";
    exit();
  } else {
      $errors = [];
      $get_class = "";

      $class_name = get_class_name($_POST['class_name']);
      $subject_array = create_subject_array($_POST['class_name']);

      foreach($_POST as $field=>$value) {
         /* Checking for empty data */
         if (!empty($_POST[$field])) {
           /* Checking for invalid and empty data */
           $value = trim($value);
           if ($field == 'class_work_score') {
             if (!ereg("^[0-9].*", $value )) {
               $errors[] = "$value is not a number.";
             } elseif ($value > 40) {
               $errors[] = get_column_name($field). " cannot be more than 40.";
             } elseif (strlen(strval($value)) > 5) {
               $errors[] = get_column_name($field). " should not be more than 5 characters including the dot.";
             }
           }

           if ($field == 'student_full_name') {
             if ($value == 'Student Full Name') {
               $errors[] = "Please select a student from a class.";
             }
           }

           if ($field == 'class_name') {
             if ($value == 'Please select a class') {
               $errors[] = "Please select a class to preceed.";
             }
             if (!$_SESSION['is_admin'] and !$_SESSION['is_head']) {
               if (!preg_match("/{$_POST[$field]}/i", $class_name)) {
                 $errors[] = "You cannot input result for a class you do not teach.";
               }
             }
           }

           if ($field == 'exam_score') {
             if (!ereg("^[0-9].*", $value )) {
               $errors[] = "$value is not a number.";
             } elseif ($value > 60) {
               $errors[] = get_column_name($field). " cannot be more than 60.";
             } elseif (strlen(strval($value)) > 5) {
               $errors[] = get_column_name($field). " should not be more than 5 characters.";
             }
           }

           if ($field == 'total_score') {
             $total_score = $_POST['class_work_score'] + $_POST['exam_score'];
             if (!ereg("^[0-9].*", $value )) {
               $errors[] = "$value is not a number.";
             } elseif ($value > 100) {
               $errors[] = get_column_name($field). " cannot be more than 100.";
             } elseif (strlen(strval($value)) > 5) {
               $errors[] = get_column_name($field). " should not be more than 5 characters.";
             } elseif ( $total_score != $_POST['total_score']) {
               $errors[] = "Class Score and Exam score do not add up to total score.";
             }
           }

           if ($field == 'average_score') {
             if (!ereg("^[0-9].*", $value )) {
               $errors[] = "$value is not a number.";
             } elseif ($value > 100) {
               $errors[] = get_column_name($field). " cannot be more than 100.";
             } elseif (strlen(strval($value)) > 5) {
               $errors[] = get_column_name($field). " should not be more than 5 characters.";
             }
           }

           if ( preg_match("/grade/i", $field)) {
             if (!ereg("^[A-F].*", $value )) {
               $errors[] = "$value is not a valid grade.";
             }
             if ($_POST[$field] != comp_score_grade($_POST['total_score'])) {
               $errors[] = "Total score and grade do not match.";
             }
           }

           if ($field == 'remark') {
             if ($_POST[$field] != comp_grade_remark($_POST['grade'])) {
               $errors[] = "Grade and remarks do not match.";
             }
           }

           if ($field == 'exam_subject') {
             if (!$_SESSION['is_admin'] and !$_SESSION['is_head']) {
               if (!is_element($subject_array, $_POST[$field])) {
                 $errors[] = "You cannot enter result of a subject you do not teach.";
               }
             }
           }
         }
       }
      //  Extracting and displaying all the errors collected
       if ( @sizeof($errors) > 0) {
          $error_message = "";
          foreach($errors as $field => $value) {
            $error_message .= "<li><i class='fa-li fa fa-check-square'></i>".$value." Please try again </li>";
          }
          $_SESSION['message'] = $error_message;
          if (isset($_SESSION['update_score'])) {
            $_SESSION['id'] = $_POST['exam_id'];
          }
          include_once "student_score_page.php";
          exit();
        } else {
          /* If the code gets here, it means the data is really clean */
          $db = new Database();

          $con = $db->connect_to_db();

          // This is an array that holds the keys of the wanted field names
          $field_names_array = $db->get_field_names($con, "exams");

          // Do some minor data additions: Add teachers signature
          $_POST['teacher_initials'] = $_SESSION['initials'];

          $user_name = $_SESSION['user_name'];
          $activity_date = date('y-m-d');
          $activty_id = create_id($activity_date, "act");
          $activity_time = date('h:i:s');

          // Get class average
          $avg_fields = array('academic_year', 'academic_term', 'exam_type', 'exam_subject', 'class_name');
          $avg_criteria = filter_array($_POST, $avg_fields);

          switch ($_POST['add_score']) {
            // Actually save the data from the form.
            case 'Add Score':
              /* Remove unwanted field names that came from the form */
              $_POST = filter_array($_POST, $field_names_array);

              $_POST = secure_data_array($_POST);

              // Clean up the data
              $fields = array('academic_year', 'academic_term', 'exam_type', 'exam_subject', 'student_full_name', 'class_name');
              $criteria = filter_array($_POST, $fields);
              $data_checked = $db->data_exists($con, "exams", $fields, $criteria);

              if (!$data_checked) {
                  // Add new data
                  $save_data = $db->add_new_data($con, $_POST, "exams");

                  // Add the new activity
                  $activity_details = "Added the scores of ".$_POST['student_full_name'];
                  $act_sql = "INSERT INTO login_activity (activity_id, user_name, activity_details, activity_date, ";
                  $act_sql .= "activity_time) VALUES ('$activity_id','$user_name', '$activity_details', '$activity_date', '$activity_time')";
                  $result = mysqli_query($con, $act_sql) or die("Can't add activity.");

                  // Update the average score for the new entered data
                  $db->update_average($con, 'exams', $avg_criteria);

                  // Save default valuess
                  $_SESSION['academic_year'] = $_POST['academic_year'];
                  $_SESSION['academic_term'] = $_POST['academic_term'];
                  $_SESSION['exam_type'] = $_POST['exam_type'];
                  $_SESSION['exam_subject'] = $_POST['exam_subject'];

                  include_once 'student_score_page.php';
              } else {
                  $_SESSION['message'] = "The data you are trying to add already exists!";
                  // $_SESSION['id'] = $exam_id;
                  include_once "student_score_page.php";
              }
              break;

            case 'Update Score':
              /* Removes unwanted field names that came from the form */
              $_POST = filter_array($_POST, $field_names_array);

              $_POST = secure_data_array($_POST);
              
              // Update the data
              $save_data = $db->update_data($con, $_POST, "exams", "exam_id", $_POST['exam_id']);

              $activity_details = "Updated scores of ".$_POST['student_full_name'];
              $act_sql = "INSERT INTO login_activity (activity_id, user_name, activity_details, activity_date, ";
              $act_sql .= "activity_time) VALUES ('$activity_id','$user_name', '$activity_details', '$activity_date', '$activity_time')";
              $result = mysqli_query($con, $act_sql) or die("Can't add activity.");

              // Update the average score for the new entered data
              $db->update_average($con, 'exams', $avg_criteria);

              $_SESSION['academic_year'] = $_POST['academic_year'];
              $_SESSION['academic_term'] = $_POST['academic_term'];
              $_SESSION['exam_type'] = $_POST['exam_type'];
              $_SESSION['exam_subject'] = $_POST['exam_subject'];
              $_SESSION['class_name'] = $_POST['class_name'];

              unset($_SESSION['update_score']);
              unset($_SESSION['id']);
              header("Location: teachers_view.php");
              break;

            default:
              $delete_data = $db->delete_data($con, "exams", "exam_id", $_POST['exam_id']);

              $activity_details = "Deleted the score values of ".$_POST['student_full_name'];
              $act_sql = "INSERT INTO login_activity (activity_id, user_name, activity_details, activity_date, ";
              $act_sql .= "activity_time) VALUES ('$activity_id','$user_name', '$activity_details', '$activity_date', '$activity_time')";
              $result = mysqli_query($con, $act_sql) or die("Can't add activity.");

              if ($delete_data) {
                $db->update_average($con, 'exams', $avg_criteria);
                header("Location: teachers_view.php");
              } else {
                echo DELETE_ERROR;
              }
              break;
          }

         // Closing the database
         $db->close_connection($con);
        }
  }

?>
