<?php

  /**
   *This class handles all the database communications
   *in the whole system.
   */
  class Database
  {
    // Declaring data variables: host, account, password and db_name
    var $host = '';
    var $account = '';
    var $password = '';
    var $db_name = '';
    var $charset = '';

    function __construct()
    {
      // Loading data details and initializng class
      require_once("public_vars.php");
      $this->host = DB_HOST;
      $this->account = DB_USER_NAME;
      $this->password = DB_PASSWORD;
      $this->db_name = DB_NAME;
      $this->charset = 'utf-8';
    }

    function connect_to_db()
    {
      // Establishing data connection
      $sconnect = mysqli_connect($this->host, $this->account, $this->password, $this->db_name)
                  or die("Database Connection Failed! <br>"."Reason: ".mysqli_connect_error());
      return $sconnect;
    }

    function get_field_names($connection, $table_name)
    {
      $query = "SHOW COLUMNS FROM $table_name";

      $result = mysqli_query($connection, $query);

      while ($field_array = mysqli_fetch_assoc($result)) {
        $fields[] = $field_array;
      }

      foreach ($fields as $key => $value) {
        foreach ($value as $field => $field_value) {
          if ($field == 'Field') {
            $field_list[] = $field_value;
          }
        }
      }

      return $field_list;
    }

    function add_new($connection, $form_data, $table_name)
    {
      if(!is_array($form_data))
      {
        return FALSE;
        exit();
      }

      foreach($form_data as $field => $value)
      {
        $form_data[$field] = trim($form_data[$field]);
        $form_data[$field] = strip_tags($form_data[$field]);
        $form_data[$field] = mysqli_real_escape_string($connection, $form_data[$field]);

        $field_array[] = $field;
        $value_array[] = $form_data[$field];
      }

      $fields = implode(",", $field_array);
      $values = implode('","', $value_array);
      $query = "INSERT INTO $table_name ($fields) VALUES (\"$values\")";

      if ($result = mysqli_query($connection, $query))
        return TRUE;
      else
        return FALSE;
    }

    function update_data($connection, $form_data, $table_name, $prim_key, $prim_value)
    {
      $query = "UPDATE $table_name SET ";

      if (is_array($form_data)) {
        foreach ($form_data as $field => $value) {
          $value = trim($value);
          $value = strip_tags($value);
          $value = mysqli_real_escape_string($connection, $value);

          $query .= "$field ="."'".$value."'".", ";
        }
        $query = substr($query, 0, strlen($query) - 2)." WHERE $prim_key = "."'".$prim_value."'";

        if ($result = mysqli_query($connection, $query))
          return TRUE;
        else
          return FALSE;
      } else {
        return FALSE;
      }
    }

    function display_all($connection, $table_name, $order_field='')
    {
      $query = (strlen($order_field) > 0 ) ? "SELECT * FROM $table_name ORDER BY $order_field ASC" : "SELECT * FROM $table_name" ;

      $result = mysqli_query($connection, $query);
      $record_count = mysqli_num_rows($result);
      $rows = array();

      if ($record_count > 0) {
        while($record = mysqli_fetch_assoc($result)){
          $rows[] = $record;
        }
        return $rows;
      } else {
        return $rows;
      }
    }

    function display_data($connection, $table_name, $field_list, $order_field='')
    {
      $fields = implode(",", $field_list);
      $query = (strlen($order_field) > 0) ? "SELECT $fields FROM $table_name ORDER BY $order_field ASC" : "SELECT $fields FROM $table_name" ;

      $result = mysqli_query($connection, $query);
      $records = mysqli_num_rows($result);
      $rows = array();

      if ($records > 0) {
        while($record = mysqli_fetch_assoc($result)){
          $rows[] = $record;
        }
        return $rows;
      } else {
        return $rows;
      }
    }

    function get_active_users($connection)
    {
      $active_users = [];
      $active_result = mysqli_query($connection, "SELECT user_name FROM priveleges") or die("Couldn't perform query.");

      $active_num = mysqli_num_rows($active_result);
      if ( $active_num > 0 ) {
        while ($active_record = mysqli_fetch_assoc($active_result)) {
          $users[] = $active_record;
        }
        foreach ($users as $value) {
          foreach ($value as $key => $user) {
            $active_users[] = $user;
          }
        }
        return $active_users;
      } else {
        return $active_users;
      }
    }

    function delete_data($connection, $table_name, $prim_key, $prim_value)
    {
      $query = "DELETE FROM $table_name WHERE $prim_key= "."'".$prim_value."'";

      if ($result = mysqli_query($connection, $query)) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    function search_by_multiple($connection, $table_name, $fields, $search_array, $order_field)
    {
      $field_list = implode(", ", $fields);
      $query = "SELECT $field_list FROM $table_name WHERE ";
      foreach ($search_array as $key => $value) {
        $query .= $key." = "."'".$value."'"." AND ";
      }
      $query = substr($query, 0, strlen($query) - 4);
      $query .= "ORDER BY ".$order_field." ASC";

      $result = mysqli_query($connection, $query);
      $records = mysqli_num_rows($result);
      $rows = [];

      if ($records > 0) {
        while($record = mysqli_fetch_assoc($result)) {
          $rows[] = $record;
        }
        return $rows;
      } else {
        return $rows;
      }
    }

    function search_data($connection, $table_name, $fields, $prim_key, $criteria, $order_field)
    {
      $field_list = implode(", ", $fields);
      $query = "SELECT $field_list FROM $table_name WHERE $prim_key LIKE "."'%".$criteria."%' ORDER BY ".$order_field." ASC ";

      $result = mysqli_query($connection, $query);
      $records = mysqli_num_rows($result);
      $rows = [];

      if ($records > 0) {
        while($record = mysqli_fetch_assoc($result)) {
          $rows[] = $record;
        }
        return $rows;
      } else {
        return $rows;
      }
    }

    function view_data($connection, $table_name, $prim_key, $prim_value)
    {
      include_once("public_functions.php");

      $query = "SELECT * FROM $table_name WHERE $prim_key="."'".$prim_value."'";

      $result = mysqli_query($connection, $query);
      $view_num = mysqli_num_rows($result);
      $view_array = [];

      if ( $view_num > 0 ) {
        while($record = mysqli_fetch_array($result)){
          $rows[] = $record;
        }
        foreach ($rows as $value) {
          foreach ($value as $vkey => $kvalue) {
            $view_array[$vkey] = $kvalue;
          }
        }
        return $view_array;
      } else {
        return $view_array;
      }
    }

    function view_array_data()
    {
      # code...
    }

    function create_data_array($connection, $table_name, $field_name, $distinct = FALSE, $sorted = FALSE)
    {
      $sql = ($distinct) ? "SELECT DISTINCT $field_name FROM $table_name " : "SELECT $field_name FROM $table_name" ;

      $result = mysqli_query($connection, $sql);
      $result_number = mysqli_num_rows($result);
      $data_array = [];

      if ($result_number > 0) {
        while ($record = mysqli_fetch_assoc($result)) {
          $rows[] = $record;
        }
        foreach ($rows as $value) {
          foreach ($value as $vkey => $kvalue) {
            $data_array[] = $kvalue;
          }
        }
        if ($sorted) {
          sort($data_array);
        }
        return $data_array;
      } else {
        return $data_array;
      }
    }

    function data_exists($connection, $table_name, $fields, $criteria)
    {

        $field_list = implode(",", $fields);
        $query = "SELECT $field_list FROM $table_name WHERE ";

        foreach($criteria as $key => $value) {
            $query .= $key." = "."'".$value."'"." AND ";
        }
        $query = substr($query, 0, strlen($query) - 4);

        $result = mysqli_query($connection, $query);
        $result_number = mysqli_num_rows($result);

        if ($result_number > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * param string $tables
     */
    public function backup_database($connection, $tables = '*')
    {
        mysqli_query($connection, "SET NAMES '". $this->charset."'");
        try
        {
            /**
            * Tables to export
            */
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($connection, 'SHOW TABLES');
                while($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->db_name.";\n\n";
            $sql .= 'USE '.$this->db_name.";\n\n";

            /**
            * Iterate tables
            */
            foreach($tables as $table) {
                // echo "Backing up ".$table." table...";

                $result = mysqli_query($connection, 'SELECT * FROM '.$table);
                $numFields = mysqli_num_fields($result);

                $sql .= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysqli_fetch_row(mysqli_query($connection, 'SHOW CREATE TABLE '.$table));
                $sql.= "\n\n".$row2[1].";\n\n";

                for ($i = 0; $i < $numFields; $i++) {
                    while($row = mysqli_fetch_row($result))
                    {
                        $sql .= 'INSERT INTO '.$table.' VALUES(';
                        for($j=0; $j<$numFields; $j++)
                        {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                            if (isset($row[$j])) {
                                $sql .= '"'.$row[$j].'"' ;
                            } else {
                                $sql.= '""';
                            }

                            if ($j < ($numFields-1)) {
                                $sql .= ',';
                            }
                        }

                        $sql.= ");\n";
                    }
                }

                $sql.="\n\n\n";

                // echo " OK" . "\n";
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return FALSE;
        }

        return $this->download_file($sql);
    }

    /**
     * Save SQL to file
     * param string $sql
     */
    protected function download_file(&$sql)
    {
        if (!$sql) return FALSE;

        try
        {
            $file_name = $this->db_name.'_data_backup_'.date("d", time()).'_'.date("M", time()).'_'.date("y", time()).'.sql';

            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"".$file_name."\"");
            echo $sql;
            exit;

        } catch (Exception $e) {
            var_dump($e->getMessage());
            return FALSE;
        }

        return TRUE;
    }

    function change_full_name($connection, $value_to_change, $prim_value)
    {
      // $user_details = "UPDATE login_details SET full_name="."'".$value_to_change."'"." WHERE user_name="."'".$prim_value."'";
      $user_sql = "UPDATE users SET edited_by="."'".$value_to_change."'"." WHERE user_name="."'".$prim_value."'";

      // $detials = mysqli_query($connection, $user_details);
      $sql_result = mysqli_query($connection, $user_sql);
    }

    function get_user_priveleges($connection, $user_name)
    {
      $user_sql = "SELECT * FROM priveleges WHERE user_name="."'".$user_name."'";

      $user_result = mysqli_query($connection, $user_sql);
      $user_num = mysqli_num_rows($user_result);
      $user_array = [];

      $fields = $this->get_field_names($connection, 'priveleges');

      if ($user_num > 0) {
        while ($record = mysqli_fetch_array($user_result)) {
          $rows[] = $record;
        }
        foreach ($rows as $key => $value) {
          $user_array = filter_array($value, $fields);
        }
        return $user_array;
      } else {
        return $user_array;
      }
    }

    function get_average_score($connection, $table_name, $criteria)
    {
      $query = "SELECT AVG(total_score) AS avg_score FROM $table_name WHERE ";

      foreach($criteria as $key => $value) {
          $query .= $key." = "."'".$value."'"." AND ";
      }
      $query = substr($query, 0, strlen($query) - 4);

      $result = mysqli_query($connection, $query);
      $average = mysqli_num_rows($result);
      $avg_array = [];

      if ($average > 0) {
        while ($record = mysqli_fetch_assoc($result)) {
          $avg_array[] = $record;
        }
        return $avg_array;
      } else {
        return $avg_array;
      }
    }

    function update_average($connection, $table, $fields)
    {
      $avg = $db->get_average_score($connection, $table, $fields);
      $average = (float)$avg[0]['avg_score'];
      $avg_sql = "UPDATE $table SET average_score="."'"."$average"."' WHERE ";
      foreach ($fields as $key => $value) {
        $avg_sql .= $key." = "."'".$value."'"." AND ";
      }
      $avg_sql = substr($avg_sql, 0, strlen($avg_sql) - 4);
      $result = mysqli_query($connection, $avg_sql);
    }

    function exec_query($connection, $query)
    {
      $result = mysqli_query($connection, $query) or
                die("Couldn't execute query!<br>"."Reason:".mysql_error());
      return $result;
    }

    function close_connection($connection)
    {
      $result = mysqli_close($connection) or die('Cannot close connection');
      return $result;
    }

  }

  /**
   * This class is responsible for all the legal things in the
   * entire system. It grants access level privileges to all kinds of users.
   * This is very necessary for security and report logs.
   */
  class Priveleges
  {
    var $user_name;
    var $is_admin;
    var $is_head;
    var $department;
    var $is_class_teacher;
    var $class_name;
    var $year_7;
    var $year_7_subject;
    var $year_8;
    var $year_8_subject;
    var $year_9;
    var $year_9_subject;
    var $ig_1;
    var $ig_1_subject;
    var $ig_2;
    var $ig_2_subject;
    var $as_level;
    var $as_level_subject;
    var $a_level;
    var $a_level_subject;

    function __construct($user_array)
    {
      $user_name = $user_array['user_name'];
      $is_admin = $user_array['is_admin'];
      $is_head = $user_array['is_head'];
      $department = $user_array['department'];
      $is_class_teacher = $user_array['is_form_teacher'];
      $class_name = $user_array['form_name'];
      $year_7 = $user_array['teaches_year_7'];
      $year_7_subject = $user_array['year_7_subject'];
      $year_8 = $user_array['teaches_year_8'];
      $year_8_subject = $user_array['year_8_subject'];
      $year_9 = $user_array['teaches_year_9'];
      $year_9_subject = $user_array['year_9_subject'];
      $ig_1 = $user_array['teaches_ig_1'];
      $ig_1_subject = $user_array['ig_1_subject'];
      $ig_2 = $user_array['teaches_ig_2'];
      $ig_2_subject = $user_array['ig_2_subject'];
      $as_level = $user_array['teaches_as_level'];
      $as_level_subject = $user_array['as_level_subject'];
      $a_level = $user_array['teaches_a_level'];
      $a_level_subject = $user_array['a_level_subject'];
    }

    function is_dep_head()
    {
      $value = ($this->is_head) ? TRUE : FALSE ;
      return $value;
    }

    function department_name()
    {
      return $this->department;
    }

    function is_admin()
    {
      $value = ($this->is_admin) ? TRUE : FALSE ;
      return $value;
    }

    function is_form_teacher()
    {
      $value = ($this->is_form_teacher) ? TRUE : FALSE ;
      return $value;
    }

    function form_name()
    {
      if ($this->is_class_teacher) {
        return $this->class_name;
      }
    }

    function teaches_year_7()
    {
      $value = ($this->year_7) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_year_8()
    {
      $value = ($this->year_8) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_year_9()
    {
      $value = ($this->year_9) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_ig_1()
    {
      $value = ($this->ig_1) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_ig_2()
    {
      $value = ($this->ig_2) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_as_level()
    {
      $value = ($this->as_level) ? TRUE : FALSE ;
      return $value;
    }

    function teaches_a_level()
    {
      $value = ($this->a_level) ? TRUE : FALSE ;
      return $value;
    }

    function year_7_subjects()
    {
      $class_subjects = explode(",", $year_7_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function year_8_subjects()
    {
      $class_subjects = explode(",", $year_8_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function year_9_subjects()
    {
      $class_subjects = explode(",", $year_9_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function ig_1_subjects()
    {
      $class_subjects = explode(",", $ig_1_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function ig_2_subjects()
    {
      $class_subjects = explode(",", $ig_2_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function as_level_subjects()
    {
      $class_subjects = explode(",", $as_level_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }

    function a_level_subjects()
    {
      $class_subjects = explode(",", $a_level_subject);
      foreach ($class_subjects as $key => $value) {
        $subjects[] = $value;
      }
      return $subjects;
    }
  }

?>
