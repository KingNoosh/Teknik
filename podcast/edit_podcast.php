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
  $file = rawurldecode($_POST['file']);
  
  $post_select = $db->select('podcast', "id=? LIMIT 1", array($postID));
  
  if ($post_select)
  {
    $orig_title = $post_select['title'];
    $filename = $post_select['file_name'];
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to edit this podcast post.";
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
      $error = "You need to submit an actual description.";
      $success = false;
    }
    
    if($success && empty($file))
    {
      $error = "You need to upload a file for the podcast.";
      $success = false;
    }
    
    if ($success && !$user->admin)
    {
      $error = "You are not allowed to edit this podcast.";
      $success = false;
    }
    
    if($success)
    {
      $podcast_dir = $CONF['podcast_dir'].$orig_title."\\";
      if ($orig_title != $title)
      {
        mkdir($CONF['podcast_dir'].$title, 0777, true);
        $podcast_dir = $CONF['podcast_dir'].$title."\\";
      }
      $files = explode(',', $file);
      $oldFiles = explode(',', $filename);
      // Delete Removed Files
      $diff = array_diff($oldFiles, $files);
      foreach ($diff as $single)
      {
        unlink($podcast_dir.$single);
      }
      
      if ($orig_title != $title)
      {
        // Move Old Files to new Directory
        $toMove = scandir($CONF['podcast_dir'].$orig_title);
        foreach ($toMove as $single)
        {
          if (file_exists($CONF['podcast_dir'].$orig_title."\\".$single) && is_file($CONF['podcast_dir'].$orig_title."\\".$single))
          {
            rename($CONF['podcast_dir'].$orig_title."\\".$single, $podcast_dir.$single);
          }
        }
        rmdir($CONF['podcast_dir'].$orig_title);
      }
      
      // Move all new files
      $diff = array_diff($files, $oldFiles);
      foreach ($files as $single)
      {
        if (file_exists($CONF['podcast_dir'].$single))
        {
          rename($CONF['podcast_dir'].$single, $podcast_dir.$single);
        }
      }
      
      $data = array(
          "title" => $title,
          "tags" => "",
          "file_name" => $file,
          "description" => $post
      );
      
      $post_id = $db->update($data, 'podcast', 'id=?', array($postID));
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
    echo "That podcast post does not exist.";
  }
}
else
{
  echo "$_POST is not set.";
}
?>