<?php
    function base_header($title='')
    {
      echo "<!DOCTYPE html>
            <html lang='en-US'>
            <head>
              <meta charset='utf-8'>
              <meta http-equiv='X-UA-Compatible' content='IE=edge'>
              <meta name='viewport' content='width=device-width, initial-scale=1'>
              <meta name='description' content=''>
              <meta name='author' content=''>

              <title> $title </title>

              <!-- Bootstrap Core CSS -->
              <link href='static/css/bootstrap.min.css' rel='stylesheet'>

              <!-- bootstrap-datepicker CSS -->
              <link href='static/css/bootstrap-datepicker3.min.css' rel='stylesheet'>

              <!-- bootstrap-dataTables CSS -->
              <link href='static/css/dataTables.bootstrap.css' rel='stylesheet'>
              <link href='static/css/jquery.dataTables.min.css' rel='stylesheet'>

              <!-- Custom CSS -->
              <link href='static/css/modern-business.css' rel='stylesheet'>
              <link href='static/css/w3.css' rel='stylesheet'>
              <link href='static/css/dia.css' rel='stylesheet'>

              <!-- Custom Fonts -->
              <link href='static/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
              <link href='static/css/fonts.css' type='text/css' rel='stylesheet'>
              <style>
                html, body {
                  font-family: Roboto, Bitter, 'Open Sans', Arvo, 'Sans Serif', Arial;
                }

                a:hover, a:visited, a:link, a:active
                {
                  text-decoration: none;
                }

                .bitterlabel {
                  font-family: Bitter, Roboto;
                }
              </style>
            </head>
            <body>";
    }

    function search_bar($search_page)
    {
      echo "<form class='form-inline' action='$search_page' method='POST' id='bsearch' style='margin-top: 15px'>
                <div class='input-group margin-bottom-sm'>
                  <input class='form-control' type='text' id='search' name='search'
                      placeholder='Search Student...'>
                  <div class='input-group-btn input-group-lg'>
                      <button class='btn btn-primary' form='bsearch' type='submit' name='submit'>
                          <i class='fa fa-search w3-large'></i> </button>
                  </div>
                </div>
            </form>";
    }

    function create_header()
    {
      $up_2 = encrypt_data('2');
      $full_name = $_SESSION['full_name'];
      // $user_type = $_SESSION['user_type'];
      $user = encrypt_data($_SESSION['user_name']);

      echo "<!-- Navigation -->
        <nav class='navbar navbar-inverse navbar-fixed-top' role='navigation'>
          <div class='container'>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class='navbar-header'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand bitterlabel' href='index.php'> <i class='fa fa-home fa-fw'></i> DIA </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
                <ul class='nav navbar-nav navbar-right'>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-graduation-cap fa-fw'></i> Student <b class='glyphicon glyphicon-menu-down'></b></a>
                        <ul class='dropdown-menu'>";
                            if ($_SESSION['is_admin'] == 1 or $_SESSION['is_head'] == 1 or $_SESSION['is_form_teacher'] == 1) {
                                echo "<li><a href='add_student.php'><span class='glyphicon glyphicon-user'></span> Add New Student </a></li>";
                                echo "<li><a href='display_students.php'><i class='fa fa-fw fa-edit'></i> View and Edit Students </a></li>";
                                // echo "<li class='divider'></li>";
                            }
                  echo "</ul>
                    </li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-tasks'></i> Scores <b class='glyphicon glyphicon-menu-down'></b></a>
                        <ul class='dropdown-menu'>
                            <li>
                                <a href='add_scores.php'><i class='fa fa-fw fa-plus'></i> Add Student Score </a>
                            </li>
                            <li>
                                <a href='teachers_view.php'><i class='fa fa-fw fa-desktop'></i> Teacher's Score View </a>
                            </li>";
                            if ( $_SESSION['is_admin'] == 1 or $_SESSION['is_head'] == 1 or $_SESSION['is_form_teacher'] == 1 ) {
                              echo "<li><a href='view_class_scores.php'><i class='fa fa-fw fa-navicon'></i> Student Score View </a></li>";
                            }
                            if ( $_SESSION['is_admin'] == 1 or $_SESSION['is_head'] == 1 or $_SESSION['is_form_teacher'] == 1 ) {
                              echo "<li><a href='reports.php'><i class='fa fa-fw fa-navicon'></i> Generate Report </a></li>";
                            }
                  echo "</ul>
                    </li>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='fa fa-fw fa-gear'></i> Settings <b class='glyphicon glyphicon-menu-down'></b></a>
                        <ul class='dropdown-menu'>
                            <li>";
                            if ($_SESSION['is_admin'] == 1 or $_SESSION['is_head'] == 1) {
                                echo "<a href='create_users.php'><i class='fa fa-fw fa-user'></i> Add New User </a>";
                            }
                      echo "</li>
                            <li>";
                            if ($_SESSION['is_admin'] == 1) {
                                echo "<a href='display_users.php'><i class='fa fa-fw fa-edit'></i> View and Edit Users </a>";
                            }
                      echo "</li>
                            <li>
                                <a href='change_password.php'><i class='fa fa-fw fa-key'></i> Change Password </a>
                            </li>
                            <li class='divider'></li>
                            <li>";
                            if ($_SESSION['is_admin'] == 1) {
                                echo "<a href='down_page.php'><i class='fa fa-fw fa-database'></i> Back Up Database </a>";
                            }
                      echo  "</li>
                            <li>
                                <a href='settings.php'><i class='fa fa-fw fa-plus-square'></i> Add Subject</a>
                            </li>
                            <li>
                                <a href='reset_security.php'><i class='fa fa-fw fa-lock'></i> Add Login Security </a>
                            </li>
                            <li class='divider'></li>
                            <li>
                                <a href='add_file.php'><i class='fa fa-fw fa-file'></i> Upload Document </a>
                            </li>
                            <li>
                                <a href='display_uploaded_files.php'><i class='fa fa-fw fa-file'></i> Dsiplay Upload History </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href='users_update.php?str_1={$user}&up={$up_2}'><i class='fa fa-fw fa-user'></i> $full_name </a>
                    </li>
                    <li>
                        <a href='log_out.php'> Log Out <i class='fa fa-sign-out fa-fw'></i> </a>
                    </li>
                </ul>
              </div>
                <!-- /.navbar-collapse -->
          </div>
        </nav>";
    }

    function get_class_name($class_name)
    {
      $get_class = 'No key found here';
      foreach ($_SESSION as $key => $value) {
        if (preg_match("/teaches/i", $key)) {
          if ($_SESSION[$key] == 1) {
            $get_class = implode(" ", explode("_", $key));
            if (preg_match("/{$class_name}/i", $get_class)) {
              return $get_class;
            }
          }
        }
      }
      return $get_class;
    }

    function create_subject_array($class_name)
    {
      $subject_array = array();
      // Loop through the sessions for the class name
      foreach ($_SESSION as $key => $value) {
        // Get the subjects that corresponds the class name
        if (preg_match('/subject/i', $key)) {
          if ($_SESSION[$key] != 'None') {
            $subject_class = implode(" ", explode("_", $key));
            if (preg_match("/{$class_name}/i", $subject_class)) {
              $subject_array = explode(",", $value);
              foreach ($subject_array as $key => $value) {
                $subject_array[$key] = trim($value);
              }
              return $subject_array;
            }
          }
        }
      }
      return $subject_array;
    }

    function add_footer()
    {
      echo "<footer class='footer navbar-fixed-bottom text-center'>
              <div class='container'>
                <span class='text-muted'><strong>Copyright &copy; ", date("Y"), "- Created by DatalabGH. &nbsp; </strong> All rights
                reserved.</span>
              </div>
            </footer>";
    }

    function create_footer()
    {
      // add_footer();
      echo "  <script src='static/js/jquery-3.1.1.min.js'></script>
              <script src='static/js/bootstrap.min.js'></script>
              <script src='static/js/bootstrap-datepicker.min.js'></script>
              <script src='static/js/jquery.dataTables.min.js'></script>
              <script src='static/js/dataTables.bootstrap.min.js'></script>
              <script src='static/js/dia.js'></script>
              <script type='text/javascript'>
                $(function () {
                  $('#display_table').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': true
                  });
                  $('#file_table').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': true
                  });
                  $('#user_table').DataTable({
                    'paging': true,
                    'lengthChange': false,
                    'searching': false,
                    'ordering': true,
                    'info': true,
                    'autoWidth': false
                  });
                  $('#form_datetime').datepicker({
                    format: 'MM-dd-yyyy',
                    autoclose: true
                  });
                });
              </script>
          </body>
          </html>";
    }

    function select_data($data_array, $select_name, $select_value, $width=100, $id = '', $sorted = FALSE, $disabled = '')
    {
      echo "<select class='form-control' name='{$select_name}' id='{$id}' style='width:{$width}%' $disabled>\n";

      // Sort the array
      if ($sorted) {
        sort($data_array);
      }

      foreach ($data_array as $key => $value) {
        echo "<option value='{$value}'";
        if ($select_value == $value) {
          echo " selected";
        }
        echo "> $value </option>";
      }
      echo "</select>\n";
    }

    function create_toggle_switch($control_name, $control_value, $checked=FALSE)
    {
      $checked_value = ($checked) ? "checked" : "" ;
      echo "<div class='checkbox'>
              <label class='switch'>
                <input type='checkbox' name='$control_name' value='1' $checked_value>
                <div class='slider round'><span> $control_value </span></div>
              </label>
            </div>";
    }
    /* Make the value of a data secured before sending */
    function secure_data_value($data_value)
    {
      $data_value = trim($data_value);
      $data_value = stripslashes($data_value);
      $data_value = strip_tags($data_value);
      $secured_value = htmlspecialchars($data_value);

      return $secured_value;
    }

    /* Make a data array secured */
    function secure_data_array($data_array)
    {
      $secured_array = array();
      if (!is_array($data_array)) { // Data is not an array, hence make it one.
        $secured_array[] = secure_data_value($data_array);
      } else { // Data is an array, hence valudate each element of the array.
        foreach ($data_array as $key => $value) {
          $secured_array[$key] = secure_data_value($value);
        }
      }
      return $secured_array;
    }

    function get_date_form($date_year, $date_month, $date_day, $date_to_use, $ywidth=20, $mwidth=30, $dwidth=15)
    {
        $monthName = array(1=> "January", "February", "March",
                            "April", "May", "June", "July",
                            "August", "September", "October",
                            "November", "December");

        $today = strtotime($date_to_use); #stores today's date

        /* Build selection list for month */
        $todayMO = date("m",$today); #get the month from $today
        echo "<select class='w3-select w3-border w3-round' name='{$date_month}' style='width:{$mwidth}%'>\n";

    		for ($n=1;$n<=12;$n++)
    		{
    			echo "<option value=$n";
    			if ($todayMO == $n) #adds selected attribute if today
    			{
    			  echo " selected";
    			}
    			echo "> $monthName[$n] </option>\n";
    		}
    		echo "</select>\n";

    		/* build selection list for the day */
    		$todayDay= date("d",$today);
    		#get the day from $today
    		echo "<select class='w3-select w3-border w3-round' name='{$date_day}' style='width:{$dwidth}%'>\n";

    		for ($n=1;$n<=31;$n++)
    		{
    			echo " <option value=$n";
    			if ($todayDay == $n )
    			{
    			  echo " selected";
    			}
    			echo "> $n </option>\n";
    		}
    		echo "</select>\n";

    		/* build selection list for the year */
    		$startYr = date("Y", $today);
    		#get the year from $today
    		echo "<select class='w3-select w3-border w3-round' name='{$date_year}' style='width:{$ywidth}%'>\n";

    		for ($n=1950;$n<=($startYr+50);$n++)
    		{
    			echo " <option value=$n";
    			if ($startYr == $n )
    			{
    			  echo " selected";
    			}
    			echo "> $n </option>\n";
    		}
    		echo "</select>\n";
    }

    function encrypt_data($string)
    {
      $crypt_data = str_split($string);
      $encrypted = "";

      foreach ($crypt_data as $key => $value) {
        // Get the length of the ascii +10 code and convert to string
        $new_value = strval(strlen(intval(ord($value) + 10)));
        // Get the ascii code of the +10 string
        $new_value .= strval(intval(ord($value) + 10));
        // Concatenate to already existing string
        $encrypted .= $new_value;
      }
      // Return the reversed string. That will be your encrypt data
      return strrev($encrypted);
    }

    function encryption($string, $key)
    {
      $encrypted = "";
      $encrypt = "";
      $i = 0; $j = 0;

      $key_len = strlen($key);
      while ($i <= strlen($string)) {
        $new_value = strval(strlen(intval(ord(substr($string, $i, 1)) + $key_len)));
        $new_value .= strval(intval(ord(substr($string, $i, 1)) + $key_len));
        $encrypted .= $new_value;
        $i += 1;
      }

      return $encrypted;
    }

    function decrypt_data($string)
    {
      // Start decryting reversing the given string
      $crypt_data = strrev($string);
      $decrypted = "";
      $i = 0;
      // Use a loop to get the number of places it should go until you get the full string decrypted
      while ($i < strlen($crypt_data)) {
        $chr_count = intval(substr($crypt_data, $i, 1));
        $new_chr = intval(substr($crypt_data, $i+1, $chr_count)) - 10;
        $decrypted .= chr($new_chr);
        $i += $chr_count + 1;
      }

      return $decrypted;
    }

    function is_key($array, $key_value)
    {
      foreach ($array as $key => $value) {
        if ($key == $key_value) {
          return TRUE;
        }
      }
      return FALSE;
    }

    function is_element($array, $element_to_find)
    {
      foreach ($array as $key => $value) {
        if ($value == $element_to_find) {
          return TRUE;
        }
      }
      return FALSE;
    }

    function has_string($string, $string_to_find, $delim = "_")
    {
      $field_name = explode($delim, $string);

      foreach ($field_name as $key => $value) {
        if ($value == $string_to_find) {
          return TRUE;
        }
      }
      return FALSE;
    }

    function create_id($date, $prelim, $delim='_')
    {
      $_date = new DateTime($date);
      $l_date = date_format($_date, "y-m-d");
      $l_num = substr(strrev(time()), 0, 4);

      $id_date = explode("-", $l_date);

      $_id = $prelim.$delim.$id_date[2].$id_date[1].$id_date[0].$delim.$l_num;

      return $_id;
    }

    function create_student_id($birth_date, $db_num)
    {
      # This function is basically used to create new students numbers for the students
      // The student number has the form DIA-Day-Month-Year-Number
      $m_date = new DateTime($birth_date);

      $new_date = date_format($m_date, "y-m-d");

      $date_given = explode("-", $new_date);

      $str_length = str_repeat("0", (4 - strlen(strval($db_num + 1)))).strval($db_num + 1);

      $std_id = "DIA".$date_given[2].$date_given[1].$date_given[0].$str_length;

      return $std_id;
    }

    function get_column_name($value)
    {
      $field_name = explode("_", $value);
      $column_name = implode(" ", $field_name);

      $column_name = ucwords($column_name);
      return $column_name;
    }

    function get_date_name($value)
    {
      $date_array = explode("_", $value);
      $array_count = sizeof($date_array);

      return $date_array[$array_count - 1];
    }

    function filter_array($subject_array, $standard_array)
    {
      foreach ($subject_array as $key => $value) {
        if (!is_element($standard_array, $key)) {
          unset($subject_array[$key]);
        }
      }

      return $subject_array;
    }

    function str_date($strdate)
    {
      $new_date = new DateTime($strdate);
      return $new_date;
    }

    function login_check()
    {
      // When the user is not logged in
      if (@$_SESSION['auth'] != "yes") {
        header("Location: login.php");
        exit();
      }

      // When the session expires
      if ( (time() - $_SESSION['login_time']) > 1440 ) {
        include_once 'log_out.php';
        exit();
      }
    }

    function academic_year($how_far = 10)
    {
      $year_array = [];
      $year = 2011;
      $a = 1;
      for ($n = 0 ; $n <= $how_far; $n++)
      {
        $year_array[$n] = $year + $n;
      }
      while ($a < sizeof($year_array)) {
        $academic_years[$a-1] = $year_array[$a-1]."_".$year_array[$a];
        $a += 1;
      }
      return $academic_years;
    }

    function pick_academic_year($year_array)
    {
      foreach ($year as $key => $value) {
        if (date('n') <= 7 and date('n') >= 9 ) {
          # code...
        }
      }
    }

    function comp_score_grade($score)
    {
      $score = floatval($score);

      switch ($score) {
        case 90 <= $score and $score <= 100:
          $grade = 'A';
          break;

        case 80 <= $score and $score < 90:
          $grade = 'B';
          break;

        case 70 <= $score and $score < 80:
          $grade = 'C';
          break;

        case 60 <= $score and $score < 70:
          $grade = 'D';
          break;

        case 50 <= $score and $score < 60:
          $grade = 'E';
          break;

        default:
          $grade = 'F';
          break;
      }

      return $grade;
    }

    function comp_grade_remark($grade)
    {
      switch ($grade) {
        case 'A':
          $remark = 'Excellent';
          break;

        case 'B':
          $remark = 'Very Good';
          break;

        case 'C':
          $remark = 'Good';
          break;

        case 'D':
          $remark = 'Satisfactory';
          break;

        case 'E':
          $remark = 'Fair';
          break;

        default:
          $remark = 'Fail';
          break;
      }

      return $remark;
    }

    function get_month_from_value($int_month)
    {
      $month_array = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                          'October', 'November', 'December');
      $int_month = intval($int_month);
      $month_name = '';
      if ($int_month > 12 and $int_month <= 0) {
        return $month_name;
      } else {
        foreach ($month_array as $key => $value) {
          if ($key == $int_month) {
            $month_name = $value;
          }
        }
        return $month_name;
      }
    }

    function class_department($class_name)
    {
      $lower_secondary = array('Year 7', 'Year 8', 'Year 9');
      $upper_secondary = array('IGCSE 1', 'IGCSE 2', 'AS Level', 'A Level');

      $value = (is_element($lower_secondary, $class_name)) ? 'Lower Secondary' : 'Upper Secondary';

      return $value;
    }

    function change_string_to_date($date_string, $delim='-')
    {
      $new_date = explode($delim, $date_string);
      $new_date[0] = date("m", strtotime($new_date[0]));

      return $new_date;
    }

?>
