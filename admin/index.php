<?php
/*
* Project Teknik - By Chris Woodward
* Integration of all my services under one roof.
* Maybe awesome?
*/

require_once('../includes/config.php');

if ($logged_in)
{
  if ($user->group == "Founder" || $user->group == "Admin" || $user->group == "Moderator")
  {
    include('../templates/'.$CONF['template'].'/header.php');
    include('main.php');
    include('../templates/'.$CONF['template'].'/footer.php');

    set_page_title("Teknik Administration");
  }
  else
  {
    header('Location: '.get_subdomain_full_url('error', $CONF).'/403');
  }
}
else
{
  header('Location: '.get_subdomain_full_url('error', $CONF).'/403');
}
?>