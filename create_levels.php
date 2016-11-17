<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    // We use this to activate any user for this system....
    if (isset($_POST['add_access'])) {
        $error = "";
        foreach ($_POST as $key => $value) {
          /* Check for wrong data */
          $value = trim($value);
          if (preg_match("/subject/i", $key)) {
            if (!ereg("^[A-Za-z].*", $value)) {
              $error .= "<b>$value</b> is not a valid subject name.,";
            }
          }
        }

        /* Extract the various errors collected */
        if (strlen($error) > 0) {
          $errors = explode(",", $error);
          $message = "";
          foreach ($errors as $key => $value) {
            $message .= $value." Please try again.<br>";
          }
          $_SESSION['message'] = $message;
          if (isset($_POST['level_up'])) {
            $_SESSION['user_id'] = $_POST['user_name'];
          }
          include_once 'user_levels.php';
          exit();
        } else {
          // Open database connection
          $db = new Database();
          $con = $db->connect_to_db();

          // Set default values
          $_POST['is_admin'] = (isset($_POST['is_admin'])) ? $_POST['is_admin'] : 0 ;
          $_POST['is_head'] = (isset($_POST['is_head'])) ? $_POST['is_head'] : 0 ;
          $_POST['is_form_teacher'] = (isset($_POST['is_form_teacher'])) ? $_POST['is_form_teacher'] : 0 ;
          $_POST['teaches_year_7'] = (isset($_POST['teaches_year_7'])) ? $_POST['teaches_year_7'] : 0 ;
          $_POST['teaches_year_8'] = (isset($_POST['teaches_year_8'])) ? $_POST['teaches_year_8'] : 0 ;
          $_POST['teaches_year_9'] = (isset($_POST['teaches_year_9'])) ? $_POST['teaches_year_9'] : 0 ;
          $_POST['teaches_ig_1'] = (isset($_POST['teaches_ig_1'])) ? $_POST['teaches_ig_1'] : 0 ;
          $_POST['teaches_ig_2'] = (isset($_POST['teaches_ig_2'])) ? $_POST['teaches_ig_2'] : 0 ;
          $_POST['teaches_as_level'] = (isset($_POST['teaches_as_level'])) ? $_POST['teaches_as_level'] : 0 ;
          $_POST['teaches_a_level'] = (isset($_POST['teaches_a_level'])) ? $_POST['teaches_a_level'] : 0 ;
          $_POST['department'] = (isset($_POST['department'])) ? $_POST['department'] : 'None' ;
          $_POST['form_name'] = (isset($_POST['form_name'])) ? $_POST['form_name'] : 'None' ;

          // This is an array that holds the keys of the wanted field names
          $field_names_array = $db->get_field_names($con, "priveleges");

          /* Removes unwanted field names that came from the form */
          $_POST = filter_array($_POST, $field_names_array);

          if (isset($_SESSION['update_access'])) {
            $save_data = $db->update_data($con, $_POST, "priveleges", "user_name", $_POST['user_name']);
          } else {
            $save_data = $db->add_new($con, $_POST, "priveleges");
          }

          if ($save_data) {
            if (isset($_SESSION['update_access'])) {
              unset($_SESSION['update_access']);
              unset($_SESSION['user_id']);
            }
            header("Location: display_users.php");
          } else {
            echo SAVE_ERROR; // Saving was not possible
          }

          // Close the database connection
          $db->close_connection($con);

          // Exit the system
          exit();
        }
      } elseif (isset($_POST['delete_access'])) {  // We use this to deactivate any user for this system
        $db = new Database();
        $con = $db->connect_to_db();

        $delete_data = $db->delete_data($con, "priveleges", "user_name", $_POST['user_name']);
        if ($delete_data) {
          header("Location: display_users.php");
        } else {
          echo DELETE_ERROR;
        }
        // Close the connection
        $db->close_connection($con);

        // Exit the system
        exit();
      } else {
        include_once 'user_levels.php';
        exit();
    }
?>
