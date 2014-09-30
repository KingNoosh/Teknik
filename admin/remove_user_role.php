<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  if ($logged_in)
  {
    $user_id = rawurldecode($_POST['id']);
    $role_id = rawurldecode($_POST['role']);
    $userTools->deleteUserRoles($user_id, array($role_id));
  }
  else
  {
    echo "You need to be logged in";
  }
}
else
{
  echo "POST not set";
}
?>