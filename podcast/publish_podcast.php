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
    $title = rawurldecode($_POST['title']);
    $post = rawurldecode($_POST['post']);
    $file = rawurldecode($_POST['file']);
    
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to create a podcast.";
      $success = false;
    }
    
    if($success && empty($title))
    {
      $error = "You need to submit a title with your podcast.";
      $success = false;
    }
    
    if($success && strlen($title) > 140)
    {
      $error = "The maximum length for your title is 140 characters.";
      $success = false;
    }
    
    if($success && empty($post))
    {
      $error = "You need to submit an actual description for the podcast.";
      $success = false;
    }
    
    if($success && empty($file))
    {
      $error = "You need to upload a file for the podcast.";
      $success = false;
    }
    
    if($success && !$user->admin)
    {
      $error = "You are not allowed to post to this podcast.";
      $success = false;
    }
    
    if($success)
    {
      mkdir($_CONF['podcast_dir'].$title);
      $files = explode(',', $file);
      foreach ($files as $single)
      {
        rename($_CONF['podcast_dir'].$single, $_CONF['podcast_dir'].$title.'/'.$single);
      }
      $data = array(
          "title" => $title,
          "tags" => "",
          "description" => $post,
          "file_name" => $file,
          "date_posted" => date("Y-m-d H:i:s",time())
      );
      
      $post_id = $db->insert($data, 'podcast');
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