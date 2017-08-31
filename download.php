<?php

  include_once 'db_functions.php';

  if (isset($_POST['download'])) {
    $db = new Database();
    $con = $db->connect_to_db();

    $backup = $db->backup_database($con);

    if ($backup) {
      $message="<i class='fa fa-fw fa-check-box'></i> Database backup completed succesfully!";
      $_SESSION['message'] = $message;
      include_once 'down_page.php';
    } else {
      $message="<i class='fa fa-fw fa-close'></i>Database backup completed succesfully!";
      $_SESSION['message'] = $message;
      include_once 'down_page.php';
    }

    $db->close_connection($con);
  }
?>
