<?php
  session_start();

  require_once "db_functions.php";
  require_once "public_vars.php";
  require_once "public_functions.php";

  $db = new Database();
  $con = $db->connect_to_db();

  if(!isset($_POST['add_student'])) {
    include_once "student_page.php";
    exit();
  } else {
    // This keeps the array of the original date sent from the form.
    $sent_date = change_string_to_date($_POST['date_of_birth']);
    $date_from_data = strtotime($sent_date[2]."/".$sent_date[0]."/".$sent_date[1]);

    // An array to hold the 30-day months
    $month_days = array("4", "6", "9", "11");
    // This is an array that holds the keys of the wanted field names
    $field_names_array = $db->get_field_names($con, "students");
    // This is an array to keep all the errors committed during data input.
    $errors = [];

    foreach($_POST as $field => $value) {
       /* Checking for empty data */
       if (empty($_POST[$field])) {
         $except = array("full_name", "date_of_birth", "student_number", "middle_name");
         if ( !is_element( $except, $field) ) {
           $blanks[$field] = "blank";
         }
       } else {
         /* Checking for invalid and empty data */
         $value = trim($value);
         if ( preg_match("/name/i", $field)) {
           if (!preg_match("/^[a-zA-Z ]*$/",$value)) {
             $errors[] = "$value is not a valid name.";
           }
         }

         if (preg_match("/mail/i", $field)) {
           if (!preg_match("/^.+@.+\\..+$/", $value)) {
             $errors[] ="$value is not a valid email address.";
           }
         }

         if ($field == "date_of_birth") {
           $current_date = date("l, j F, Y");
           $current_year = date("Y", strtotime($current_date));
           $current_month = date("m", strtotime($current_date));
           $current_day = date("d", strtotime($current_date));

           if ( $sent_date[2] > $current_year) {
             $errors[] = "Birth year cannot be in the future.";
           }

           if ( $sent_date[2] == $current_year && $sent_date[0] > $current_month ) {
             $errors[] = "Birth month cannot be in the future. ".$current_month;
           }

           if ( $sent_date[2] == $current_year && $sent_date[0] == $current_month && $sent_date[1] > $current_day ) {
             $errors[] = "Birth day cannot be in the future.";
           }
         }

         if (preg_match("/class/i", $field)) {
           if ($_SESSION['is_form_teacher'] and ($_SESSION['form_name'] != $value)) {
              $errors[] = "The student, <b>". $_POST['last_name'].", ". $_POST['first_name']. " ". $_POST['middle_name']. "</b> you are trying to add is not in your class.
              If it is necessary to do this, please speak to your department head about it.";
           }
         }
       }
     }

     //  Extracting and sisplaying all the errors collected
     if ( @sizeof($errors) > 0) {
        $error_message = "";
        foreach($errors as $field => $value) {
          $error_message .= "<li><i class='fa-li fa fa-check-square'></i>".$value." Please try again </li>";
        }
        $_SESSION['message'] = $error_message;
        if (isset($_SESSION['update_student'])) {
          $_SESSION['id'] = $_POST['student_number'];
        }
        include_once "student_page.php";
        exit();
      } else {
        /* If the code gets here, it means the data is really clean */

        /* Assign values to fields like data_of_birth, student_number and full_name */
        $_POST['date_of_birth'] = date("Y-m-d", $date_from_data);

        $_POST['full_name'] = $_POST['last_name'].", ".$_POST['first_name']." ".$_POST['middle_name'];

        switch ($_POST['add_student']) {
          case 'Add Student':
            $SQL = "SELECT * FROM students";
            $result = mysqli_query($con, $SQL);
            $num_of_records = mysqli_num_rows($result);
            $_POST['student_number'] = create_student_id($_POST['date_of_birth'], $num_of_records);

            /* Removes unwanted field names that came from the form */
            $_POST = filter_array($_POST, $field_names_array);

            $_POST = secure_data_array($_POST);

            $save_data = $db->add_new_data($con, $_POST, "students");

            if ($save_data) {
              include_once "student_page.php";  // If saving was possible open the student page for another entry
            } else {
              echo SAVE_ERROR; // Saving was not possible
            }
            break;

          case 'Update Student':
            $_POST = filter_array($_POST, $field_names_array);

            $_POST = secure_data_array($_POST);

            // Actually update the edited data
            $update_data = $db->update_data($con, $_POST, "students", "student_number", $_POST['student_number']);
            if ($update_data) {
              header("Location: display_students.php");
            } else {
              echo UPDATE_ERROR;
            }
            break;

          default:
            $delete_data = $db->delete_data($con, "students", "student_number", $_POST['student_number']);
            if ($delete_data) {
              header("Location: display_students.php");
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
