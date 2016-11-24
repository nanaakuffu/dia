<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    if (isset($_POST['reset'])) {

        $error = "";
        foreach ($_POST as $key => $value) {
          /* Check for wrong data */
          $value = trim($value);
          if (preg_match("/user/i", $key)) {
            if (!ereg("^[A-Za-z0-9].*", $value)) {
              $error .= "<b>$value</b> is not a valid user name.,";
            }
          }

          if (preg_match("/question/i", $key)) {
            if (!ereg("^[A-Za-z0-9].*", $value)) {
              $error .= "<b>$value</b> is not a valid hint or question.,";
            }
          }

          if (preg_match("/answer/i", $key)) {
            if (!ereg("^[A-Za-z0-9].*", $value)) {
              $error .= "<b>$value</b> does not contain valid characters.,";
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

          include_once 'reset_page.php';
          exit();

        } else {

          $db = new Database();
          $con = $db->connect_to_db();
          $SQL = "SELECT * FROM login_check WHERE user_name = "."'".$_POST['user_name']."'";

          $result = mysqli_query($con, $SQL);
          $num = mysqli_num_rows($result);
          if ($num > 0 and !isset($_SESSION['update_sec'])) {   //user name already exists
            $_SESSION['message'] = "User Name already exists";
            include_once 'reset_page.php';
          } else {
            // This is an array that holds the keys of the wanted field names
            $field_names_array = $db->get_field_names($con, "login_check");

            /* Removes unwanted field names that came from the form */
            $_POST = filter_array($_POST, $field_names_array);
            $_POST['answer'] = encrypt_data($_POST['answer']);

            if ($_SESSION['update_sec']) {
              $save_data = $db->update_data($con, $_POST, "login_check", "user_name", $_POST['user_name']);
            } else {
              $save_data = $db->add_new($con, $_POST, "login_check");
            }

            if ($save_data) {
              if (isset($_SESSION['update_sec'])) {
                unset($_SESSION['update_sec']);
              }
              header("Location: index.php");
            } else {
              echo SAVE_ERROR; // Saving was not possible
            }

            // Open the web page
            $db->close_connection($con);
            // include_once("users_page.php");
            exit();
          }
        }
      } else {
        include_once 'reset_page.php';
        exit();
    }
?>
