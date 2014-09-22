<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_POST))
{
  $id = rawurldecode($_POST['id']);
  $post = $db->select('podcast', "id=? LIMIT 1", array($id));
  if ($post)
  {
    echo $post['title'];
  }
}
?>