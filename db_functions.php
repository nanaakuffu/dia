<?php
  /*
    Needed for the report_pdf view_class
  */
  require('fpdf/fpdf.php');

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
      // $sconnect = mysqli_connect($this->host, $this->account, $this->password, $this->db_name);
      if (!mysqli_connect($this->host, $this->account, $this->password, $this->db_name)) {
        die( include_once 'errors.php');
      } else {
        $sconnect = mysqli_connect($this->host, $this->account, $this->password, $this->db_name);
        return $sconnect;
      }
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

    function add_new_data($connection, $form_data, $table_name)
    {
      if(!is_array($form_data))
      {
        return FALSE;
        exit();
      }

      foreach($form_data as $field => $value)
      {
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

    function display_data($connection, $table_name, $field_list, $order_field='', $privelege)
    {
      $fields = implode(",", $field_list);

      switch ($privelege) {
        case '*':
          $query = "SELECT $fields FROM $table_name";
          break;

        case 'Level':
          $query = "SELECT $fields FROM $table_name WHERE class_name='AS Level' OR class_name='A Level'";
          break;

        default:
          $query = "SELECT $fields FROM $table_name WHERE class_name="."'".$privelege."'";
          break;
      }

      if (strlen($order_field) > 0) {
        $query .= " ORDER BY $order_field ASC";
      }

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

    function get_last_logged_id($connection, $user_name)
    {
      $sql = "SELECT log_id, login_date, login_time FROM login_details WHERE user_name="."'".$user_name."' ORDER BY login_date DESC, login_time DESC";

      $log_result = mysqli_query($connection, $sql);
      $log_number = mysqli_num_rows($log_result);

      if ($log_number > 0) {
        while ($records = mysqli_fetch_assoc($log_result)) {
          $logs[] = $records;
        }

        return $logs[0]['log_id'];
      }
    }

    // function view_array_data()
    // {
    //   # code...
    // }

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
      $avg = $this->get_average_score($connection, $table, $fields);
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
   * entire system. It grants access level privileges to all of users.
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
      $ig_1 = $user_array['teaches_igcse_1'];
      $ig_1_subject = $user_array['igcse_1_subject'];
      $ig_2 = $user_array['teaches_igcse_2'];
      $ig_2_subject = $user_array['igcse_2_subject'];
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
      $value = ($this->is_admin == '1' ) ? TRUE : FALSE ;
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

  /*
    Credits Oliver licensed to FPDF
  */

  class Report_PDF extends FPDF
  {
    var $ProcessingTable=false;
    var $aCols=array();
    var $TableX;
    var $HeaderColor;
    var $RowColors;
    var $ColorIndex;

    function Header()
    {
        //Print the table header if necessary
        if($this->ProcessingTable)
            $this->TableHeader();
    }

    function TableHeader()
    {
        $this->SetFont('Arial','B',12);
        $this->SetX($this->TableX);
        $fill=!empty($this->HeaderColor);
        if($fill)
            $this->SetFillColor($this->HeaderColor[0],$this->HeaderColor[1],$this->HeaderColor[2]);
        foreach($this->aCols as $col)
            $this->Cell($col['w'],6,$col['c'],1,0,'C',$fill);
        $this->Ln();
    }

    function Row($data)
    {
        $this->SetX($this->TableX);
        $ci=$this->ColorIndex;
        $fill=!empty($this->RowColors[$ci]);
        if($fill)
            $this->SetFillColor($this->RowColors[$ci][0],$this->RowColors[$ci][1],$this->RowColors[$ci][2]);
        foreach($this->aCols as $col)
            $this->Cell($col['w'],5,$data[$col['f']],1,0,$col['a'],$fill);
        $this->Ln();
        $this->ColorIndex=1-$ci;
    }

    function CalcWidths($width,$align)
    {
        //Compute the widths of the columns
        $TableWidth=0;
        foreach($this->aCols as $i=>$col)
        {
            $w=$col['w'];
            if($w==-1)
                $w=$width/count($this->aCols);
            elseif(substr($w,-1)=='%')
                $w=$w/100*$width;
            $this->aCols[$i]['w']=$w;
            $TableWidth+=$w;
        }
        //Compute the abscissa of the table
        if($align=='C')
            $this->TableX=max(($this->w-$TableWidth)/2,0);
        elseif($align=='R')
            $this->TableX=max($this->w-$this->rMargin-$TableWidth,0);
        else
            $this->TableX=$this->lMargin;
    }

    function AddCol($field=-1,$width=-1,$caption='',$align='L')
    {
        //Add a column to the table
        if($field==-1)
            $field=count($this->aCols);
        $this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
    }

    function Table($connection, $query, $prop=array())
    {
        //Issue query
        $res=mysqli_query($connection, $query) or die('Error: '.mysql_error()."<BR>Query: $query");
        //Add all columns if none was specified
        if(count($this->aCols)==0)
        {
            $nb=mysqli_num_fields($res);
            for($i=0;$i<$nb;$i++)
                $this->AddCol();
        }
        //Retrieve column names when not specified
        foreach($this->aCols as $i=>$col)
        {
            if($col['c']=='')
            {
                if(is_string($col['f']))
                    $this->aCols[$i]['c']=ucfirst($col['f']);
                else
                    $this->aCols[$i]['c']=ucfirst(mysqli_fetch_field_direct($res,$col['f'])->name);
            }
        }
        //Handle properties
        if(!isset($prop['width']))
            $prop['width']=0;
        if($prop['width']==0)
            $prop['width']=$this->w-$this->lMargin-$this->rMargin;
        if(!isset($prop['align']))
            $prop['align']='C';
        if(!isset($prop['padding']))
            $prop['padding']=$this->cMargin;
        $cMargin=$this->cMargin;
        $this->cMargin=$prop['padding'];
        if(!isset($prop['HeaderColor']))
            $prop['HeaderColor']=array();
        $this->HeaderColor=$prop['HeaderColor'];
        if(!isset($prop['color1']))
            $prop['color1']=array();
        if(!isset($prop['color2']))
            $prop['color2']=array();
        $this->RowColors=array($prop['color1'],$prop['color2']);
        //Compute column widths
        $this->CalcWidths($prop['width'],$prop['align']);
        //Print header
        $this->TableHeader();
        //Print rows
        $this->SetFont('Arial','',11);
        $this->ColorIndex=0;
        $this->ProcessingTable=true;
        while($row=mysqli_fetch_array($res))
            $this->Row($row);
        $this->ProcessingTable=false;
        $this->cMargin=$cMargin;
        $this->aCols=array();
    }

    function Generate_Mid_Term_Report($connection, $full_name, $info_array, $sql, $titles)
    {
      $this->AddPage();
      // $this->SetY(100);
      $this->SetFont('Arial', 'B', 12);
      $this->Cell(90, 20, 'Student Name', 1);
      $this->SetFont('Arial', '');
      $this->Cell(220, 20, $info_array['student_full_name'], 1);

      $this->SetFont('Arial', 'B', 12);
      $this->Cell(90, 20, 'Class / Grade', 1);
      $this->SetFont('Arial', '');
      $this->Cell(90, 20, $info_array['class_name'], 1);

      $this->SetFont('Arial', 'B', 12);
      $this->Cell(90, 20, 'Form Teacher', 1);
      $this->SetFont('Arial', '');
      $this->Cell(200, 20, $full_name, 1, 1);

      $this->Ln(0);

      $this->SetFont('Arial', 'B');
      $this->Cell(180, 25, "Parent / Guardian Name", 1);
      $this->Cell(150, 25, "Academic Year", 0, 0,'C');
      $this->SetFont('Arial', 'B', 18);
      $this->Cell(150, 25, "DAYSPRING INTERNATIONAL ACADEMY", 0, 1);
      // $this->Ln(0);
      $address = "Mr Isaac K. Gyan \nP. O. Box KN 4211 \nKaneshie, Accra\n";
      $this->Ln(0);
      $this->SetFont('Arial', 'I', 12);
      $this->MultiCell(180, 30, $address, 1);

      $this->SetXY(350, 70);
      $this->SetFont('Arial', '', 11);
      $school_address = "East-Legon Ext. Nmai Dzorn,\n PO BOX AF 1841, Adenta Accra Ghana\n Tel:(233) 0302-937-881 / 0302-541154\n Cel: (233) 024-251-1271, 024-817-0663,\n 020-946-6192";
      $this->MultiCell(250, 15, $school_address, 0, 'C');

      $grading_system ="A = 100 - 90 (Excellent)\nB = 89 - 80 (Very Good)\nC = 79 - 70 (Good)\nD = 69 - 60 (Satisfactory)\nE = 59 - 50 (Fair)\nF = 49 - 0 (Fail)";
      $this->SetXY(600, 70);
      $this->SetFont('Arial', '', 11);
      $this->MultiCell(250, 15, $grading_system, 0, 'L');

      $this->SetXY(209, 70);
      $this->SetFont('Arial', 'B', 12);
      $this->Cell(150, 25, $info_array['academic_year'], 0, 0, 'C');

      $this->Image( "site_pics\mid_term_logo_sm.jpg", 230, 90, 100, "JPG" );

      $this->SetXY(0, 180);
      $this->Ln(0);
      $this->SetFont('Arial', 'B', 18, 0);
      $this->Cell(0, 25, "MIDTERM PROGRESS REPORT", 0, 1, 'C');

      $this->Table($connection, $sql);
      $this->Output();
    }
  }

?>
