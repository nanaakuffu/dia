<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    login_check();

    $db = new Database();

    $con = $db->connect_to_db();

    $fields = array('doc_id', 'doc_title', 'doc_description', 'doc_subject', 'uploaded_by', 'date_uploaded');

    base_header('Display Uploaded Files');
    create_header();

    $records = $db->display_data($con, "doc_details", $fields, 'doc_title', "*");
    $new_records = array();

    // echo "<pre>", var_dump($records), "</pre>";

    $db->close_connection($con);

    foreach ($records as $key => $value) {
      if ($value['uploaded_by'] == $_SESSION['user_name']) {
        $new_records[$key] = $value;
      }
    }

    echo "<div class='container'>
            <br /><div class='table-responsive'>
              <table id='file_table' class='table table-hover table-striped' align='center' cellspacing='5'>
                <thead>
                  <tr class='w3-green'>
                    <th> Title </th>
                    <th> Description </th>
                    <th> Subject </th>
                    <th> Date Uploaded </th>
                  </tr>
                </thead>
                <tbody>";

    if (sizeof($new_records) != 0) {
      foreach ($new_records as $key => $record) {
        echo "<tr>";
        foreach ($record as $rkey => $value) {
          if ($rkey != 'doc_id' and $rkey != 'uploaded_by') {
            $new_id = $record['doc_id'];
            if ($rkey == 'date_uploaded') {
              $value = date_format(str_date($value), 'F j, Y');
              echo "<td ><a href=document_upload_page.php?doc_id={$new_id}>", $value, "</a></td>";
            } else {
              echo "<td ><a href=document_upload_page.php?doc_id={$new_id}>", $value, "</a></td>";
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
