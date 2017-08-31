<?php
  session_start();

  require_once "db_functions.php";
  require_once "public_vars.php";
  require_once "public_functions.php";

  $db = new Database();
  $con = $db->connect_to_db();

  if(!isset($_POST['add_file'])) {
    include_once "document_upload_page.php";
    exit();
  } else {

    // echo "<pre>", var_dump($_FILES), "</pre>";

    $field_names_array = $db->get_field_names($con, "doc_details");

    // This is an array to keep all the errors committed during data input.
    $errors = array();

    $extension = array("pdf", "docx", "doc", "ppt", "pptx", "xls", "xlsx", "jpeg", "jpg", "png", "gif");

    // This condition means I'm adding a new Document
    if (!isset($_POST['doc_id'])) {
      // echo "<pre>", var_dump($_POST), "</pre>";
      // echo "<pre>", var_dump($_FILES), "</pre>";

      $ext = pathinfo($_FILES["doc_file"]["name"], PATHINFO_EXTENSION);

      // Check if the file exists
      if(file_exists("dia_documents/".$_FILES['doc_file']['name'])) {
        array_push($errors, "File already exists. Please rename file and try again.");
      }

      // Check if file was uploaded
      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_INI_SIZE) {
        $errors[] = "File is too big. File size must not exceed 8MB.";
      }

      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_FORM_SIZE) {
        $errors[] = "File is too big. File size must not exceed 8MB.";
      }

      // Check the file type
      if (!in_array($ext, $extension)){
        array_push($errors, "File type is invalid. File types allowed are ".implode(", ", $extension));
      }

      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_EXTENSION) {
        $errors[] = "A PHP extension stopped the file upload.";
      }

      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_PARTIAL) {
        $errors[] = "The uploaded file was only partially uploaded..";
      }

      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_NO_TMP_DIR) {
        $errors[] = "Missing a temporary folder at the web server. Please restart the server.";
      }

      if ($_FILES['doc_file']['error'] == UPLOAD_ERR_CANT_WRITE) {
        $errors[] = "Failed to write file to disk. ";
      }

      // echo "<pre>", var_dump($errors), "</pre>";

      // Checl the file size
      // if($_FILES['doc_file']['size'] > $_POST['MAX_FILE_SIZE']) {
      //   array_push($errors, "File is too big. File size must not exceed 8MB.");
      // }


    }


   //  Extracting and sisplaying all the errors collected
   if ( @sizeof($errors) > 0) {
      $error_message = "";
      foreach($errors as $field => $value) {
        $error_message .= "<li><i class='fa-li fa fa-check-square'></i>".$value." Please try again </li>";
      }
      $_SESSION['message'] = $error_message;
      if (isset($_SESSION['update_doc'])) {
        $_SESSION['doc_id'] = $_POST['doc_id'];
      }
      include_once "document_upload_page.php";
      exit();
    } else {
      /* If the code gets here, it means the data is really clean */
      $today_date = date('y-m-d');

      switch ($_POST['add_file']) {
        case 'Upload File':
          $_POST['doc_id'] = create_id($today_date, "DOC");
          $_POST['doc_file'] = $_FILES['doc_file']['name'];
          $_POST['uploaded_by'] = $_SESSION['user_name'];
          $_POST['date_uploaded'] = date('Y-m-d');

          /* Removes unwanted field names that came from the form */
          $_POST = filter_array($_POST, $field_names_array);

          $_POST = secure_data_array($_POST);

          $save_data = $db->add_new_data($con, $_POST, "doc_details");

          if ($save_data) {
            move_uploaded_file($_FILES["doc_file"]["tmp_name"],"dia_documents/".$_POST['doc_file']);
            include_once "document_upload_page.php";  // If saving was possible open the student page for another entry
          } else {
            echo SAVE_ERROR; // Saving was not possible
          }
          break;

        case 'Update File':
          $_POST = filter_array($_POST, $field_names_array);

          $_POST = secure_data_array($_POST);

          // Actually update the edited data
          $update_data = $db->update_data($con, $_POST, "doc_details", "doc_id", $_POST['doc_id']);
          if ($update_data) {
            header("Location: display_uploaded_files.php");
          } else {
            echo UPDATE_ERROR;
          }
          break;

        // default:
        //   $delete_data = $db->delete_data($con, "students", "student_number", $_POST['student_number']);
        //   if ($delete_data) {
        //     header("Location: display_students.php");
        //   } else {
        //     echo DELETE_ERROR;
        //   }
        //   break;
      }
      // Closing the database
      $db->close_connection($con);
    }
  }

?>
