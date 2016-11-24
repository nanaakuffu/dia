<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_functions.php");

    if (isset($_POST['reset'])) {
        // Connect to database and get user access levels as he/she logs in
        $db = new Database();
        $con = $db->connect_to_db();

        $login_sql = "SELECT * FROM login_check WHERE user_name = "."'".$_POST['user_name']."'";

        $result = mysqli_query($con, $login_sql) or die("Couldn't execute query.");
        $num = mysqli_num_rows($result);

        if ($num > 0) {   // user name was found

          while($record = mysqli_fetch_assoc($result)){
            $rows[] = $record;
          }

          if ($_POST['security_question'] == $rows[0]['security_question']) {
            if ($_POST['answer'] == $rows[0]['answer']) {
              $db->close_connection($con);
              $message = "<i class='fa fa-check-square-o'></i> Your answer has been verified.<br />
                          Please click <a href='login_page.php'> here </a> to login.";
              $_SESSION['message'] = $message;
              include_once 'reset_login.php';
              exit();

            } else {
              $db->close_connection($con);
              $message = "<i class='fa fa-fw fa-close'></i> Your answer was not verified.<br />
                          Please try again here.";
              $_SESSION['message'] = $message;
              include_once 'reset_login.php';
              exit();
            }
          } else {
            $db->close_connection($con);
            $message = "<i class='fa fa-fw fa-close'></i> Your question does not match your user name.<br />
                        Please try again here.";
            $_SESSION['message'] = $message;
            include_once 'reset_login.php';
            exit();
          }
        } else {
          $db->close_connection($con);
          $message = "<i class='fa fa-fw fa-close'></i> User name does not exist!";
          $_SESSION['message'] = $message;
          include_once 'reset_login.php';
          exit();
        }
    } else {
      include 'reset_login.php';
      exit();
    }
?>