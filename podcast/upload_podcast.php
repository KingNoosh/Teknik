<?php
require_once('../includes/config.php');

if (isset($_FILES['file']))
{
  if ($logged_in && $user->admin)
  {
    $tempFile = $_FILES['file']['tmp_name'];

    $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    $name = 'Teknik_Podcast_'.date('Y-m-d', time()).'.'.$fileType;
    
    $path = $CONF['podcast_dir'].$name;
    move_uploaded_file($tempFile, $path);
    echo json_encode(array("file" => array("name" => $name, "path" => $CONF['podcast_dir'])));
  }
  else
  {
    echo json_encode(array("error" => $CONF['errors']['NoAuth']));
  }
}
else
{
  echo json_encode(array("error" => $CONF['errors']['NoFile']));
}
?>