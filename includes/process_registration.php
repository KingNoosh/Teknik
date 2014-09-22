<?php
require_once('config.php');
 
//initialize php variables used in the form
$username = "";
$password = "";
$password_confirm = "";
$error = "";
 
//check to see that the form has been submitted
if(isset($_POST))
{
    //retrieve the $_POST variables
    $username = rawurldecode($_POST['username']);
    $password = rawurldecode($_POST['password']);
    $password_confirm = rawurldecode($_POST['password_confirm']);
 
    $obBaseApp = new COM("hMailServer.Application");
    $obBaseApp->Connect();
    $obBaseApp->Authenticate($CONF['mail_admin_user'], $CONF['mail_admin_pass']);
    $domain = $obBaseApp->Domains->ItemByName($CONF['host']);
    
    //initialize variables for form validation
    $success = true;
    
    if($success && safe_register($username) != $username)
    {
        $error = "Invalid Characters in username.";
        $success = false;
    }
    
    $username = safe_register($username);
    
    if($success && !$username)
    {
        $error = "You must input a Username.";
        $success = false;
    }
    
    //validate that the form was filled out correctly
    //check to see if user name already exists
    if($success && $userTools->checkUsernameExists($username))
    {
        $error = "That username is already taken.";
        $success = false;
    }
 
    if($success && $userTools->checkEmailExists($domain, $username . "@" . $CONF['host']))
    {
        $error = "The email for that username is already taken.";
        $success = false;
    }
    
    if($success && !$password)
    {
        $error = "You must input a Password.";
        $success = false;
    }
 
    //check to see if passwords match
    if($success && $password != $password_confirm)
    {
        $error = "Passwords do not match.";
        $success = false;
    }
 
    if($success)
    {
        $email = $username . "@" . $CONF['host'];

        //prep the data for saving in a new user object
        $data['username'] = $username;
        $data['password'] = hashPassword($password, $CONF); //encrypt the password for storage
        
        //create the new user object
        $newUser = new User($data);
 
        //save the new user to the database
        $newUser->save($db, true);
        
        //Create an email for the user
        $account = $domain->Accounts->Add();
        $account->Address = $email;
        $account->Password = $password;
        $account->Active = True;
        $account->MaxSize = 1000;
        
        $account->Save();

        //log them in
        $userTools->login($username, hashPassword($password, $CONF), false);
 
        //redirect them to a welcome page
        echo "true";
    }
    else
    {
      echo $error;
    }
}
else
{
  echo "$_POST is not set.";
}
?>