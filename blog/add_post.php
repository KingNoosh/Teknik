<?php
require_once('../includes/config.php');

$userID = 0;
$authorID = 0;
$title = "";
$post = "";

//check to see that the form has been submitted
if(isset($_POST))
{
    //retrieve the $_POST variables
    $userID = rawurldecode($_POST['userID']);
    $title = rawurldecode($_POST['title']);
    $post = rawurldecode($_POST['post']);

    //initialize variables for form validation
    $success = true;

    if($success && !$logged_in)
    {
      $error = "You must be logged in to make a blog post.";
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

    if($success && (($userID == 0 && !$user->admin) || ($userID != 0 && $user->id != $userID)))
    {
      $error = "You are not allowed to post to this blog.";
      $success = false;
    }

    if($success)
    {
      $data = array(
          "user_id" => $userID,
          "author_id" => $user->id,
          "title" => $title,
          "tags" => "",
          "post" => $post,
          "date_posted" => date("Y-m-d H:i:s",time())
      );

      $post_id = $db->insert($data, 'blog');
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
