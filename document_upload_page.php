<?php
  if (isset($_GET['doc_id'])) {
    session_start();
  }

  unset($_SESSION['update_doc']);

  include_once 'public_functions.php';
  include_once 'db_functions.php';

  login_check();

  base_header('Upload Document');
  create_header();

  $db = new Database();
  $con = $db->connect_to_db();
  $subject_array = $db->create_data_array($con, 'subjects', 'subject_name', TRUE, TRUE);

  if (isset($_GET['doc_id'])) {
    $doc_id = $_GET['doc_id'];
    $_POST = $db->view_data($con, "doc_details", "doc_id", $doc_id );
    $_POST['add_file'] = 'Update File';
    $_SESSION['update_doc'] = TRUE;
  }

  if (isset($_SESSION['doc_id'])) {
    $doc_id = $_SESSION['doc_id'];
    $_SESSION['update_doc'] = TRUE;
  }

  $doc_title = (isset($_POST['add_file'])) ? $_POST['doc_title'] : '' ;
  $doc_subject = (isset($_POST['add_file'])) ? $_POST['doc_subject'] : $subject_array[0] ;
  $doc_description = (isset($_POST['add_file'])) ? $_POST['doc_description'] : '' ;
  $button = (isset($_SESSION['update_doc'])) ? 'Update File' : 'Upload File' ;
?>

<br/>
  <div class="container">
    <?php
      if (isset($_SESSION['message'])) {
       echo "<div class='panel panel-default'>
               <div class='panel-heading'>Input Error(s)</div>
               <div class='panel-body'><ul class='fa-ul'>", $_SESSION['message'], "</ul></div>
             </div>";
       unset($_SESSION['message']);
     }
    ?>
    <div class='w3-container w3-green'>
      <?php if (isset($_SESSION['update_doc'])) {
        echo "<h3> Update Document </h3>";
      } else {
        echo "<h3> Upload Document </h3>";
      } ?>
    </div>
    <form class='w3-form w3-border' action='add_file.php' method='POST' enctype='multipart/form-data'>
      <div class="row">
        <div class="col-sm-5">
          <?php if (isset($_SESSION['update_doc'])) {
             echo "<input type='hidden' name='doc_id' value='{$doc_id}'>";
          } ?>
          <div class='form-group'>
              <label> Document Title: </label>
              <input class='form-control' type='text' name='doc_title' value='<?php echo $doc_title; ?>'
                       id='uname' placeholder='Type document title' required>
          </div>
          <div class='form-group'>
              <label> Document Description: </label>
              <textarea class='form-control' rows='3' name='doc_description'
                  placeholder='Describe the document' required><?php echo $doc_description; ?></textarea>
          </div>
        </div>
        <div class="col-sm-5">
          <div class='form-group'>
              <label> Document Subject: </label>
              <?php select_data($subject_array, "doc_subject", $doc_subject);  ?>
          </div>
          <?php if (!isset($_SESSION['update_doc'])) { ?>
          <div class='form-group'>
              <label> Document File: </label>
              <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
              <input type="file" name="doc_file" required>
          </div>
          <?php } ?>
        </div>
        <div class="col-sm-2">
          <div class='form-group'>
            <label> Control: </label>
            <input class='btn btn-primary btn-block' type='submit' name='add_file'
                  value='<?php echo $button; ?>'>
          </div>
        </div>
      </div>
    </form>
  </div>
<hr>
<?php
  unset($_SESSION['doc_id']);
  create_footer();
?>
