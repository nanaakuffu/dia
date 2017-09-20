<?php
  require_once 'public_functions.php';
  base_header('Login Form');
?>

<div class='w3-container row' style='margin-top:-50px'>
    <div class='col-md-4'>
      <br />
    </div>
    <div class='col-md-4'>
      <div class='panel panel-default' style='margin-top:90px'>
        <div class='panel-heading w3-green'>
          <div class="panel-title"><b class="bitterlabel" style='font-size:20px'> Please Login Here! </b></div>
        </div><br />
        <div class='panel-body'>
          <form action='login.php' id='login' method='POST'>
              <div class='input-group'>
                <span class='input-group-addon w3-blue'><i class='glyphicon glyphicon-user'></i></span>
                <input type='text' class='form-control' id='user_name' name='user_name' placeholder='Enter username' required>
              </div><br />
              <div class='input-group'>
                <span class='input-group-addon w3-blue'><i class='glyphicon glyphicon-lock'></i></span>
                <input type='password' class='form-control' id='user_pass_word' name='user_pass_word' placeholder='Enter password' required>
              </div><br />
              <button class='btn btn-primary w3-right' type='submit' name='login' form='login'>Login <span class='glyphicon glyphicon-log-in'></button>
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
  create_footer();
?>
