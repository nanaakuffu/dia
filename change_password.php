<?php
/* Script name: student_page */
session_start();

require_once "db_functions.php";
require_once "public_vars.php";
require_once "public_functions.php";

if(!isset($_POST['submit'])) {
  include_once "change_page.php";
  exit();
} else {
    $errors = [];
    foreach($_POST as $field=>$value) {
      /* Checking for invalid and empty data */
      $value = trim($value);
      if ( preg_match("/name/i", $field)) {
        if (!ereg("^[A-Za-z\-].*", $value )) {
           $errors[] = "$value is not a valid name.";
        }
      }

      if ($field == 'new_password') {
        if (strlen($value) < 8 ) {
           $errors[] = "New password must be 8 characters or more.";
        }
      }

      if ($field == 'confirm_password') {
        if ($_POST['new_password'] != $_POST['confirm_password']) {
           $errors[] = "Confirmed password does not match new password.";
        }
      }
    }

    if ( @sizeof($errors) > 0) {
      $error_message = "";
      foreach($errors as $field => $value) {
        $error_message .= "<li><i class='fa-li fa fa-check-square'></i>".$value." Please try again </li>";
      }
      $_SESSION['message'] = $error_message;
      include_once "change_page.php";
      exit();
    } else {
      /* If the code gets here, it means the data is really clean */
      $db = new Database();
      $con = $db->connect_to_db();
      $check_user_data = [];

      $check_user_sql = "SELECT user_name, user_password FROM users WHERE user_name = "."'".$_POST['user_name']."'";

      $result = mysqli_query($con, $check_user_sql);
      $num_of_records = mysqli_num_rows($result);

      if ($num_of_records > 0) { //User name was found
        // encrypt the password
        $password = encrypt_data($_POST['old_password']);
        // create an array from the query
        while($record = mysqli_fetch_assoc($result)) {
          $rows[] = $record;
        }

        foreach ($rows as $key => $value) {
          foreach ($value as $vkey => $kvalue) {
            $check_user_data[$vkey] = $kvalue;
          }
        }

        if ($check_user_data['user_password'] == $password) { // User password matches user name given
          $pass_data = [];
          $pass_data['user_name'] = $_POST['user_name'];
          $pass_data['user_password'] = encrypt_data($_POST['new_password']);

          $change_data = $db->update_data($con, $pass_data, 'users', 'user_name', $pass_data['user_name']);
          if ($change_data) {
            header('Location: index.php');
          }
        } else {
          $_SESSION['message'] = 'User name and old password do not match.';
          include_once 'change_page.php';
        }
      } else {
        $_SESSION['message'] = "User name, <b>". $_POST['user_name']. "</b>,  does not exist.";
        include_once 'change_page.php';
      }
      // CLosing the database
      $db->close_connection($con);
      exit();
    }
  }
?>
