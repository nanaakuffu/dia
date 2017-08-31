<?php
/* Script name: uploadFile.php
* Description: Uploads a file via HTTP using a POST form.
*/
  if(!isset($_POST['Upload']))          #5
  {
    include("form_upload.inc");
  } # endif
  else                                  #9
  {
    //var_dump($_FILES);
    if ($_FILES['pix']['size'] > $_POST['MAX_FILE_SIZE']) {
      echo "File is too big.";
    }
    if($_FILES['pix']['tmp_name'] == "none")        #11
    {
      echo "<b>File did not successfully upload. Check the
          file size. File must be less than 500K.<br>";
      include("form_upload.inc");
      exit();
    }
    if(!ereg("image",$_FILES['pix']['type']))       #16
    {
      echo "<b>File is not a picture. Please try another file.</b><br>";
      //echo "<p> Hello";
      include("form_upload.inc");
      exit();
    }
    else                                            #23
    {
      $destination = 'site_pics/'.$_FILES['pix']['name'];
      $temp_file = $_FILES['pix']['tmp_name'];
      move_uploaded_file($temp_file,$destination);
      echo "<p><b>The file has successfully uploaded:</b>
            {$_FILES['pix']['name']}
            ({$_FILES['pix']['size']})</p>";
    }
  }
?>
