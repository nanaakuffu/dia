<?php
  require_once 'db_functions.php';

  $db = new Database();
  $con = $db->connect_to_db();

  $db->backupTables($con);
  // $db->Export_Database($con);

  $db->close_connection($con);
  // include_once 'index.php';
?>
