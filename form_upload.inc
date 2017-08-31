<!-- Script Name: form_upload.inc
Description: Displays a form to upload a file -->
<html>
  <head><title>File Upload</title></head>
  <body>
    <ol>
      <li>Enter the name of the picture you want to upload
        to our picture archive or use the browse button
        to navigate to the picture file.</li>
      <li>When the path to the picture file shows in the text
        field, click the Upload Picture button.</li>
    </ol>
    <!-- <?php
      include_once 'db_functions.php';
      include_once 'public_functions.php';

      $db = new Database();
      $con = $db->connect_to_db();
      $user_array = $db->get_user_priveleges($con, 'Admin');

      $priv = new Priveleges($user_array);

      $dep_name = $priv->is_admin();

      // echo "<pre>", var_dump($user_array), "</pre>";
      if ($dep_name == 1) {
        echo "TRUE";
      } else {
        echo "FALSE";
      }
    ?> -->
    <div align="center"><hr>
      <form enctype="multipart/form-data" action="file_upload.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="500000">
        <input type="file" name="pix" size="60">
        <p><input type="submit" name="Upload" value="Upload Picture">
        <a href='./Downloads/'> Here </a>
      </form>
  </body>
</html>
