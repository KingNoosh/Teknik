<?php
require_once('config.php');
 
//initialize php variables used in the form
$username = "";
$password = "";
$error = "";
$remember = false;
 
//check to see that the form has been submitted
if(isset($_POST))
{
    $username = rawurldecode($_POST['username']);
    $password = rawurldecode($_POST['password']);
    $remember_me = rawurldecode($_POST['remember_me']);
    if ($remember_me == "true")
    {
      $remember = true;
    }
    if ($userTools->login($username, hashPassword($password, $CONF), $remember, $CONF))
    {
      $user = unserialize($_SESSION[$CONF['session_prefix'].'user']);
      $user->save($db);
      //successful login, redirect them to a page
      echo "true";
    }
    else
    {
      echo "false";
    }
}
else
{
  echo "false";
}
?>