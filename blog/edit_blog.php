<?php
require_once('../includes/config.php');

$userID = 0;
$postID = 0;
$authorID = 0;
$title = "";
$post = "";

//check to see that the form has been submitted
if(isset($_POST))
{
  //retrieve the $_POST variables
  $userID = rawurldecode($_POST['userID']);
  $postID = rawurldecode($_POST['postID']);
  $title = rawurldecode($_POST['title']);
  $post = rawurldecode($_POST['post']);
  
  $post_select = $db->select('blog', "id=? LIMIT 1", array($postID));
  
  if ($post_select)
  {
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to edit this blog post.";
      $success = false;
    }
    
    if($success && empty($title))
    {
      $error = "You need to submit a title with your post.";
      $success = false;
    }
    
    if($success && strlen($title) > 140)
    {
      $error = "The maximum length for your title is 140 characters.";
      $success = false;
    }
    
    if($success && empty($post))
    {
      $error = "You need to submit an actual post.";
      $success = false;
    }
    
    if ($success && !$user->admin && $post_select['author_id'] != $user->id)
    {
      $error = "You are not allowed to edit this post.";
      $success = false;
    }
    
    if($success)
    {
      $data = array(
          "title" => $title,
          "tags" => "",
          "post" => $post
      );
      
      $post_id = $db->update($data, 'blog', 'id=?', array($postID));
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
    echo "That blog post does not exist.";
  }
}
else
{
  echo "$_POST is not set.";
}
?>