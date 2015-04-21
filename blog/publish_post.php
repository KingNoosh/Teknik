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
    $publish = $post['publish'];
    if ($success && !$user->admin && $post_select['author_id'] != $user->id)
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
          "publish" => $publish,
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
