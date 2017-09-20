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
            if ($_POST['answer'] == decrypt_data($rows[0]['answer'])) {

              // Set the date and time you are doing this
              $today_date = date('y-m-d');
              $today_time = date('h:i:s');
              $log_id = $db->get_last_logged_in($con, $_POST['user_name']);

              $user_sql = "UPDATE users SET status='0' WHERE user_name ="."'".$_POST['user_name']."'";
              $result = mysqli_query($con, $user_sql) or die("Couldn't execute query.");
              if ($result) {
                $log_sql = "UPDATE login_details SET logout_date='$today_date', logout_time='$today_time' WHERE log_id='$log_id'";
                $log_result = mysqli_query($con, $log_sql) or die("Couldn't execute query.");
              }

              $db->close_connection($con);
              $message = "<i class='fa fa-check-square-o'></i> Your answer has been verified. Please
                        click <a href='login_page.php'> HERE </a> to login.";
              $_SESSION['message'] = $message;
              include_once 'reset_login.php';
              exit();

            } else {
              $db->close_connection($con);
              $message = "<i class='fa fa-fw fa-close'></i> Your answer and question does not match. Please
                        try again.";
              $_SESSION['message'] = $message;
              include_once 'reset_login.php';
              exit();
            }
          } else {
            $db->close_connection($con);
            $message = "<i class='fa fa-fw fa-close'></i> Your question does not match your user name.
                        Please try again.";
            $_SESSION['message'] = $message;
            include_once 'reset_login.php';
            exit();
          }
        } else {
          $db->close_connection($con);
          $message = "<i class='fa fa-fw fa-close'></i> You may not have updated your security credentials. Please contact the system Administrator.";
          $_SESSION['message'] = $message;
          include_once 'reset_login.php';
          exit();
        }
    } else {
      include 'reset_login.php';
      exit();
    }
?>
