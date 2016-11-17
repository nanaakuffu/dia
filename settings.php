<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    if (isset($_POST['user_type']) ) {
        $error = "";
        foreach ($_POST as $key => $value) {
          /* Check for wrong data */
          $value = trim($value);
          if (preg_match("/type/i", $key) ) {
            if (!ereg("^[A-Za-z1-9].*", $value)) {
              $error .= "Some characters in $value does not seem to be valid.,";
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
          // extract($_POST);
          include_once 'settings_page.php';
          exit();
        } else {
          $db = new Database();
          $con = $db->connect_to_db();

          unset($_POST['user_type']);

          $save_data = $db->add_new($con, $_POST, 'user_type');
          if ($save_data) {
            header("Location: index.php");
          }
          $db->close_connection($con);
        }
      } elseif (isset($_POST['subject'])) {
        $error = "";
        foreach ($_POST as $key => $value) {
          /* Check for wrong data */
          $value = trim($value);
          if (preg_match("/subject/i", $key)) {
            if (!ereg("^[A-Za-z1-9].*", $value)) {
              $error .= "Some characters in $value does not seem to be valid.,";
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
          // extract($_POST);
          include_once 'settings_page.php';
          exit();
        } else {
          $db = new Database();
          $con = $db->connect_to_db();

          $field_names_array = $db->get_field_names($con, "subjects");

          $_POST = filter_array($_POST, $field_names_array);

          $save_data = $db->add_new($con, $_POST, 'subjects');
          if ($save_data) {
            include_once "settings_page.php";
          }
          $db->close_connection($con);
          exit();
        }
      } elseif (isset($_POST['exams_type'])) {
        $error = "";
        foreach ($_POST as $key => $value) {
          /* Check for wrong data */
          $value = trim($value);
          if (preg_match("/name/i", $key)) {
            if (!ereg("^[A-Za-z1-9].*", $value)) {
              $error .= "Some characters in $value does not seem to be valid.,";
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
          // extract($_POST);
          include_once 'settings_page.php';
          exit();
        } else {
          $db = new Database();
          $con = $db->connect_to_db();

          unset($_POST['exams_type']);

          $save_data = $db->add_new($con, $_POST, 'exam_type');
          if ($save_data) {
            header("Location: index.php");
          }
          $db->close_connection($con);
          exit();
        }
      } else {
        include_once 'settings_page.php';
        exit();
    }
?>
