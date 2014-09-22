<?php
require_once('../includes/config.php');

if (isset($_POST['file']))
{
  if ($logged_in && $user->admin)
  {
    $files = explode(',', rawurldecode($_POST['file']));
    foreach ($files as $file)
    {
      if (file_exists($CONF['podcast_dir'].$file))
      {
        $result = unlink($CONF['podcast_dir'].$file);
      }
    }
    if ($result)
    {
      echo json_encode(array("result" => True));      
    }
    else
    {
      echo json_encode(array("error" => $CONF['errors']['InvFile']));
    }
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