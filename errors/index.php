<?php
/*
* Project Teknik - By Chris Woodward
* Integration of all my services under one roof.
* Maybe awesome?
*/

require_once('../includes/config.php');
$error_page = "404";
if (isset($_GET['error']))
{
  if (file_exists(safe($_GET['error']).'.php'))
  {
    $error_page = safe($_GET['error']);
  }
}
include('../templates/'.$CONF['template'].'/header.php');
set_page_title("Teknik - ".$error_page);
include($error_page.'.php');
include('../templates/'.$CONF['template'].'/footer.php');

?>