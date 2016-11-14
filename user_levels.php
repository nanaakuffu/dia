<?php
  if (isset($_GET['upd'])) {
    session_start();
  }

  unset($_SESSION['update_access']);

  require_once 'public_functions.php';
  require_once 'db_functions.php';

  login_check();

  base_header('Access Levels');
  create_header();

  $db = new Database();
  $con = $db->connect_to_db();

  if (isset($_GET['level'])) {
    $user_name = decrypt_data($_GET['level']);
    $sql = "SELECT * FROM priveleges WHERE user_name="."'".$user_name."'";
    $result = mysqli_query($con, $sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
      $_POST = $db->view_data($con, "priveleges", "user_name", $user_name);
      $_POST['add_access'] = 'Update';
      $_SESSION['update_access'] = TRUE;
    }
  }

  if (isset($_SESSION['message'])) {
    $user_name = $_SESSION['user_id'];
  }

  $department = array('Lower Secondary', 'Upper Secondary', 'Both');
  $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS/A Level");

  $user = $user_name;
  $is_admin = (isset($_POST['add_access'])) ? $_POST['is_admin'] : 0 ;
  $is_head = (isset($_POST['add_access'])) ? $_POST['is_head'] : 0 ;
  $is_form_teacher = (isset($_POST['add_access'])) ? $_POST['is_form_teacher'] : 0 ;
  $year_7 = (isset($_POST['add_access'])) ? $_POST['teaches_year_7'] : 0 ;
  $year_8 = (isset($_POST['add_access'])) ? $_POST['teaches_year_8'] : 0 ;
  $year_9 = (isset($_POST['add_access'])) ? $_POST['teaches_year_9'] : 0 ;
  $ig_1 = (isset($_POST['add_access'])) ? $_POST['teaches_ig_1'] : 0 ;
  $ig_2 = (isset($_POST['add_access'])) ? $_POST['teaches_ig_2'] : 0 ;
  $as_level = (isset($_POST['add_access'])) ? $_POST['teaches_as_level'] : 0 ;
  $a_level = (isset($_POST['add_access'])) ? $_POST['teaches_a_level'] : 0 ;
  $depment = (isset($_POST['add_access'])) ? $_POST['department'] : $department[0] ;
  $form = (isset($_POST['add_access'])) ? $_POST['form_name'] : $class_array[0] ;
  $year_7_sub = (isset($_POST['add_access'])) ? $_POST['year_7_subject'] : "None" ;
  $year_8_sub = (isset($_POST['add_access'])) ? $_POST['year_8_subject'] : "None" ;
  $year_9_sub = (isset($_POST['add_access'])) ? $_POST['year_9_subject'] : "None" ;
  $ig_1_sub = (isset($_POST['add_access'])) ? $_POST['ig_1_subject'] : "None" ;
  $ig_2_sub = (isset($_POST['add_access'])) ? $_POST['ig_2_subject'] : "None" ;
  $as_level_sub = (isset($_POST['add_access'])) ? $_POST['as_level_subject'] : "None" ;
  $a_level_sub = (isset($_POST['add_access'])) ? $_POST['a_level_subject'] : "None" ;

  echo "<br />
        <div class='container'>";
        if (isset($_SESSION['message'])) {
              echo "<div class='panel panel-default'>
                      <div class='panel-heading'>Input Error(s)</div>
                      <div class='panel-body'><ol type='1' start='1'>", $_SESSION['message'], "</ol></div>
                    </div>";
              unset($_SESSION['message']);
        }
  echo "  <div class='w3-container w3-red'>
            <h3> Set User Priveleges </h3>
          </div>
          <form class='w3-form w3-border w3-round' action='create_levels.php' method='POST'>
          <div class='row'>
            <div class='col-sm-3'>
              <div class='form-group'>
                <label> User Name </label>
                <input class='form-control' type='text' name='user_name' value='{$user}' readonly>
              </div>
              <div class='checkbox'>";
              if ($is_admin == 1) {
                echo "<label> <input type='checkbox' name='is_admin' value='1' checked> Is Administrator </label>";
              } else {
                echo "<label> <input type='checkbox' name='is_admin' value='1'> Is Administrator </label>";
              }
  echo "      </div>
              <div class='checkbox'>";
              if ($is_head == 1) {
                echo "<label> <input type='checkbox' name='is_head' value='1' id='head' onchange='verify_head()' checked> Is Head </label>";
              } else {
                echo "<label> <input type='checkbox' name='is_head' value='1' id='head' onchange='verify_head()'> Is Head </label>";
              }
  echo "      </div>
              <div class='form-group'>";
              $head_disabling = ($is_head != 1) ? 'disabled' : "" ;
              $form_disabling = ($is_form_teacher != 1) ? 'disabled' : "" ;
  echo "        <label for='academic_year'>Department </label><br />
                ", select_data($department, 'department', $depment, 100, 'department', FALSE, $head_disabling), "
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='checkbox'>";
              if ($is_form_teacher == 1) {
                echo "<label> <input type='checkbox' name='is_form_teacher' value='1' id='form' onchange='verify_form()' checked> Is Form Teacher </label>";
              } else {
                echo "<label> <input type='checkbox' name='is_form_teacher' value='1' id='form' onchange='verify_form()'> Is Form Teacher </label>";
              }
  echo "      </div>
              <label for='academic_year'>Form Name </label>
                ", select_data($class_array, 'form_name', $form, 100, 'form_name', FALSE, $form_disabling), "
              <div class='checkbox'>";
              if ($year_7 == 1) {
                echo "<label> <input type='checkbox' name='teaches_year_7' value='1' checked> Teaches Year 7 </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_year_7' value='1'> Teaches Year 7 </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> Year 7 Subject(s) </label>
                <input class='form-control' type='text' name='year_7_subject' value='{$year_7_sub}'>
              </div>
              <div class='checkbox'>";
              if ($year_8 == 1) {
                echo "<label> <input type='checkbox' name='teaches_year_8' value='1' checked> Teaches Year 8 </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_year_8' value='1'> Teaches Year 8 </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> Year 8 Subject(s) </label>
                <input class='form-control' type='text' name='year_8_subject' value='{$year_8_sub}'>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='checkbox'>";
              if ($year_9 == 1) {
                echo "<label> <input type='checkbox' name='teaches_year_9' value='1' checked> Teaches Year 9 </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_year_9' value='1'> Teaches Year 9 </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> Year 9 Subject(s) </label>
                <input class='form-control' type='text' name='year_9_subject' value='{$year_9_sub}'>
              </div>
              <div class='checkbox'>";
              if ($ig_1 == 1) {
                echo "<label> <input type='checkbox' name='teaches_ig_1' value='1' checked> Teaches IG 1 </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_ig_1' value='1'> Teaches IG 1 </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> IG 1 Subject(s) </label>
                <input class='form-control' type='text' name='ig_1_subject' value='{$ig_1_sub}'>
              </div>
              <div class='checkbox'>";
              if ($ig_2 == 1) {
                echo "<label> <input type='checkbox' name='teaches_ig_2' value='1' checked> Teaches IG 2 </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_ig_2' value='1'> Teaches IG 2 </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> IG 2 Subject(s) </label>
                <input class='form-control' type='text' name='ig_2_subject' value='{$ig_2_sub}'>
              </div>
            </div>
            <div class='col-sm-3'>
              <div class='checkbox'>";
              if ($as_level == 1) {
                echo "<label> <input type='checkbox' name='teaches_as_level' value='1' checked> Teaches AS Level </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_as_level' value='1'> Teaches AS Level </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> AS Level Subject(s) </label>
                <input class='form-control' type='text' name='as_level_subject' value='{$as_level_sub}'>
              </div>
              <div class='checkbox'>";
              if ($a_level == 1) {
                echo "<label> <input type='checkbox' name='teaches_a_level' value='1' checked> Teaches A Level </label>";
              } else {
                echo "<label> <input type='checkbox' name='teaches_a_level' value='1'> Teaches A Level </label>";
              }
  echo "      </div>
              <div class='form-group'>
                <label> A Level Subject(s) </label>
                <input class='form-control' type='text' name='a_level_subject' value='{$a_level_sub}'>
              </div>";
              if (!isset($_SESSION['update_access'])) {
                echo "<div class='btn-group'>
                        <input class='btn btn-primary' type='submit' name='add_access'
                        value='Add Access Level'>
                        <a class='btn btn-primary' href='display_users.php'>Back</a>
                      </div>";
              } else {
                echo "<input type='hidden' name='level_up' value='up_lev'>
                      <div class='btn-group'>
                        <input class='btn btn-primary' type='submit' name='add_access'
                        value='Update Access Level'>
                        <a class='btn btn-primary' href='display_users.php'>Back</a>
                      </div>";
              }
  echo "      </div>
          </div>
          </form>";
  echo "</div>";

  create_footer();
?>
