<?php
require_once('../includes/config.php');

$post_id = 0;
$title = "";
$comment = "";

//check to see that the form has been submitted
if(isset($_POST))
{
    //retrieve the $_POST variables
    $post_id = rawurldecode($_POST['postID']);
    $service = rawurldecode($_POST['service']);
    $comment = rawurldecode($_POST['comment']);
    
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to make a comment.";
      $success = false;
    }
    
    if($success && empty($comment))
    {
      $error = "You need to submit an actual comment.";
      $success = false;
    }
    
    if($success)
    {
      $data = array(
          "service" => $service,
          "reply_id" => $post_id,
          "user_id" => $user->id,
          "title" => $title,
          "post" => $comment,
          "date_posted" => date("Y-m-d H:i:s",time())
      );
      
      $post_id = $db->insert($data, 'comments');
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
  echo "$_POST is not set.";
}
?>