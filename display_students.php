<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();

    $con = $db->connect_to_db();

    $fields = array('student_number','full_name', 'gender', 'class_name', 'house_name', 'date_of_birth');

    base_header('Display Students');
    create_header();

    $form_name = $_SESSION['form_name'];

    if (isset($_POST['submit'])) {
      $records = $db->search_data($con, "students", $fields, "full_name", $_POST['search'], 'full_name');
    } else {
      $records = $db->display_data($con, "students", $fields, "full_name");
    }
    $db->close_connection($con);

    echo "<div class='container'>",
            search_bar('display_students.php'),
           "<br /><div class='table-responsive'>
              <table class='w3-table w3-striped w3-hoverable' align='center' cellspacing='5'>
                <tr class='w3-green'>";
                  $headers = "";
                  foreach ($fields as $key => $value) {
                      if ($value != 'student_number') {
                        $headers .= "<th>".get_column_name($value)."</th>";
                      }
                  }
                  echo $headers;
          echo "</tr>";

    if (sizeof($records) != 0) {
      if ($_SESSION['is_admin'] or $_SESSION['is_head']) {
        foreach ($records as $key => $record) {
          echo "<tr>";
          foreach ($record as $rkey => $value) {
            if ($rkey != 'student_number') {
              $new_id = encrypt_data($record['student_number']);
              if ($rkey == 'date_of_birth') {
                $value = date_format(str_date($value), 'F j, Y');
                echo "<td ><a href=update_page.php?str={$new_id}>", $value, "</a></td>";
              } else {
                echo "<td ><a href=update_page.php?str={$new_id}>", $value, "</a></td>";
              }
            }
          }
            echo "</tr>";
        }
        echo "</table>";
      } elseif ($_SESSION['is_form_teacher']) {
        if (preg_match("/Level/i", $form_name)) {
          $match = "Level";
        } else {
          $match = $form_name;
        }

        foreach ($records as $key => $record) {
          echo "<tr>";
          foreach ($record as $rkey => $value) {
            if ($rkey != 'student_number' and preg_match("/{$match}/i", $record['class_name'])) {
              $new_id = encrypt_data($record['student_number']);
              if ($rkey == 'date_of_birth') {
                $value = date_format(str_date($value), 'F j, Y');
                echo "<td ><a href=update_page.php?str={$new_id}>", $value, "</a></td>";
              } else {
                echo "<td ><a href=update_page.php?str={$new_id}>", $value, "</a></td>";
              }
            }
          }
            echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "</table><br />
              <div class='panel panel-default w3-red'>
                <div class='panel-body w3-text-white'> Please you do not have account priveleges to view this data. </div>
              </div>";
      }
    } else {
      echo "</table><br />
            <div class='panel panel-default w3-pale-yellow'>
              <div class='panel-body> No record found for this search. </div>
            </div>";
    }

    echo "</div>
      </div>";

    create_footer();
?>
