<?php
  /* Script name: student_page */
  session_start();

  include_once "db_functions.php";
  include_once "public_vars.php";
  include_once "public_functions.php";

  if(!isset($_POST['submit'])) {
    include_once "student_page.php";
    exit();
  } else {
      $month_days = array("4", "6", "9", "11");         // An array to hold the 30-day months
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
             if (!ereg("^[A-Za-z\-].*", $value )) {
               $errors[] = "$value is not a valid name.";
             }
           }

           if (preg_match("/mail/i", $field)) {
             if (!ereg("^.+@.+\\..+$", $value)) {
               $errors[] ="$value is not a valid email address.";
             }
           }

           if (preg_match("/birth/i", $field )) {
             $current_date = date("l, j F, Y");
             $current_month = date("n", strtotime($current_date));
             $current_year = date("Y", strtotime($current_date));

             if ($field == "birth_year") {
               if ($value > $current_year) {
                 $errors[] = "Birth year cannot be in the future.";
               }
             }

             if ($field == "birth_day") {
               if ( is_element($month_days, $_POST['birth_month']) and (int)$value > 30) {
                 $errors[] = "Birth month and Birth day is not possible";
               } elseif ((int)$_POST['birth_year']%4 != 0 and $_POST['birth_month'] == "2" and (int)$value > 28) {
                 $errors[] = "Birth month and Birth day is not possible";
               } elseif ((int)$_POST['birth_year']%4 == 0 and $_POST['birth_month'] == "2" and (int)$value > 29) {
                 $errors[] = "Birth month and Birth day is not possible";
               }
             }
           }

           if (preg_match("/class/i", $field)) {
             if ($_SESSION['is_form_teacher'] and ($_SESSION['form_name'] != $value)) {
                $errors[] = "The student, <b>". $_POST['last_name'].", ". $_POST['first_name']. " ". $_POST['middle_name']. "</b> you are trying to add is not in your class. If this is very necessary please tell your department head about it.";
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
          include_once "student_page.php";
          exit();
        } else {
          /* If the code gets here, it means the data is really clean */
          $db = new Database();

          $con = $db->connect_to_db();

          $SQL = "SELECT * FROM students";

          $result = mysqli_query($con, $SQL);

          $num_of_records = mysqli_num_rows($result);

          /* Assign values to fields like data_of_birth, student_number and full_name */
          $date_from_data = strtotime($_POST['birth_year']."/".$_POST['birth_month']."/".$_POST['birth_day']);
          $_POST['date_of_birth'] = date("Y-m-d", $date_from_data);
          $_POST['student_number'] = create_student_id($_POST['date_of_birth'], $num_of_records);
          $_POST['full_name'] = $_POST['last_name'].", ".$_POST['first_name']." ".$_POST['middle_name'];

          // This is an array that holds the keys of the wanted field names
          $field_names_array = $db->get_field_names($con, "students");

          /* Removes unwanted field names that came from the form */
          $_POST = filter_array($_POST, $field_names_array);

          // Actually save the date
          $save_data = $db->add_new($con, $_POST, "students");

          if ($save_data) {
            include_once "student_page.php";  // If saving was possible open the student page for another entry
          } else {
            echo SAVE_ERROR; // Saving was not possible
          }
          // CLosing the database
          $db->close_connection($con);
        }
  }

?>
