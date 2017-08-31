<?php

  include_once 'public_functions.php';
  include_once 'form.php';

  login_check();

  base_header('Change Password');
  create_header();

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
        <h3> Change User Password </h3>
    </div>
    <form class='w3-form w3-border' action='change_password.php' method='POST'>
      <div class="row">
        <div class="col-sm-5">
          <div class='form-group'>
              <label> User Name: </label>
              <input class='form-control' type='text' name='user_name' value=''
                       id='uname' placeholder='Enter User Name' required>
          </div>
          <div class='form-group'>
              <label> Old Password: </label>
              <input class='form-control' type='password' name='old_password' value=''
                       id='opass' placeholder='Enter Old Password' required>
          </div>
        </div>
        <div class="col-sm-5">
          <div class='form-group'>
              <label> New Password: </label>
              <input class='form-control' type='password' name='new_password' value=''
                       id='npass' placeholder='Enter New Password' required>
          </div>
          <div class='form-group'>
              <label> Confirm New Password: </label>
              <input class='form-control' type='password' name='confirm_password' value=''
                       id='cpass' placeholder='Confirm New Password' required>
          </div>
        </div>
        <div class="col-sm-2">
          <div class='form-group'>
            <label> Control </label>
            <input class='btn btn-primary btn-block' type='submit' name='submit'
                  value='Change Password'>
          </div>
        </div>
      </div>
    </form>
  </div>
<hr>
<?php
    create_footer();
?>
