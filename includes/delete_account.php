<?php
require_once('config.php');
 
//check to see that the form has been submitted
if(isset($_POST))
{
  //delete user from mail-server
  $obBaseApp = new COM("hMailServer.Application");
  $obBaseApp->Connect();
  $obBaseApp->Authenticate($CONF['mail_admin_user'], $CONF['mail_admin_pass']);
  $domain = $obBaseApp->Domains->ItemByName($CONF['host']);
  $email = $user->username . "@" . $CONF['host'];
  $account = $domain->Accounts->ItemByAddress($email);
  $account->Delete();

  //delete any public keys from git auth
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
  putenv("HOME=/home/git");
  $result = shell_exec('bash --login -c "/home/git/gitolite/src/gitolite trigger SSH_AUTHKEYS"');
  /*
  $r = new minecraftRcon($CONF['minecraft_server'], $CONF['rcon_port'], $CONF['rcon_pass']);

  // Authenticate, and if so, execute command(s)
  if ( $r->Auth() ) {
    $r->mcRconCommand('pex user '.$user->minecraft_user." group remove Member");
  }
  */
  //delete the user from the main database
  $user->delete($db);
  
  //log the user out
  $userTools->logout();
  echo "true";
}
else
{
  echo "false";
}
?>