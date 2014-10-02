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
    include('../help/api.php');
    ?>
    </div>
  </div>
</div>
<?php
include('../templates/'.$CONF['template'].'/footer.php');

set_page_title("Teknik API");
?>