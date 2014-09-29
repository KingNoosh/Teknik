<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $image = $db->select('walla', "id=? LIMIT 1", array($id));
  if ($image)
  {
    $success = true;
    $userID = $image['user_id'];
    if($success && $user->id != $userID)
    {
      $error = "You are not allowed to delete this image.";
      $success = false;
    }
    
    if ($success)
    {
      $db->delete('walls', 'id=?', array($id));
      $db->delete('votes', 'row_id=? AND table_name=?', array($id, 'walls'));
      echo "true";
    }
    else
    {
      echo $error;
    }
  }
  else
  {
    echo "That image does not exist.";
  }
}
else
{
  echo "You must be logged in to delete this image.";
}
?>