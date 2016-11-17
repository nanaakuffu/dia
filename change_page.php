<?php
  // session_start();

  include_once 'public_functions.php';
  include_once 'form.php';

  login_check();

  base_header('Change Password');
  create_header();

  echo "<br /><div class='w3-container row'>
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
                  <h3> Change Password </h3>
               </div>
               <div class='w3-container w3-border'>";
                  $change_form = new Form('change_password.php', 'submit', 'Change Password');

                  $change_pass = array('user_name', 'old_password', 'new_password', 'confirm_password');

                  foreach ($change_pass as $key => $value) {
                    $change_form->addField($value, get_column_name($value));
                  }
                  $change_form->login_form(65, 65);
        echo " </div>
            </div>
            <div class='col-sm-3'>
              <br />
            </div>
        </div>
      <hr>";
    create_footer();
?>
