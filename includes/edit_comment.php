<?php
require_once('../includes/config.php');

$commentID = 0;
$post = "";

//check to see that the form has been submitted
if(isset($_POST))
{
  //retrieve the $_POST variables
  $commentID = rawurldecode($_POST['commentID']);
  $post = rawurldecode($_POST['post']);
  
  $comment_select = $db->select('comments', "id=? LIMIT 1", array($commentID));
  
  if ($comment_select)
  {
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to edit this comment.";
      $success = false;
    }
    
    if($success && empty($post))
    {
      $error = "You need to submit an actual comment.";
      $success = false;
    }
    
    if ($success && $comment_select['user_id'] != $user->id && !$user->admin)
    {
      $error = "You are not allowed to edit this comment.";
      $success = false;
    }
    
    if($success)
    {
      $data = array(
          "post" => $post
      );
      
      $comment_id = $db->update($data, 'comments', 'id=?', array($commentID));
      unset($_POST);
      echo "true";
    }
    else
    {
      unset($_POST);
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
  echo "$_POST is not set.";
}
?>