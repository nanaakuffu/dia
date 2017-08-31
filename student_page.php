<?php
  if (isset($_GET['str'])) {
    session_start();
  }

  unset($_SESSION['update_student']);

  require_once 'public_functions.php';
  require_once 'db_functions.php';
  // require_once 'form.php';

  login_check();

  base_header("Students");
  create_header();

  $db = new Database();
  $con = $db->connect_to_db();

  $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
  $house_array = array('Honesty', 'Integrity', 'Loyalty', 'Unity');

  if (isset($_GET['str'])) {
    $student_number = decrypt_data($_GET['str']);
    $_POST = $db->view_data($con, "students", "student_number", $student_number );
    $_POST['add_student'] = 'Update Student';
    $_SESSION['update_student'] = TRUE;
  }

  if (isset($_SESSION['id'])) {
    $student_number = $_SESSION['id'];
    $_SESSION['update_student'] = TRUE;
  }

  $class_name = (isset($_POST['add_student'])) ? $_POST['class_name'] : $class_array[0] ;
  $house_name = (isset($_POST['add_student'])) ? $_POST['house_name'] : $house_array[0] ;
  $first_name = (isset($_POST['add_student'])) ? $_POST['first_name'] : '' ;
  $middle_name = (isset($_POST['add_student'])) ? $_POST['middle_name'] : '' ;
  $last_name = (isset($_POST['add_student'])) ? $_POST['last_name'] : '' ;
  $date_of_birth = (isset($_POST['add_student'])) ? date("F-j-Y", strtotime($_POST['date_of_birth'])) : date("F-j-Y");
  $gender = (isset($_POST['add_student'])) ? $_POST['gender'] : 'Male' ;
  // $date_of_birth = (isset($_POST['add_student'])) ? $_POST['date_of_birth'] : '' ;

  // $class_name = (isset($_POST['submit'])) ? $_POST['class_name'] : $class_array[0] ;
  // $house_name = (isset($_POST['submit'])) ? $_POST['house_name'] : $house_array[0] ;

  echo "<br/>
        <div class='container'>";
          if (isset($_SESSION['message'])) {
            echo "<div class='panel panel-default'>
                    <div class='panel-heading'>Input Error(s)</div>
                    <div class='panel-body'><ul class='fa-ul'>", $_SESSION['message'], "</ul></div>
                  </div>";
            unset($_SESSION['message']);
          }
  echo "  <div class='w3-container w3-red'>";
              if (isset($_SESSION['update_student'])) {
                echo "<h3> Update Student Details </h3>";
              } else {
                echo "<h3> Add Student Details </h3>";
              }
  echo "  </div>
          <form class='w3-form w3-border w3-round' action='add_student.php' method='POST'>
            <div class='row'>
              <div class='col-sm-6'>";
                if (isset($_SESSION['update_student'])) {
                   echo "<input type='hidden' name='student_number' value='{$student_number}'>";
                }

        echo "  <div class='form-group'>
                    <label class='bitterlabel' for='first_name'> First Name: </label>
                    <input class='form-control' type='text' name='first_name' value='{$first_name}'
                         id='first_name' placeholder='Enter First Name' required>
                </div>

                <div class='form-group'>
                  <label class='bitterlabel' for='middle_name'>Middle Name: </label>
                  <input class='form-control' type='text' name='middle_name' value='{$middle_name}'
                         id='middle_name' placeholder='Enter Middle Name'>
                </div>

                <div class='form-group'>
                  <label class='bitterlabel' for='last_name'> Last Name: </label>
                  <input class='form-control' type='text' name='last_name' value='{$last_name}'
                         id='last_name' placeholder='Enter Last Name' required>
                </div>

                <input type='hidden' name='full_name'>

                <div class='form-group'>
                  <label class='bitterlabel' for='date_of_birth'>Date of Birth: </label> <br />
                  <div class='input-group date' id='form_datetime'>
                    <input class='form-control' type='text' name='date_of_birth' value='{$date_of_birth}' readonly>
                    <span class='input-group-addon'>
                        <span class='glyphicon glyphicon-calendar'></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class='col-sm-6'>
                <div class='form-group'>
                  <label class='bitterlabel' for='gender'>Gender: </label> <br />";
                  if ($gender == "Male") {
                    echo "<input class='w3-radio' type='radio' name='gender'
                            value='Male' checked> Male
                          <input class='w3-radio' type='radio' name='gender'
                            value='Female'> Female ";
                  } else {
                    echo "<input class='w3-radio' type='radio' name='gender'
                            value='Male'> Male
                          <input class='w3-radio' type='radio' name='gender'
                            value='Female' checked> Female ";
                  }
     echo "     </div>
                <div class='form-group'>
                  <label class='bitterlabel' for='class_name'>Class Name : </label>
                  <br />", select_data($class_array, 'class_name', $class_name), "
                </div>

                <div class='form-group'>
                  <label class='bitterlabel' for='house_name'> House Name : </label></td>
                  <br />", select_data($house_array, 'house_name', $house_name), "
                </div><br />";
                if (!isset($_SESSION['update_student'])) {
                  echo "<input class='btn btn-primary w3-round w3-padding-medium' type='submit' name='add_student'
                        value='Add Student' >";
                } else {
                  if ($_SESSION['is_admin']) {
                    echo "<div class='btn-group'>
                            <input class='btn btn-primary' type='submit' name='add_student' value='Update Student'>
                            <input class='btn btn-primary' type='submit' name='add_student' value='Delete Student'>
                            <a class='btn btn-primary' href='display_students.php'>Back</a>
                          </div>";
                  } else {
                    echo "<div class='btn-group'>
                            <input class='btn btn-primary' type='submit' name='add_student' value='Update Student'>
                            <a class='btn btn-primary' href='display_students.php'>Back</a>
                          </div>";
                  }
                }
    echo "    </div>
            </div>
          </form>
        </div>";

      unset($_SESSION['id']); // Unset the id
      create_footer();
?>
