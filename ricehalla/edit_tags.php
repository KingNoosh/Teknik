<?php
include('../includes/config.php');
 
$filename = "";
if(isset($_POST) && $logged_in)
{
  $id = rawurldecode($_POST['id']);
  $tags = rawurldecode($_POST['tags']);
  $user_id = $user->id;
  $desktop = $db->select('ricehalla', 'id=? AND user_id=?', array($id, $user_id));
  if ($desktop)
  {
    $data = array(
              "tags" => $tags
            );
    $db->update($data, 'ricehalla', 'id=?', array($id));
  }
  else
  {
    echo "You can only edit your image.";
  }
}
?>