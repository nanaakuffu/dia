<?php
  // session_start();

  require_once 'public_functions.php';
  require_once 'db_functions.php';
  require_once 'form.php';

  login_check();

  base_header('Create User');
  $level_array = array('Lower Secondary', 'IGCSE', 'AS Level', 'A Level' );
  create_header();

  echo "<br /><div class='container'>
          <div class='row'>
          <div class='col-sm-3'>
            <br />
          </div>
          <div class='col-sm-6'>";
              if (isset($_SESSION['message'])) {
                echo "<div class='panel panel-default'>
                        <div class='panel-heading'>Input Error(s)</div>
                        <div class='panel-body'>", $_SESSION['message'], "</div>
                      </div>";
                unset($_SESSION['message']);
              }
        echo  "<div class='w3-container w3-red'>
                  <h3> Add Subject </h3>
              </div>
              <div class='w3-container w3-border'>
                <form action='settings.php' method='POST' style='margin-top: 15px'>
                  <div class='form-group'>
                      <label for='subject_name'>Subject Name: </label><br />
                      <input class='form-control' type='text' id='subject_name' name='subject_name'
                            placeholder='Subject Name...' required>
                  </div>
                  <div class='form-group'>
                      <label for='subject_level'>Subject Level: </label>
                      <br />", select_data($level_array, 'subject_level', "Subject Level...", 100), "
                  </div>
                  <div class='form-group'>
                      <label for='subject_teacher'>Subject Teacher: </label></td>
                      <td><input class='form-control' type='text' id='subject_teacher' name='subject_teacher' placeholder='Subject Teacher...' required>
                  </div>
                  <input class='btn btn-primary w3-round w3-padding-large' type='submit' name='subject'
                          value='Add Subject'>
                </form>
              </div>
         </div>
         <div class='col-sm-3'>
          <br />
         </div>
        </div>
        </div>
        <hr />";

        create_footer();
?>
