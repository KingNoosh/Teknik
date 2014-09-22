<?php
require_once('config.php');

if (isset($_POST))
{
  $success = true;
  $error = "";
  $name = "";
  $email = "";
  $subject = "";
  $message = "";
  
  $subject = $_POST['subject'];
  $message = $_POST['message'];
  
  if (isset($_POST['name']))
  {
    $name = $_POST['name'];
  }
  elseif ($logged_in == 1)
  {
    $name = $user->username;
  }
  else
  {
    $success = false;
    $error = "You are not currently logged in.";
  }
  
  if (isset($_POST['email']))
  {
    $email = $_POST['email'];
  }
  elseif ($logged_in == 1)
  {
    $email = $user->username . "@" . $CONF['host'];
  }
  else
  {
    $success = false;
    $error = "You are not currently logged in.";
  }
  
  if ($success && empty($name))
  {
    $success = false;
    $error = "You need to specify a name.";
  }
  
  if ($success && empty($email))
  {
    $success = false;
    $error = "You need to specify a email to respond to.";
  }
  
  if ($success && $subject == "na")
  {
    $success = false;
    $error = "Please choose a subject.";
  }
  
  if ($success && empty($message))
  {
    $success = false;
    $error = "Please supply in a brief message.";
  }
  
  if ($success)
  {
    $data = array(
        "name" => $name,
        "email" => $email,
        "subject" => $subject,
        "message" => $message,
        "date_added" => date("Y-m-d H:i:s",time())
    );
    
    $db->insert($data, 'support');
    echo "true";
  }
  else
  {
    echo $error;
  }
}
else
{
  echo "Unable to process the request.";
}
?>