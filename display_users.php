<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();

    $con = $db->connect_to_db();

    $fields = array('user_name', 'last_name', 'first_name', 'middle_name', 'added_by', 'status');

    base_header('Display Users');
    create_header();

    $active_users = $db->get_active_users($con);

    if (isset($_POST['submit'])) {
      $records = $db->search_data($con, "users", $fields, "last_name", $_POST['search'], 'last_name');
    } else {
      $records = $db->display_data($con, "users", $fields, "last_name");
    }

    echo "<div class='container'>",
            search_bar('display_users.php'),
           "<div class='table-responsive'>
              <table class='w3-table w3-striped w3-hoverable' align='center' cellspacing='5'>
                <tr class='w3-green'>";
                  $headers = "";
                  foreach ($fields as $key => $value) {
                    if ($value != 'user_name') {
                      if ($value == 'status') {
                        $headers .= "<th> Online </th>";
                      } else {
                        $headers .= "<th>".get_column_name($value)."</th>";
                      }
                    }
                  }
                  echo $headers .= "<th> Status </th>";
          echo "</tr>";

            if (sizeof($records) != 0) {
              foreach ($records as $key => $record) {
                echo "<tr>";
                foreach ($record as $rkey => $value) {
                  if ($rkey != 'user_name') {
                    $new_id = encrypt_data($record['user_name']);
                    $up_3 = encrypt_data('2');

                    if ($rkey == 'status') {
                      $online = ($value == 1) ? 'Yes' : 'No' ;
                      echo "<td ><a href=user_levels.php?level={$new_id}&upd={$up_3}>", $online, "</a></td>";
                    } else {
                      echo "<td ><a href=user_levels.php?level={$new_id}&upd={$up_3}>", $value, "</a></td>";
                    }
                  }
                }
                $active = (is_element($active_users, $record['user_name'])) ? 'Active' : 'Inactive';
                echo "<td ><a href=user_levels.php?level={$new_id}&upd={$up_3}>", $active, "</a></td>";

                echo "</tr>";
              }
            }

    echo "</table>
        </div>
      </div>";

    create_footer();
?>
