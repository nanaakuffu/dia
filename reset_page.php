<?php

    unset($_SESSION['update_sec']);

    require_once 'public_functions.php';
    require_once 'db_functions.php';

    login_check();
    base_header('Login Form');
    create_header();

    $db = new Database();
    $con = $db->connect_to_db();

    $user_name = $_SESSION['user_name'];
    $users = $db->view_data($con, "login_check", "user_name", $user_name);

    $question = (sizeof($users) > 0) ? $users['security_question'] : '' ;
    $answer = (sizeof($users) > 0) ? decrypt_data($users['answer']) : '' ;
    $button = (sizeof($users) > 0) ? 'Update Security' : 'Add Security' ;
    $_SESSION['update_sec'] = (sizeof($users) > 0) ? TRUE : FALSE ;

    if (isset($_SESSION['message'])) {
      $user_name = $_SESSION['user_name'];
    }
?>
<div class='w3-container row' style='margin-top:-50px'>
    <div class='col-sm-4'>
      <br />
    </div>
    <div class='col-sm-4'>
      <div class='panel panel-default' style='margin-top:90px'>
        <div class='panel-heading'>
            <h3> Enter Security Details Here </h3>
        </div>
        <div class='panel-body'>
            <form action='reset_security.php' id='reset' method='POST'>
              <div class='form-group'>
                <label for='user_name'>Username:</label>
                <input type='text' class='form-control' id='user_name' name='user_name'
                  value='<?php echo $user_name; ?>' readonly>
              </div>
              <div class='form-group'>
                <label for='security_question'>Security Question:</label><br />
                <input type='text' class='form-control' id='question' name='security_question'
                placeholder='Please type your security question' value='<?php echo $question; ?>' required>
              </div>
              <div class='form-group'>
                  <label for='answer'>Answer:</label>
                  <input type='text' class='form-control' id='answer' name='answer'
                  placeholder='Enter security answer' value='<?php echo $answer; ?>' required>
              </div>
              <button class='btn btn-primary w3-round w3-padding-medium' type='submit' name='reset_sec'
                  form='reset' value='reset'> <?php echo $button; ?> <i class='fa fa-fw fa-plus'></i></button>
            </form>
        </div>
      </div>
      <?php
        if (isset($_SESSION['message'])) {
          echo "<div class='panel panel-default'>
                    <div class='panel-body'>", $_SESSION['message'], "</div>
                </div>";
          unset($_SESSION['message']);
        }
      ?>
    </div>
    <div class="col-sm-4">
      <br />
    </div>
</div>

<?php
  $db->close_connection($con);
  create_footer();
?>
