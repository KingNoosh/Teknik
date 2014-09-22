<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $post = $db->select('blog', "id=? LIMIT 1", array($id));
  if ($post)
  {
    $success = true;
    $userID = $post['user_id'];
    if($success && (($userID == 0 && !$user->admin) || ($userID != 0 && ($user->id != $userID && !$user->admin))))
    {
      $error = "You are not allowed to delete this post.";
      $success = false;
    }
    
    if ($success)
    {
      $db->delete('blog', 'id=?', array($id));
      echo "true";
    }
    else
    {
      echo $error;
    }
  }
  else
  {
    echo "That blog post does not exist.";
  }
}
else
{
  echo "You need to be logged in to delete this post.";
}
?>