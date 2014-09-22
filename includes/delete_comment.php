<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $post = $db->select('comments', "id=? LIMIT 1", array($id));
  if ($post)
  {
    $success = true;
    $userID = $post['user_id'];
    if($success && (!$user->admin && ($user->id != $userID)))
    {
      $error = "You are not allowed to delete this comment.";
      $success = false;
    }
    
    if ($success)
    {
      $db->delete('comments', 'id=?', array($id));
      echo "true";
    }
    else
    {
      echo $error;
    }
  }
  else
  {
    echo "That comment does not exist.";
  }
}
else
{
  echo "You need to be logged in to delete this comment.";
}
?>