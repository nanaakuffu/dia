<?php
    // session_start();

    require_once 'public_functions.php';
    require_once 'db_functions.php';

    base_header('Login Form');

    $db = new Database();
    $con = $db->connect_to_db();

    $question_array = $db->create_data_array($con, 'login_check', 'security_question', TRUE, TRUE);
?>

<div class='w3-container row' style='margin-top:-50px'>
    <div class='col-md-4'>
      <br />
    </div>
    <div class='col-md-4'>
      <div class='panel panel-default' style='margin-top:90px'>
        <div class='panel-heading'>
            <h3> Please enter the details below </h3>
        </div>
        <div class='panel-body'>
            <form action='reset.php' id='reset' method='POST'>
              <div class='form-group'>
                  <label for='user_name'>Username:</label>
                  <input type='text' class='form-control' id='user_name' name='user_name' placeholder='Enter username' readonly>
              </div>
              <div class='form-group'><label for='security_question'>Security Question:</label><br />
              <?php select_data($question_array, 'security_question', '', 100, TRUE) ?>
              </div>
              <div class='form-group'>
                  <label for='security_answer'>Answer:</label>
                  <input type='text' class='form-control' id='security_answer' name='security_answer'>
              </div>
              <button class='btn btn-primary w3-round w3-padding-medium' type='submit' name='reset' form='reset'
                      value='reset'>Reset Login <i class='fa fa-fw fa-refresh'></i></button>
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
    <div class='col-md-4'>
      <br />
    </div>
</div>
<hr>
<?php
    $db->close_connection($con);
    create_footer();
?>
