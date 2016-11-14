<?php

  require_once 'public_functions.php';
  require_once 'db_functions.php';
  require_once 'form.php';

  login_check();

  base_header("Students");
  create_header();

  $class_array = array("Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
  $house_array = array('Honesty', 'Integrity', 'Loyalty', 'Unity');

  $class_name = (isset($_POST['submit'])) ? $_POST['class_name'] : $class_array[0] ;
  $house_name = (isset($_POST['submit'])) ? $_POST['house_name'] : $house_array[0] ;

  echo "<br/><div class='w3-container'>
            <div class='row'>
              <div class='col-sm-3'>
                <br />
              </div>
              <div class='col-sm-6'>";
              if (isset($_SESSION['message'])) {
                echo "<div class='panel panel-default'>
                        <div class='panel-heading'>Input Error(s)</div>
                        <div class='panel-body'><ol type='1' start='1'>", $_SESSION['message'], "</ol></div>
                      </div>";
                unset($_SESSION['message']);
              }
        echo "  <div class='w3-container w3-red'>
                    <h3> Student Details </h3>
                </div>
                <form class='w3-form w3-border w3-round' action='add_student.php' method='POST'>
                  <input type='hidden' name='student_number'>
                  <div class='form-group'>
                      <label class='w3-validate' for='first_name'> First Name: </label>
                      <input class='form-control' type='text' name='first_name' value=''
                               id='first_name' placeholder='Enter First Name' required>
                  </div>
                  <div class='form-group'>
                    <label class='w3-validate' for='middle_name'>Middle Name: </label>
                    <input class='form-control' type='text' name='middle_name' value=''
                           id='middle_name' placeholder='Enter Middle Name'>
                  </div>

                  <div class='form-group'>
                    <label class='w3-validate' for='last_name'> Last Name: </label>
                    <input class='form-control' type='text' name='last_name' value=''
                           id='last_name' placeholder='Enter Last Name' required>
                  </div>

                  <input type='hidden' name='full_name'>

                  <div class='form-group'>
                    <label for='date_of_birth'>Date of Birth: </label>
                    <br />", get_date_form('birth_year','birth_month', 'birth_day', date("l, j F, Y") ),
                    "<input type='hidden' name='date_of_birth'>
                  </div>

                  <div class='form-group'>
                      <input class='w3-radio' type='radio' name='gender'
                        value='Male' checked> Male
                      <input class='w3-radio' type='radio' name='gender'
                        value='Female'> Female
                  </div>

                  <div class='form-group'>
                    <label for='class_name'>Class Name : </label>
                    <br />", select_data($class_array, 'class_name', $class_name, 30), "
                  </div>

                  <div class='form-group'>
                    <label for='house_name'> House Name : </label></td>
                    <br />", select_data($house_array, 'house_name', $house_name, 30), "
                  </div>
                  <input class='btn btn-primary w3-round w3-padding-medium' type='submit' name='submit'
                        value='Add Student' >
              </div>
              <div class='col-sm-3'>
                <br />
              </div>
            </div>
          </form>
        </div>";

      create_footer();
?>
