<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $post = $db->select('podcast', "id=? LIMIT 1", array($id));
  if ($post)
  {
    $success = true;
    $filename = $post['file_name'];
    if($success && !$user->admin)
    {
      $error = "You are not allowed to delete this podcast.";
      $success = false;
    }
    
    if ($success)
    {
      if (file_exists($CONF['podcast_dir'].$filename))
      {
        unlink($CONF['podcast_dir'].$filename);
      }
      $db->delete('podcast', 'id=?', array($id));
      echo "true";
    }
    else
    {
      echo $error;
    }
  }
  else
  {
    echo "That podcast post does not exist.";
  }
}
else
{
  echo "You need to be logged in to delete this podcast.";
}
?>