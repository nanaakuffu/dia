<?php
  if (isset($_GET['str'])) {
    session_start();
  }

  include_once 'public_functions.php';
  include_once 'db_functions.php';
  include_once 'form.php';

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
    $_POST['Update'] = 'Update';
  } elseif (isset($_SESSION['message'])) {
    $student_number = $_SESSION['id'];
  }

  $class_name = (isset($_POST['Update'])) ? $_POST['class_name'] : $class_array[0] ;
  $house_name = (isset($_POST['Update'])) ? $_POST['house_name'] : $house_array[0] ;
  $first_name = (isset($_POST['Update'])) ? $_POST['first_name'] : '' ;
  $middle_name = (isset($_POST['Update'])) ? $_POST['middle_name'] : '' ;
  $last_name = (isset($_POST['Update'])) ? $_POST['last_name'] : '' ;
  $birth_date = (isset($_POST['Update'])) ? $_POST['date_of_birth'] : date("l, j F, Y") ;
  $gender = (isset($_POST['Update'])) ? $_POST['gender'] : 'Male' ;

  echo "<br/><div class='w3-container'>
            <div class='row'>
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
        echo "  <div class='w3-container w3-red'>
                    <h3> Update Student Details </h3>
                </div>
                <form class='w3-form w3-border w3-round' action='view_student.php' method='POST'>
                  <input type='hidden' name='student_number' value='{$student_number}'>
                  <div class='form-group'>
                      <label class='w3-validate' for='first_name'> First Name: </label>
                      <input class='form-control' type='text' name='first_name' value='{$first_name}'
                           id='first_name' required>
                  </div>
                  <div class='form-group'>
                    <label class='w3-validate' for='middle_name'>Middle Name: </label>
                    <input class='form-control' type='text' name='middle_name' value='{$middle_name}'
                           id='middle_name' >
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='last_name'> Last Name: </label>
                    <input class='form-control' type='text' name='last_name' value='{$last_name}'
                           id='last_name' required>
                  </div>

                  <input type='hidden' name='full_name'>

                  <div class='form-group'>
                    <label for='date_of_birth'>Date of Birth: </label>
                    <br />", get_date_form('birth_year','birth_month', 'birth_day', $birth_date ),
                    "<input type='hidden' name='date_of_birth'>
                  </div>

                  <div class='form-group'>";
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
         echo "   </div>
                  <div class='form-group'>
                    <label for='class_name'>Class Name : </label>
                    <br />", select_data($class_array, 'class_name', $class_name, 30), "
                  </div>

                  <div class='form-group'>
                    <label for='house_name'> House Name : </label></td>
                    <br />", select_data($house_array, 'house_name', $house_name, 30), "
                  </div>
                  <div class='btn-group'>
                      <input class='btn btn-primary' type='submit' name='Update' value='Update'>
                      <input class='btn btn-primary' type='submit' name='Delete' value='Delete'>
                      <a class='btn btn-primary' href='display_students.php'>Back</a>
                  </div>
              </div>
              <div class='col-sm-3'>
                <br />
              </div>
            </div>
          </form>
        </div>";

//        $db->close_connection($con);
        create_footer();
?>
