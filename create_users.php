<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    if (isset($_POST['login'])) {
        $error = "";
        foreach ($_POST as $key => $value) {
            /* Check for wrong data */
            $value = trim($value);
            if (preg_match("/first/i", $key)) {
              if (!ereg("^[A-Za-z].*", $value)) {
                $error .= "<b>$value</b> is not a valid first name.,";
              }
            }

            if (preg_match("/last/i", $key)) {
              if (!ereg("^[A-Za-z].*", $value)) {
                $error .= "<b>$value</b> is not a valid last name.,";
              }
            }

            if (preg_match("/pass/i", $key)) {
              if (strlen($value) < 8 ) {
                $error .= "Password must be 8 characters or more.,";
              }
            }

            if (preg_match("/mail/i", $key)) {
              if (!ereg("^.+@.+\\..+$", $value)) {
                $error .="<b>$value</b> is not a valid email address.";
              }
            }

            if (preg_match("/initials/i", $key)) {
              if (!ereg("^[A-Z].*", $value)) {
                $error .= "<b>$value</b> is not a valid initial.,";
              }
            }
          }

        /* Extract the various errors collected */
        if (strlen($error) > 0) {
          $errors = explode(",", $error);
          $message = "";
          foreach ($errors as $key => $value) {
            $message .="<i class='fa fa-fw fa-close'></i>".$value." Please try again.<br>";
          }
          $_SESSION['message'] = $message;
          if (isset($_POST['user_up'])) {
            $_SESSION['user_id'] = $_POST['user_name'];
            include_once 'users_update.php';
          } else {
            include_once 'users_page.php';
          }

          exit();

        } else {
          $db = new Database();
          $con = $db->connect_to_db();
          $SQL = "SELECT * FROM users WHERE user_name = "."'".$_POST['user_name']."'";

          $result = mysqli_query($con, $SQL);
          $num = mysqli_num_rows($result);
          if ($num > 0 and !isset($_POST['user_up'])) {   //user name already exists
            $_SESSION['message'] = "User Name already exists";
            include_once 'users_page.php';
          } else {
            // This is an array that holds the keys of the wanted field names
            $field_names_array = $db->get_field_names($con, "users");

            /* Removes unwanted field names that came from the form */
            $_POST = filter_array($_POST, $field_names_array);

            if (isset($_SESSION['update_user'])) {
              $save_data = $db->update_data($con, $_POST, "users", "user_name", $_POST['user_name']);
              $_SESSION['full_name'] = $_POST['last_name'].", ".$_POST['first_name']." ".$_POST['middle_name'];
              $db->change_full_name($con, $_SESSION['full_name'], $_SESSION['user_name']);

              // Reset initials
              $_SESSION['initials'] = $_POST['user_initials'];
            } else {
              /* Encrypt the password data */
              $_POST['user_password'] = encrypt_data($_POST['user_password']);
              $_POST['added_by'] = $_SESSION['full_name'];
              $save_data = $db->add_new($con, $_POST, "users");
            }

            if ($save_data) {
              if (isset($_SESSION['update_user'])) {
                unset($_SESSION['update_user']);
                unset($_SESSION['id']);
                header("Location: index.php");
              } else {
                $_SESSION['new_user'] = $_POST['user_name'];
                include_once 'user_query.php';
              }
            } else {
              echo SAVE_ERROR; // Saving was not possible
            }

            // Open the web page
            mysqli_close($con);
            // include_once("users_page.php");
            exit();
          }
        }
      } else {
        include_once 'users_page.php';
        exit();
    }
?>
