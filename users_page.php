<?php

  require_once 'public_functions.php';
  require_once 'db_functions.php';
  require_once 'form.php';

  login_check();

  base_header('Create User');

  create_header();

  echo "<br /><div class='container row'>
            <div class='col-sm-3'>
                <br />
            </div>
            <div class='col-sm-6'>";
            if (isset($_POST['login'])) {
        echo "  <div class='panel panel-default'>
                  <div class='panel-heading panel-primary'> Input Error(s) </div>
                  <div class='panel-body w3-text-red'>", $_SESSION['message'], "</div>
                </div>";
                unset($_SESSION['message']);
            }
        echo " <div class='w3-container w3-red'>
                  <h3> Become a member </h3>
               </div>

               <div class='w3-container w3-border'>";
                  $db = new Database();
                  $con = $db->connect_to_db();

                  $member_form = new Form("create_users.php", "login", "Register");
                  $fields = $db->get_field_names($con, 'users');

                  foreach ($fields as $key => $value) {
                    if ($value != 'status' and $value != 'added_by' and $value != 'edited_by') {
                      $member_form->addField($value, get_column_name($value));
                    }
                  }

                  $user_type = $db->create_data_array($con, "user_type", 'type_name');
                  $member_form->login_form(15, 255, $user_type);
                  $db->close_connection($con);

        echo " </div>
            </div>
            <div class='col-sm-3'>
                <br />
            </div>
        </div><hr>";

    create_footer();
?>
