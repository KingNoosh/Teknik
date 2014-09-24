<?php
include('../includes/config.php');

if(isset($_POST) && isset($_SESSION))
{
  $filename = rawurldecode($_POST['uploadID']);
  if (isset($_SESSION[$filename]) && $_SESSION[$filename] == $filename)
  {
    $file_db = $db->select('uploads', "filename=? LIMIT 1", array($filename));
    if ($file_db)
    {
      $delete_key = generate_code($file_db['filename'], $CONF);
      $data = array(
          "delete_key" => $delete_key
      );
      
      $post_id = $db->update($data, 'uploads', 'filename=?', array($filename));
      unset($_POST);
      echo json_encode(array('result' => array('url' => get_page_url("u", $CONF).'/'.$file_db['filename'].'/'.$delete_key)));
    }
    else
    {
      echo json_encode(array('error' => $CONF['errors']['NoFile']));
    }
  }
  else
  {
    echo json_encode(array('error' => $CONF['errors']['InvRequest']));
  }
}
else
{
  echo json_encode(array('error' => $CONF['errors']['InvRequest']));
}
?>