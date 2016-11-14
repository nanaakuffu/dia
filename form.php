<?php
  /**
   *
   */
  class Form
  {
    var $fields = array();
    var $processor;
    var $nfields = 0;
    var $submit_name;
    var $submit_value;

    function __construct($processor, $submit_name, $submit_value)
    {
      $this->processor = $processor;
      $this->submit_name = $submit_name;
      $this->submit_value = $submit_value;
    }

    function addField($name, $label)
    {
      $this->fields[$this->nfields]['name'] = $name;
      $this->fields[$this->nfields]['label'] = $label;
      $this->nfields += 1;
    }

    function display_form($size, $max_length, $title="", $bool_message=FALSE, $message="")
    {
      include_once("public_functions.php");

      echo "<div class='w3-container'>
              <form class='w3-form' action='{$this->processor}' method='POST'>
                <div class='w3-container w3-red'>
                  <h3> Please fill out the following form </h3>
                </div>
                <div class='w3-container w3-row-padding'>
                  <div class='w3-twothird'>
                    <table cellpadding='10'>";
                    for ($i = 1; $i <= sizeof($this->fields); $i++) {
                      switch ($this->fields[$i-1]['name']) {
                        case 'gender':
                          echo "<tr><td style='text-align:right'><label> {$this->fields[$i-1]['label']} : </label></td>
                                <td><input class='w3-radio' type='radio' name='{$this->fields[$i-1]['name']}'
                                  value='Male' checked> Male
                                <input class='w3-radio' type='radio' name='{$this->fields[$i-1]['name']}'
                                  value='Female'> Female </td></tr>";
                          break;

                        case 'full_name':
                          echo "<input type='hidden', name='{$this->fields[$i-1]['name']}'>";
                          break;

                        case 'student_number':
                          echo "<input type='hidden', name='{$this->fields[$i-1]['name']}'>";
                          break;

                        case 'class_name':
                          $class_array = array(1 => "Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
                          echo "<tr><td style='text-align:right'><label for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label></td>
                                <td>", select_data($class_array, $this->fields[$i-1]['name'], "Year 7"), "</td></tr>";
                          break;

                        default:
                          if (preg_match("/middle/i", $this->fields[$i-1]['name'])) {
                            echo "<tr><td style='text-align:right'><label for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label></td>
                                  <td><input class='w3-input w3-border w3-round' type='text' size='$size' name='{$this->fields[$i-1]['name']}'
                                  id='{$this->fields[$i-1]['name']}'></td></tr>";
                          } elseif (preg_match("/date/i", $this->fields[$i-1]['name'])) {
                            $year = get_date_name($this->fields[$i-1]['name'])."_year";
                            $month = get_date_name($this->fields[$i-1]['name'])."_month";
                            $day = get_date_name($this->fields[$i-1]['name'])."_day";
                            echo "<tr><td style='text-align:right'><label for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label></td>
                                  <td>", get_date_form($year,$month, $day, date("l, j F, Y") ),
                                  "</td></tr><input type='hidden' name='{$this->fields[$i-1]['name']}'>";
                          } elseif (preg_match("/pass/i", $this->fields[$i-1]['name'])) {
                            echo "<tr>
                                    <td style='text-align:right'><label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label></td>
                                    <td><input class='w3-input w3-border w3-round' type='password' size='$size'
                                      id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                      name='{$this->fields[$i-1]['name']}' required>
                                    </td>
                                  </tr>";
                          } elseif (preg_match("/id/i", $this->fields[$i-1]['name'])) {
                            echo "<input type='hidden', name='{$this->fields[$i-1]['name']}'>";
                          } else {
                            echo "<tr><td style='text-align:right'><label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label></td>
                                  <td><input class='w3-input w3-border w3-round' type='text' size='$size'
                                    id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                    name='{$this->fields[$i-1]['name']}' required>
                                  </td></tr>";
                          }
                          break;
                      }
                    }
              echo "</table>
                  </div>
                  <div class='w3-third'>
                    <fieldset style='margin-top:8px'>
                      <input class='w3-btn w3-round' type='submit' name='{$this->submit_name}'
                        value='{$this->submit_value}'>
                    </fieldset>";
                    if ($bool_message) {
                      echo  "<div class='w3-container w3-red' style='margin-top:10px'>
                                <p> Data Input Errors </p>
                             </div>
                             <div class='w3-container w3-border'>
                                <p>", $message, "</p>
                             </div>";
                    }
            echo "  </div>
                  </div>
                </div>
              </form>
            </div>";
    }

    function update_form($data_array, $size, $max_length, $display_name = "full_name", $bool_message=FALSE, $message='')
    {
      include_once("public_functions.php");

      echo "<div class='w3-container'>
              <form class='w3-form' action='{$this->processor}' method='POST'>
                <div class='w3-container w3-red'>
                  <h3> Details of <i> $data_array[$display_name] </i> </h3>
                </div>
                <div class='w3-container w3-row-padding'>
                  <div class='w3-twothird'>
                    <table cellpadding='10'>";
                    foreach ($data_array as $key => $value) {
                      switch ($key) {
                        case "gender":
                          echo "<tr><td style='text-align:right'><label>", get_column_name($key), ": </label></td>";
                          if ($value == "Male") {
                            echo "<td><input class='w3-radio' type='radio' name='{$key}'
                                    value='Male' checked> Male
                                  <input class='w3-radio' type='radio' name='{$key}'
                                    value='Female'> Female </td></tr>";
                          } else {
                            echo "<td><input class='w3-radio' type='radio' name='{$key}'
                                    value='Male'> Male
                                  <input class='w3-radio' type='radio' name='{$key}'
                                    value='Female' checked> Female </td></tr>";
                          }
                          break;

                        case 'full_name':
                          echo "<input type='hidden' name='{$key}' value='{$value}'>";
                          break;

                        case 'student_number':
                          echo "<input type='hidden' name='{$key}' value='{$value}'>";
                          break;

                        case 'class_name':
                          $class_array = array(1 => "Year 7", "Year 8", "Year 9", "IGCSE 1", "IGCSE 2", "AS Level", "A Level");
                          echo "<tr><td style='text-align:right'><label for='{$key}'>", get_column_name($key), ": </label></td>";
                          echo "<td>", select_data($class_array, $key, $value), "</td></tr>";
                          break;

                        default:
                          if (preg_match("/middle/i", $key)) {
                            echo "<tr><td style='text-align:right'><label for='{$key}'>", get_column_name($key), ": </label></td>";
                            echo "<td><input class='w3-input w3-border w3-round' type='text' size='$size' id='{$key}' value='{$value}' maxlength='$max_length'
                                name='$key'></td></tr>";
                          } elseif (preg_match("/date/i", $key)) {
                            $year = get_date_name($key)."_year";
                            $month = get_date_name($key)."_month";
                            $day = get_date_name($key)."_day";
                            echo "<tr><td style='text-align:right'><label for='{$key}'>", get_column_name($key), ": </label></td>";
                            echo "<td>", get_date_form($year,$month, $day, $value), "</td></tr>";
                            echo "<input type='hidden' name='{$key}'>";
                          } else {
                            echo "<tr><td style='text-align:right'><label class='w3-validate' for='{$key}'>", get_column_name($key), ": </label></td>";
                            echo "<td><input class='w3-input w3-border w3-round' type='text' size='$size' id='{$key}' value='{$value}' maxlength='$max_length'
                                name='$key' required></td></tr>";
                          }
                          break;
                        }
                      }
              echo "</table>
                  </div>
                  <div class='w3-third'>
                    <fieldset style='margin-top:8px'>
                      <table cellpadding='5'>
                        <tr><td><input class='w3-btn w3-round w3-padding-large' type='submit' name='{$this->submit_name}' value='{$this->submit_value}' style='width:40%'></td></tr>
                        <tr><td><input class='w3-btn w3-round w3-padding-large' type='submit' name='Delete' value='Delete' style='width:40%'> </td></tr>
                        <tr><td><a class='w3-btn w3-round w3-padding-large' href='display_students.php' style='width:40%'>Back</a></td></tr>
                      </table>
                    </fieldset>";
                    if ($bool_message) {
                      echo  "<div class='w3-container w3-red' style='margin-top:10px'>
                                <p> Data Input Errors </p>
                             </div>
                             <div class='w3-container w3-border'>
                                <p>", $message, "</p>
                             </div>";
                    }
          echo    "</div>
                </div>
              </form>
            </div>";
    }

    function login_form($size, $max_length, $type_array=[])
    {
      echo "<form class='w3-form' action='{$this->processor}' method='POST'>";
                for ($i = 1; $i <= sizeof($this->fields); $i++) {
                  echo "<div class='form-group'>";
                  if (preg_match("/pass/i", $this->fields[$i-1]['name'])) {
                      echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                            <input class='form-control' type='password' size='$size'
                                  id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                  name='{$this->fields[$i-1]['name']}' required>";
                  } elseif (preg_match("/middle/i", $this->fields[$i-1]['name'])){
                      echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                              <input class='form-control' type='text' size='$size'
                                  id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                  name='{$this->fields[$i-1]['name']}'>";
                  } elseif (preg_match("/mail/i", $this->fields[$i-1]['name'])) {
                      echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                            <input class='form-control' type='email' size='$size'
                                  id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                  name='{$this->fields[$i-1]['name']}' required>";
                  } elseif (preg_match("/type/i", $this->fields[$i-1]['name'])) {
                        if (@sizeof($type_array) > 0) {
                          echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                                  <br />", select_data($type_array, $this->fields[$i-1]['name'], "Teacher", 40);
                        } else {
                          echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                                <input class='form-control' type='text' size='$size'
                                    id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                    name='{$this->fields[$i-1]['name']}' value='Standard' readonly='readonly' />";
                        }
                   } else {
                        echo "<label class='w3-validate' for='{$this->fields[$i-1]['name']}'>{$this->fields[$i-1]['label']} : </label>
                              <input class='form-control' type='text' size='$size'
                                  id='{$this->fields[$i-1]['name']}' maxlength='$max_length'
                                  name='{$this->fields[$i-1]['name']}' required>";
                    }
                    echo "</div>";
                }
      echo " <input class='btn btn-primary w3-round w3-padding-medium' type='submit' name='{$this->submit_name}' value='{$this->submit_value}'>
                </form>";

    }
  }

?>
