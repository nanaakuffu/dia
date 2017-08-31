<?php
  session_start();

  $user_name = $_SESSION['user_name'];
  $log_id = $_SESSION['log_id'];

  include_once 'db_functions.php';

  $db = new Database();
  $con = $db->connect_to_db();
  $today_time = date('h:i:s');

  $log_sql = "UPDATE login_details SET logout_time='$today_time' WHERE log_id ="."'".$log_id."'";
  $log_result = mysqli_query($con, $log_sql);

  $user_sql = "UPDATE users SET status='0' WHERE user_name ="."'".$user_name."'";
  $result = mysqli_query($con, $user_sql) or die("Couldn't execute query.");
  if ($result) {
    session_unset();
    session_destroy();
    $db->close_connection($con);
    header("Location: login.php");
    exit();
  }

?>
