<?php

  require_once 'public_functions.php';
  require_once 'db_functions.php';
  require_once 'form.php';

  login_check();
  base_header('Create User');
  create_header();

  $db = new Database();
  $con = $db->connect_to_db();

  // $fields = $db->get_field_names($con, 'users');
  $type_array = $db->create_data_array($con, "user_type", 'type_name');
  $db->close_connection($con);
?>
<br />
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
      <h3> Add New User </h3>
  </div>
  <form class='w3-form w3-border' action='create_users.php' method='POST'>
    <div class='row'>
      <div class='col-sm-5'>
        <div class='form-group'>
            <label> User Name: </label>
            <input class='form-control' type='text' name='user_name' value=''
                     id='user_name' placeholder='Enter new user name' required>
        </div>
        <div class='form-group'>
            <label> User Password: </label>
            <input class='form-control' type='password' name='user_password' value=''
                     id='password' placeholder='Enter new user password' required>
        </div>
        <div class='form-group'>
            <label> User Type: </label>
            <?php select_data($type_array, "user_type", "Teacher"); ?>
        </div>
        <div class='form-group'>
            <label> E-mail: </label>
            <input class='form-control' type='text' name='e_mail' value=''
                     id='email' placeholder='Type user email here.' required>
        </div>
      </div>
      <div class='col-sm-5'>
        <div class='form-group'>
            <label> First Name: </label>
            <input class='form-control' type='text' name='first_name' value=''
                     id='fname' placeholder='Enter user first name' required>
        </div>
        <div class='form-group'>
            <label> Middle Name: </label>
            <input class='form-control' type='text' name='middle_name' value=''
                     id='mname' placeholder='Enter user middle name if any'>
        </div>
        <div class='form-group'>
            <label> Last Name: </label>
            <input class='form-control' type='text' name='last_name' value=''
                     id='lname' placeholder='Enter user last name' required>
        </div>
        <div class='form-group'>
            <label> User Initials: </label>
            <input class='form-control' type='text' name='user_initials' value=''
                     id='uinit' placeholder='Enter user full name initials' required>
        </div>
      </div>
      <div class="col-sm-2">
        <div class='form-group'>
          <label> Control </label>
          <input class='btn btn-primary btn-block' type='submit' name='login'
                value='Add User'>
        </div>
      </div>
    </div>
  </form>
</div>

<?php
    create_footer();
?>
