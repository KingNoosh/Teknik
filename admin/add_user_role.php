<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  if ($logged_in)
  {
    $user_id = rawurldecode($_POST['id']);
    $role_name = rawurldecode($_POST['role']);
    $role = Role::getRole($db, $role_name);
    if ($role)
    {
      $userTools->insertUserRoles($user_id, array($role['role_id']));
    }
    else
    {
      echo "Invalid Role";
    }
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