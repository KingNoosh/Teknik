<?php
require_once('../includes/config.php');
 
//check to see that the form has been submitted
$id = 0;
if(isset($_GET))
{
  $file = rawurldecode($_GET['file']);
  $hash = rawurldecode($_GET['hash']);
  $upload = $db->select('uploads', "filename=? LIMIT 1", array($file));
  if ($upload)
  {
    $success = true;
    $key = $upload['delete_key'];
    if($success && $key != $hash)
    {
      $success = false;
    }
    
    if ($success)
    {
      $db->delete('uploads', 'id=?', array($upload['id']));
      include('../templates/'.$CONF['template'].'/header.php');
      ?>
      <div class="container">
        <div class="row">
          <div class="col-sm-12 text-center">
            <h2><b><?php echo $upload['filename']; ?></b> has been successfully deleted.</h2>
          </div>
        </div>
      </div>
      <?php
      include('../templates/'.$CONF['template'].'/footer.php');
      set_page_title("Upload Deleted");
    }
    else
    {
      header('Location: '.get_subdomain_full_url("error", $CONF).'/403');
    }
  }
  else
  {
    header('Location: '.get_subdomain_full_url("error", $CONF).'/404');
  }
}
else
{
  header('Location: '.get_subdomain_full_url("error", $CONF).'/404');
}
?>