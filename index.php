<?php
    session_start();

    require_once 'public_functions.php';
    require_once 'db_functions.php';

    login_check();

    base_header('Welcome to Dayspring International Academy');
    create_header();

    $db = new Database();
    $con = $db->connect_to_db();

    $month_stamp = date('m', time());
    $sql = "SELECT full_name, class_name, house_name, date_of_birth FROM students WHERE ";
    $sql .= "MONTH(date_of_birth) = {$month_stamp} ORDER BY DAY(date_of_birth) ASC";
    $result = mysqli_query($con, $sql);
    $records = mysqli_num_rows($result);

    $rows = [];

    echo "<br /><div class='container'>
          <div class='row'>
          <div class='col-sm-3'>
            <br />
          </div>
          <div class='col-sm-6'>
            <div class='panel panel-default'>
              <div class='panel-heading w3-green'>
                <h5><i class='fa fa-fw fa-birthday-cake'></i> ", date('F', time()), " Borns </h5>
              </div>
              <div class='panel-body'>
                <table class='table-responsive w3-table w3-striped w3-hoverable' cellpadding='8' cellspacing='10'>
                  <tr class='w3-red'>
                    <th> Full Name </th>
                    <th> Class </th>
                    <th> House </th>
                    <th> Day </th>
                  </tr>";

    if ($records > 0) {
        while($record = mysqli_fetch_assoc($result)) {
          $rows[] = $record;
        }
    }

    if (sizeof($rows) > 0 ) {
      foreach ($rows as $key => $value) {
        echo "<tr>";
          foreach ($value as $vkey => $kvalue) {
            if ($vkey == 'date_of_birth') {
              $kvalue = date_format(str_date($kvalue), 'j');
              echo "<td>", $kvalue, "</td>";
            } else {
              echo "<td>", $kvalue, "</td>";
            }
          }
          echo "</tr>";
        }
      echo "</table>";
    } else {
      echo "</table>
            <div class='panel panel-default w3-pale-yellow'>
              <div class='panel-body'> No birthdays this month </div>
            </div>";
    }

    echo "</div>
        </div>
    </div>
    <div class='col-sm-3'>
      <br />
    </div>
    </div>
    </div>";

    $db->close_connection($con);
    create_footer();
?>
