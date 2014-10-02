<?php
/*
* Project Teknik - By Chris Woodward
* Integration of all my services under one roof.
* Maybe awesome?
*/

require_once('../includes/config.php');

include('../templates/'.$CONF['template'].'/header.php');
include('../help/api.php');
include('../templates/'.$CONF['template'].'/footer.php');

set_page_title("Teknik API");
?>