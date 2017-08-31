<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_functions.php");
    require_once("public_vars.php");

    login_check();

    $db = new Database();
    $con = $db->connect_to_db();

    if (!isset($_POST['Update']) and !isset($_POST['Delete'])) {
      include_once 'update_page.php';
      exit();
    } else {
      // This is an array that holds the keys of the wanted field names
      $field_names_array = $db->get_field_names($con, "students");
      $date_from_form = strtotime($_POST['birth_year']."/".$_POST['birth_month']."/".$_POST['birth_day']);

      if (isset($_POST['Update'])) {
        $month_days = array("4", "6", "9", "11");         // An array to hold the 30-day months
        $errors = [];
        foreach($_POST as $field=>$value) {
           /* Checking for empty data */
           if (empty($_POST[$field])) {
             $except = array("full_name", "date_of_birth", "student_number", "middle_name");
             if ( !is_element( $except, $field) ) {
               $blanks[$field] = "blank";
             }
           } else {
             /* Checking for invalid data */
             $value = trim($value);
             if ( preg_match("/name/i", $field)) {
               if (!ereg("^[A-Za-z\-].*", $value )) {
                 $errors[] = "$value is not a valid name.";
               }
             }

             if (preg_match("/mail/i", $field)) {
               if (!ereg("^.+@.+\\..+$", $value)) {
                 $errors[] ="$value is not a valid email address.";
               }
             }
             if ( preg_match("/birth/i", $field )) {
               $current_date = date("l, j F, Y");
               $curr_month = date("n", strtotime($current_date));
               $curr_year = date("Y", strtotime($current_date));

               if ($field == "birth_year") {
                 if ($value > $curr_year) {
                   $errors[] = "Birth year cannot be in the future.";
                 }
               }

               if ($field == "birth_day") {
                 if ( is_element($month_days, $_POST['birth_month']) and (int)$value > 30) {
                   $errors[] = "The month <b>".get_month_from_value($_POST['birth_month'])."</b> you gave cannot have more than 30 days.";
                 } elseif ((int)$_POST['birth_year']%4 != 0 and $_POST['birth_month'] == "2" and (int)$value > 28) {
                   $errors[] = "The year you gave is not a leap year and so February cannot have more than 28 days.";
                 } elseif ((int)$_POST['birth_year']%4 == 0 and $_POST['birth_month'] == "2" and (int)$value > 29) {
                   $errors[] = "The year you gave is a leap year but February cannot have more than 29 days.";
                 }
               }
             }
           }
         }

         if (@sizeof($errors) > 0) {
            $error_message = "";
            foreach($errors as $field => $value) {
              $error_message .= $value." Please try again <br>";
            }
            $_SESSION['message'] = $error_message;
            $_SESSION['id'] = $_POST['student_number'];

            $_POST['date_of_birth'] = date("Y-m-d", $date_from_form);
            include_once 'update_page.php';
            exit();
          } else {
            $_POST['date_of_birth'] = date("Y-m-d", $date_from_form);
            $_POST['full_name'] = $_POST['last_name'].", ".$_POST['first_name']." ".$_POST['middle_name'];

            $_POST = filter_array($_POST, $field_names_array);

            $update_data = $db->update_data($con, $_POST, "students", "student_number", $_POST['student_number']);
            if ($update_data) {
              header("Location: display_students.php");
            } else {
              echo UPDATE_ERROR;
            }
            $db->close_connection($con);
        }
      } else {
        $delete_data = $db->delete_data($con, "students", "student_number", $_POST['student_number']);
        if ($delete_data) {
          header("Location: display_students.php");
        } else {
          echo DELETE_ERROR;
        }
      }
    }
?>
