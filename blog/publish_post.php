<?php
require_once('../includes/config.php');

//check to see that the form has been submitted
$id = 0;
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $publish = rawurldecode($_POST['publish']);
  $post = $db->select('blog', "id=? LIMIT 1", array($id));
  if ($post)
  {
    $success = true;
    if ($success && !$user->admin && $post['author_id'] != $user->id)
    {
      $error = "You are not allowed to publish this post.";
      if (!$publish)
      {
        $error = "You are not allowed to unpublish this post.";
      }
      $success = false;
    }

    if ($success)
    {
      $data = array(
          "published" => $publish,
          "date_published" => date("Y-m-d H:i:s",time())
      );

      $post_id = $db->update($data, 'blog', 'id=?', array($id));
      unset($_POST);
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
