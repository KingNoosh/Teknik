<?php
/*
* Project Teknik - By Chris Woodward
* Integration of all my services under one roof.
* Maybe awesome?
*/

require_once('../includes/config.php');

include('../templates/'.$CONF['template'].'/header.php');
?>
<div class="container">
  <div class="row">
    <div class="col-md-10">
    <?php
    include('main.php');
    include('git.php');
    include('mail.php');
    include('irc.php');
    //include('../minecraft/help.php');
    include('mumble.php');
    include('api.php');
    ?>
    </div>     
    <div class="col-md-2"> 
      <div id="toc"> 
      </div>
    </div>
  </div>
</div>
<?php
include('../templates/'.$CONF['template'].'/footer.php');

set_page_title("Teknik Help");
?>