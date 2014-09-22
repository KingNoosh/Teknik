<?php
require_once('config.php');
 
//initialize php variables used in the form
$username = "";
 
//check to see that the form has been submitted
if(isset($_POST))
{
    $username = $_POST['username'];
 
    $userTools = new UserTools();
    if ($username == "")
    {
      echo "How about something we can read";
    }
    else if ($userTools->checkUsernameExists($username))
    {
      //successful login, redirect them to a page
      echo "That username is taken  =(";
    }
    else
    {
      echo "true";
    }
}
else
{
  echo "Error";
}
?>