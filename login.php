<?php
    session_start();

    require_once("db_functions.php");
    require_once("public_vars.php");
    require_once("public_functions.php");

    if (isset($_POST['login'])) {
        // Connect to database and get user access levels as he/she logs in
        $db = new Database();
        $con = $db->connect_to_db();
        $user_array = $db->get_user_priveleges($con, $_POST['user_name']);

        // echo "<pre>",var_dump($user_array),"</pre>";

        $user_sql = "SELECT * FROM users WHERE user_name = "."'".$_POST['user_name']."'";

        $result = mysqli_query($con, $user_sql) or die("Couldn't execute query.");
        $num = mysqli_num_rows($result);

        if ($num > 0) {   // user name was found
          $password = encrypt_data($_POST['user_pass_word']);
          $pass_sql = "SELECT user_name, user_type, last_name, first_name, middle_name, user_initials, status FROM users WHERE user_name="."'".$_POST['user_name']."'". " AND user_password=". "'".$password."'";
          $pass_result = mysqli_query($con, $pass_sql) or die("Couldn't execute query 2.");
          $num_available = mysqli_num_rows($pass_result);

          while($record = mysqli_fetch_assoc($pass_result)){
            $rows[] = $record;
          }

          if ($num_available > 0) { // Password did match
            if ($rows[0]['status'] == 0) {
              if (sizeof($user_array) > 0 ) { // The user's access leve is set
                $full_name = $rows[0]['last_name'].", ".$rows[0]['first_name']." ".$rows[0]['middle_name'];

                $_SESSION = $user_array;
                $_SESSION['auth'] = 'yes';
                $user_name = $_POST['user_name'];
                $_SESSION['full_name'] = $full_name;
                $_SESSION['initials'] = $rows[0]['user_initials'];

                $today_date = date('y-m-d');
                $today_time = date('h:i:s');
                $log_id = create_id($today_date, "log");
                $_SESSION['log_id'] = $log_id;
                $_SESSION['login_time'] = time();

                $log_sql = "INSERT INTO login_details (log_id, user_name, full_name, login_date, login_time, ";
                $log_sql .= "logout_time) VALUES ('$log_id','$user_name', '$full_name', '$today_date', '$today_time', ' ')";
                $result = mysqli_query($con, $log_sql) or die("Can't execute insert query.");

                // Put the user online or login
                $status_sql = "UPDATE users SET status='1' WHERE user_name="."'".$user_name."'";
                $status_result = mysqli_query($con, $status_sql);

                $db->close_connection($con);
                header("Location: index.php");
              } else { // Access level not set. Redirect complaint to the administrator
                $message = "<i class='fa fa-fw fa-close'></i> Your user account is inactive.
                            Please contact the system administrator about this message.";
                $_SESSION['message'] = $message;
                include_once 'login_page.php';
                exit();
              }
            } else { // User is already logged in. So you either ask the administrator to log you out or ask him how
              $db->close_connection($con);
              $message = "<i class='fa fa-fw fa-close'></i> User is already logged in. If you are sure this
                          is you, please click <a href='reset.php'> HERE </a>
                          to reset your login status.";
              $_SESSION['message'] = $message;
              include_once 'login_page.php';
              exit();
            }
          } else { // Password typed does not match what is on the database
            $db->close_connection($con);
            $message = "<i class='fa fa-fw fa-close'></i> Password does not match your user name!";
            $_SESSION['message'] = $message;
            include_once 'login_page.php';
            exit();
          }
        } else { // User name was not found.
          $db->close_connection($con);
          $message = "<i class='fa fa-fw fa-close'></i> Password does not match your user name!";
          $_SESSION['message'] = $message;
          include_once 'login_page.php';
          exit();
        }
    } else { // No data hase been submitted yet.
      include 'login_page.php';
      exit();
    }

?>
