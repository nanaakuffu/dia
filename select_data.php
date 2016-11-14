<?php

    include_once "db_functions.php";

    $db = new Database();
    $con = $db->connect_to_db();

    $choice = mysqli_real_escape_string($con, $_GET['choice']);
    $query = "SELECT DISTINCT full_name FROM students WHERE class_name='$choice' ORDER BY full_name ASC";

    $result = mysqli_query($con, $query);
    $record_count = mysqli_num_rows($result);

    if ($record_count > 0 ){
        while ($row = mysqli_fetch_array($result)) {
            echo "<option value='{$row['full_name']}'>" . $row['full_name'] . "</option>";
        }
    } else {
      echo "<option value=''> </option>";
    }

    mysqli_close($con);

?>
