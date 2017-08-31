<?php
  if (isset($_GET['up'])) {
    session_start();
    $_SESSION['update_user'] = TRUE;
  }

  require_once 'public_functions.php';
  require_once 'db_functions.php';

  login_check();

  base_header("Update Users");
  create_header();

  $db = new Database();
  $con = $db->connect_to_db();

  $type_array = $db->create_data_array($con, "user_type", 'type_name');

  if (isset($_GET['str_1'])) {
    $user_name = decrypt_data($_GET['str_1']);
    $_POST = $db->view_data($con, "users", "user_name", $user_name);
    $_POST['login'] = 'Update';
  } elseif (isset($_SESSION['message'])) {
    $user_name = $_SESSION['user_id'];
  }

  $user_type = (isset($_POST['login'])) ? $_POST['user_type'] : $type_array[0] ;
  $e_mail = (isset($_POST['login'])) ? $_POST['e_mail'] : '' ;
  $first_name = (isset($_POST['login'])) ? $_POST['first_name'] : '' ;
  $middle_name = (isset($_POST['login'])) ? $_POST['middle_name'] : '' ;
  $last_name = (isset($_POST['login'])) ? $_POST['last_name'] : '' ;
  $initials = (isset($_POST['login'])) ? $_POST['user_initials'] : '' ;

  echo "<br /><div class='container'>
          <div class='row'>
            <div class='col-sm-3'>
              <br />
            </div>
            <div class='col-sm-6'>";
              if (isset($_SESSION['message'])) {
                echo "<div class='panel panel-default'>
                        <div class='panel-heading'>Input Error(s)</div>
                        <div class='panel-body'><ul class='fa-ul'>", $_SESSION['message'], "</ul></div>
                      </div>";
                unset($_SESSION['message']);
              }
        echo  "<div class='w3-container w3-green '>
                <h3>", $_SESSION['full_name'], "</h3>
              </div>
              <div class='w3-container w3-border w3-round'>
                <form class='w3-form' action='create_users.php' method='POST'>
                  <input type='hidden' name='user_name' value='{$user_name}'>

                  <div class='form-group'>
                    <label class='w3-validate' for='e_mail'> Email Address: </label>
                    <input class='form-control' type='email' name='e_mail' value='{$e_mail}'
                            id='e_mail' required>
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='first_name'> First Name: </label>
                    <input class='form-control' type='text' name='first_name' value='{$first_name}'
                            id='first_name' required>
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='middle_name'>Middle Name: </label>
                    <input class='form-control' type='text' name='middle_name' value='{$middle_name}'
                            id='middle_name'>
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='last_name'> Last Name: </label>
                    <input class='form-control' type='text' name='last_name' value='{$last_name}'
                            id='last_name' required>
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='initials'> Initials: </label>
                    <input class='form-control' type='text' name='user_initials' value='{$initials}'
                            id='initials' required>
                  </div>

                  <input type='hidden' name='user_up' value='up_d'>
                  <input class='btn btn-primary w3-padding-medium' type='submit' name='login' value='Update'>
                </form>
              </div>
            </div>
            <div class='col-sm-3'>
              <br />
            </div>
          </div>
        </div>";

      unset($_POST);
      unset($_SESSION['user_id']);

      $db->close_connection($con);
      create_footer();
?>
