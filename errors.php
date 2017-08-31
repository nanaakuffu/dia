<?php
  require_once 'public_functions.php';
  base_header("Database Error!");

  echo "<div class='container'>
          <div class='row'>
            <div class='col-sm-3'>
              <br />
            </div>
            <div class='col-sm-6'>
              <div class='panel panel-default'>
                  <div class='panel-heading'>Database Connection Error</div>
                    <div class='panel-body'>
                      <ul class='fa-ul'>
                        <li><i class='fa-li fa fa-check-square'></i><b>Error</b>: Database Connection Failed! </li>
                        <li><i class='fa-li fa fa-check-square'></i><b>Reason</b>: ".mysqli_connect_error()."</li>
                      </ul>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>";

  create_footer();
?>
