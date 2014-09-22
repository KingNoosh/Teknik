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
  
  $file = $CONF['ssh_pub_keys'];

  //delete any public keys from git auth
  $fh = fopen($file, 'r+');
  if ($fh)
  {
    if (filesize($file) > 0)
    {
      $data = fread($fh, filesize($file));
      $reg_key = preg_quote($user->public_key, '/');
      $reg_user = preg_quote($user->username, '/');
      $forced_commands = preg_quote($CONF['forced_commands'], '/');
      $new_data = preg_replace("/($forced_commands)(\s)($reg_key)(\s)($reg_user)(\n)*/", "", $data);
      fclose($fh);
      
      $fh = fopen($file, 'w');
      fwrite($fh, $new_data);
    }
  }
  fclose($fh);
  
  $r = new minecraftRcon($CONF['minecraft_server'], $CONF['rcon_port'], $CONF['rcon_pass']);

  // Authenticate, and if so, execute command(s)
  if ( $r->Auth() ) {
    $r->mcRconCommand('pex user '.$user->minecraft_user." group remove Member");
  }
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