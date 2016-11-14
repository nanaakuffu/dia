<?php
  /* Script name: add student scores */
  session_start();

  require_once "db_functions.php";
  require_once "public_vars.php";
  require_once "public_functions.php";

  if(!isset($_POST['add_score'])) {
    include_once "student_score_page.php";
    exit();
  } else {
      $errors = [];
      $get_class = "";
      $sub_array = array();

      $class_name = get_class_name($_POST['class_name']);
      $sub_array = get_subject($_POST['class_name']);

      // echo "<pre>", var_dump($sub_array), "</pre>", $class_name, $_POST['class_name'];
      // echo preg_match("/{$_POST['class_name']}/i", $class_name);

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
               $errors[] = get_column_name($field). " should not be more than 5 characters.";
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
           }

           if ( preg_match("/teacher/i", $field)) {
             if (!ereg("^[A-Z].*", $value )) {
               $errors[] = "$value is not a valid initial.";
             }
           }

           if ($field == 'grade') {
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
               if (!is_element($sub_array, $_POST[$field])) {
                 $errors[] = "You cannot enter result of a subject you do not teach.";
               }
             }
           }
         }
       }

       if ( @sizeof($errors) > 0) {
          $error_message = "";
          foreach($errors as $field => $value) {
            $error_message .= "<li>".$value." Please try again </li>";
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

          /* Removes unwanted field names that came from the form */
          $_POST = filter_array($_POST, $field_names_array);

          // Get class average
          $avg_fields = array('academic_year', 'academic_term', 'exam_type', 'exam_subject', 'class_name');
          $avg_criteria = filter_array($_POST, $avg_fields);

          // Actually save the date
          if (isset($_SESSION['update_score'])) {
            // Update the data
            $save_data = $db->update_data($con, $_POST, "exams", "exam_id", $_POST['exam_id']);
			
            // Update the average score for the new entered data
            $avg = $db->get_average_score($con, "exams", $avg_criteria);
            $average = (float)$avg[0]['avg_score'];
            $avg_sql = "UPDATE exams SET average_score="."'"."$average"."' WHERE ";
            foreach ($avg_criteria as $key => $value) {
              $avg_sql .= $key." = "."'".$value."'"." AND ";
            }
            $avg_sql = substr($avg_sql, 0, strlen($avg_sql) - 4);
            $result = mysqli_query($con, $avg_sql);

            unset($_SESSION['update_score']);
            unset($_SESSION['id']);
            header("Location: teachers_view.php");
          } else {
            $fields = array('academic_year', 'academic_term', 'exam_type', 'exam_subject', 'student_full_name', 'class_name');
            $criteria = filter_array($_POST, $fields);
            $data_checked = $db->data_exists($con, "exams", $fields, $criteria);

            if (!$data_checked) {
                // Add new data
                $save_data = $db->add_new($con, $_POST, "exams");
                // Update average for new data added
                $avg = $db->get_average_score($con, "exams", $avg_criteria);
                $average = (float)$avg[0]['avg_score'];
                $avg_sql = "UPDATE exams SET average_score="."'"."$average"."' WHERE ";
                foreach ($avg_criteria as $key => $value) {
                  $avg_sql .= $key." = "."'".$value."'"." AND ";
                }
                $avg_sql = substr($avg_sql, 0, strlen($avg_sql) - 4);
                $result = mysqli_query($con, $avg_sql);

                include_once 'student_score_page.php';
            } else {
                $_SESSION['message'] = "The data you are trying to add already exists!";
                // $_SESSION['id'] = $exam_id;
                include_once "student_score_page.php";
            }
          }

         // Closing the database
         $db->close_connection($con);
        }
  }

?>
