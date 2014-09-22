<?php
include('../includes/config.php');
 
$filename = "";
if(isset($_POST) && $logged_in)
{
  //retrieve the $_POST variables
  $filename = rawurldecode($_POST['file']);
  $file_path  = $CONF['upload_dir'] . $filename;
  $thumbnail_path  = $CONF['upload_dir'] . 'thumbnails/150_150_' . $filename;
  $file_db = $db->select('uploads', "filename=? LIMIT 1", array($filename));
  $temp_path = sys_get_temp_dir()."\\".$filename;

  if (file_exists($file_path) && $file_db)
  {
    if ($file_db['hash'] != "")
    {
      $crypt = new Cryptography();
      $result = $crypt->Decrypt($CONF['key'], $file_db['hash'], $file_path, $temp_path, $file_db['cipher']);
      $file_path = $temp_path;
    }
    $file_type = $file_db['type'];
    $pattern = "/^(image)\/(.*)$/";
    if(preg_match($pattern, $file_type))
    {
      $resizeObj = new resize($file_path);
      // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
      $resizeObj->resizeImage(150, 150, 'auto');
      $resizeObj->saveImage($thumbnail_path, 70);
      $data = array(
                "url" => $filename,
                "user_id" => $user->id,
                "date_added" => date("Y-m-d H:i:s",time())
            );
      $row_id = $db->insert($data, 'ricehalla');
      $data = array(
                "table_name" => 'ricehalla',
                "row_id" => $row_id,
                "user_id" => $user->id,
                "points" => 1
            );
      $db->insert($data, 'votes');
      echo "true";
    }
    else
    {
      echo "Please upload an actual image.";
    }
  }
  else
  {
    echo "No File Found";
  }
}
?>