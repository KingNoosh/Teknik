<?php
require_once('config.php');
require_once('Git.php');
 
//initialize php variables used in the form
$current_password = "";
$password = "";
$password_confirm = "";
$theme = "";
$public_key = "";
$minecraft = "";
$website = "";
$profile_image = "";
$quote = "";
$about = "";
$blog_title = "";
$blog_description = "";
$error = "";
 
//check to see that the form has been submitted
if(isset($_POST))
{
    array_filter($_POST, 'trim_value');    // the data in $_POST is trimmed
    $postfilter =    // set up the filters to be used with the trimmed post array
      array(
              'website'                        =>    array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW),
              'quote'                            =>    array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW),
              'about'                            =>    array('filter' => FILTER_SANITIZE_STRING, 'flags' => !FILTER_FLAG_STRIP_LOW),
              'blog_title'                            =>    array('filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_LOW),
              'blog_desc'                            =>    array('filter' => FILTER_SANITIZE_STRING, 'flags' => !FILTER_FLAG_STRIP_LOW)
          );
          
    $revised_post_array = filter_var_array($_POST, $postfilter);    // must be referenced via a variable which is now an array that takes the place of $_POST[]

    //retrieve the $_POST variables
    $current_password = rawurldecode($_POST['current_password']);
    $password = rawurldecode($_POST['password']);
    $password_confirm = rawurldecode($_POST['password_confirm']);
    $theme = rawurldecode($_POST['theme']);
    $public_key = rawurldecode($_POST['public_key']);
    //$minecraft = rawurldecode($revised_post_array['minecraft']);
    $website = rawurldecode($revised_post_array['website']);
    $quote = rawurldecode($revised_post_array['quote']);
    $about = rawurldecode($revised_post_array['about']);
    $blog_title = rawurldecode($revised_post_array['blog_title']);
    $blog_description = rawurldecode($revised_post_array['blog_desc']);
    
    //initialize variables for form validation
    $success = true;
    
    if($success && !$logged_in)
    {
      $error = "You must be logged in to update your profile.";
      $success = false;
    }
    
    if($success && strlen($quote) > 140)
    {
      $error = "The maximum length for your quote is 140 characters.";
      $success = false;
    }
    
    if($success && strlen($blog_title) > 50)
    {
      $error = "The maximum length for your blog title is 50 characters.";
      $success = false;
    }
    
    if($success && strlen($blog_description) > 140)
    {
      $error = "The maximum length for your blog description is 140 characters.";
      $success = false;
    }
    
    $change_password = false;
    if($success && $current_password)
    {
      //check to see if passwords match
      if($success && hashPassword($current_password, $CONF) != $user->hashedPassword)
      {
          $error = "Current Password does not match.";
          $success = false;
      }
      
      //check to see if passwords match
      if($success && !$password)
      {
          $error = "You need to specify a new password.";
          $success = false;
      }
      
      //check to see if passwords match
      if($success && $password != $password_confirm)
      {
          $error = "New Passwords do not match.";
          $success = false;
      }
      
      if($success)
      {
        $change_password = true;
      }
    }

    if(!array_key_exists($theme, $CONF['themes']))
    {
      $error = "Invalid Theme Choice.";
      $success = false;
    }
    
    $keys = explode(",", $public_key);
    foreach ($keys as $key)
    {
      $pattern = "/^(ssh-rsa)\s([0-9A-Za-z\/\+]+)([=]*)((\s.*)|())$/";
      if($success && $key && !preg_match($pattern, $key))
      {
        $error = "Invalid Public Key.<br />Please make sure it follows this format.<br /><b>ssh-rsa [0-9A-Za-z/+ ]</b>";
        $success = false;
        break;
      }
    }
 
    if($success)
    {
        //prep the data for saving in a new user object
        if ($change_password)
        {
          $user->hashedPassword = hashPassword($password, $CONF); //encrypt the password for storage
        }
        
        // Add the user's keys to his git account
        if ($public_key != $user->public_key)
        {
          $dir = $CONF['git_key_dir'].'u\\'.$user->username;
          if (is_dir($dir))
          {
            foreach (glob($dir."\\*") as $filename)
            {
              if (is_file($filename))
              {
                unlink($filename);
              }
            }
          }
          
          if (!is_dir($dir))
          {
            mkdir($dir, 0777, true);
          }
          $index = 0;
          $keys = explode(",", $public_key);
          foreach ($keys as $key)
          {
            preg_match($pattern, $key, $matches);
            if (trim($matches[2]) != "")
            {
              $key = "ssh-rsa " . $matches[2];
              
              $keyFileName = $dir."\\".$user->username."@Key".$index.".pub";
              $fileHandle = fopen($keyFileName, 'w');
              fwrite($fileHandle, $key);
              fclose($fileHandle);
              $index++;
            }
          }
          putenv("HOME=/home/git");
          $result = shell_exec('bash --login -c "'.$CONF['gitolite_path'].'gitolite trigger SSH_AUTHKEYS"');
        }
        
        /*
        if ($minecraft != $user->minecraft_user)
        {
          // code to add/remove user from permissionsex
          // Connect to the server
          $r = new minecraftRcon($CONF['minecraft_server'], $CONF['rcon_port'], $CONF['rcon_pass']);

          // Authenticate, and if so, execute command(s)
          if ( $r->Auth() ) {
            $r->mcRconCommand('pex user '.$user->minecraft_user." group remove Member");
            $r->mcRconCommand('pex user '.$minecraft." group add Member");
          }
        }
        */
        $user->theme = $theme;
        $user->public_key = $public_key;
        //$user->minecraft_user = $minecraft;
        $user->website = $website;
        $user->quote = $quote;
        $user->about = $about;
        $user->blog_title = $blog_title;
        $user->blog_desc = $blog_description;
        
        //update the user in the database
        $user->save($db);
        
        unset($_POST);
        echo "true";
    }
    else
    {
      unset($_POST);
      echo $error;
    }
}
else
{
  echo "$_POST is not set.";
}
?>