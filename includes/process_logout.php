<?php
require_once('config.php');
 
//check to see that the form has been submitted
if(isset($_POST))
{
  $userTools->logout();
  echo "true";
}
else
{
  echo "false";
}
?>