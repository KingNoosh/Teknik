<?php
include('../includes/config.php');

if(isset($_POST) && isset($_SESSION))
{
  $file = rawurldecode($_POST['uploadID']);
  if (isset($_SESSION[$file]) && $_SESSION[$file] == $file)
  {
    $file_db = $db->select('uploads', "url=? LIMIT 1", array($file));
    if ($file_db)
    {
      $delete_key = generate_code($file_db['url'], $CONF);
      $data = array(
          "delete_key" => $delete_key
      );
      
      $post_id = $db->update($data, 'uploads', 'url=?', array($file));
      unset($_POST);
      echo json_encode(array('result' => array('url' => get_page_url("u", $CONF).'/'.$file_db['url'].'/'.$delete_key)));
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