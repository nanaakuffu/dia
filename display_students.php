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

    if ($_SESSION['is_form_teacher']) {
      $form = $_SESSION['form_name'];
      if (preg_match("/Level/i", $form)) {
        $form_name = "Level";
      } else {
        $form_name = $form;
      }
    } else {
      $form_name = '*';
    }

    $records = $db->display_data($con, "students", $fields, "full_name", $form_name);    

    $db->close_connection($con);

    echo "<div class='container'>
            <br /><div class='table-responsive'>
              <table id='display_table' class='table table-hover table-striped' align='center' cellspacing='5'>
                <thead>
                  <tr class='w3-green'>";
                    $headers = "";
                    foreach ($fields as $key => $value) {
                        if ($value != 'student_number') {
                          $headers .= "<th>".get_column_name($value)."</th>";
                        }
                    }
                    echo $headers;
            echo "</tr>
                </thead>
                <tbody>";

    if (sizeof($records) != 0) {
      foreach ($records as $key => $record) {
        echo "<tr>";
        foreach ($record as $rkey => $value) {
          if ($rkey != 'student_number') {
            $new_id = encrypt_data($record['student_number']);
            if ($rkey == 'date_of_birth') {
              $value = date_format(str_date($value), 'F j, Y');
              echo "<td ><a href=student_page.php?str={$new_id}>", $value, "</a></td>";
            } else {
              echo "<td ><a href=student_page.php?str={$new_id}>", $value, "</a></td>";
            }
          }
        }
          echo "</tr>";
      }
      echo "</tbody>
          </table>";
    } else {
      echo "</tbody></table><br />
            <div class='panel panel-default w3-pale-yellow'>
              <div class='panel-body> No record found for this search. </div>
            </div>";
    }

    echo "</div>
      </div>";

    create_footer();
?>
