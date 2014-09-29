<?php

require_once('../includes/config.php');

include('../templates/'.$CONF['template'].'/header.php');

if (isset($_GET['id']))
{
  if ($userTools->checkUsernameExists($_GET['id']))
  {
    $own_profile = false;
    $Profile_User = $userTools->getUser($_GET['id']);
    if ($Profile_User->id == $user->id && $logged_in == true)
    {
      $own_profile = true;
    }
?>
<div class="container">
    <div class="row">
  		<div class="col-sm-3<?php if(!$Profile_User->about && !$own_profile) { echo " col-sm-offset-4"; } ?>"><h1><?php echo $Profile_User->username; ?></h1></div>
    </div>
    <div class="row">
  		<div class="col-sm-3<?php if(!$Profile_User->about && !$own_profile) { echo " col-sm-offset-4"; } ?>"><!--left col-->
              
          <ul class="list-group">
            <li class="list-group-item text-muted">Profile</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span> <?php echo $Profile_User->join_date; ?></li>
            <?php if ($own_profile) { ?>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Last Seen</strong></span> <?php echo $Profile_User->last_seen; ?></li>
            <?php } ?>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Email</strong></span> <a href="mailto:<?php echo $Profile_User->username; ?>@teknik.io"><?php echo $Profile_User->username; ?>@teknik.io</a></li>
            <?php if ($Profile_User->blog_title) { ?>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Blog</strong></span> <a href="<?php echo get_page_url('blog', $CONF).'/'.$Profile_User->username; ?>" id="blog_title"><?php echo $Profile_User->blog_title; ?></a></li>
            <?php } ?>
            <?php if ($own_profile) { ?>
            <li class="list-group-item text-center"><button type="button" class="btn btn-danger" id="delete_account">Delete Account</button></li>
            <?php } ?>
          </ul>
          <?php if($Profile_User->website) { ?>
          <div class="panel panel-default">
            <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
            <div class="panel-body"><a href="<?php echo $Profile_User->website; ?>" id="website"><?php echo $Profile_User->website; ?></a></div>
          </div>
          <?php } ?>
          <?php if($Profile_User->quote) { ?>
          <div class="panel panel-default">
            <div class="panel-heading">Quote <i class="fa fa-quote-right fa-1x"></i></div>
            <div class="panel-body" id="quote"><?php echo $Profile_User->quote; ?></div>
          </div>
          <?php } ?>
        </div><!--/col-3-->
    	<div class="col-sm-9">
        <?php if($Profile_User->about && $own_profile) { ?>
          <ul class="nav nav-tabs" id="myTab">
            <?php if($Profile_User->about) { ?>
            <li <?php if(!$own_profile) { echo 'class="active"'; }?>><a href="#about" data-toggle="tab">About Myself</a></li>
            <?php } ?>
            <?php if($own_profile) { ?>
            <li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>
            <li><a href="#privacy" data-toggle="tab">Privacy</a></li>
            <?php } ?>
          </ul>
        <?php } ?>
              
          <div class="tab-content">
          <?php if($Profile_User->about) { ?>
            <div class="tab-pane <?php if(!$own_profile) { echo "active"; }?>" id="about">
              <script>
                $(document).ready(function() {
                  var converter = new Markdown.getSanitizingConverter();
                  
                <?php if($Profile_User->blog_title) { ?>
                  // Blog Title Conversion
                  var old_html = $("#blog_title").text();
                  var new_html = converter.makeHtml(old_html);
                  $("#blog_title").html(new_html);
                <?php } ?>
                  
                <?php if($Profile_User->website) { ?>
                  // Website Conversion
                  var old_html = $("#website").text();
                  var new_html = converter.makeHtml(old_html);
                  $("#website").html(new_html);
                <?php } ?>
                  
                <?php if($Profile_User->quote) { ?>
                  // Quote Conversion
                  var old_html = $("#quote").text();
                  var new_html = converter.makeHtml(old_html);
                  $("#quote").html(new_html);
                <?php } ?>
                  
                <?php if($Profile_User->about) { ?>
                  // About Conversion
                  var old_about = $("#markdown_body").text();
                  var new_about = converter.makeHtml(old_about);
                  $("#markdown_body").html(new_about);
                <?php } ?>
                });
              </script>
              <div class="col-sm-12" id="markdown_body"><?php echo $Profile_User->about; ?></div>
             </div><!--/tab-pane-->
           <?php } ?>
            <?php if($own_profile) { ?>
             <div class="tab-pane active" id="settings">
                  <form class="form" action="##" method="post" id="updateForm">
                    <input name="update_userid" id="update_userid" type="hidden" value="<?php echo $Profile_User->id; ?>" />
                    <!-- Profile Settings -->
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <h3>Profile Settings</h3>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-sm-4">
                          <label for="update_password_current"><h4>Current Password</h4></label>
                          <input class="form-control" name="update_password_current" id="update_password_current" placeholder="current password" title="enter your current password." type="password" />
                      </div>
                      <div class="form-group col-sm-4">
                          <label for="update_password"><h4>New Password</h4></label>
                          <input class="form-control" name="update_password" id="update_password" placeholder="new password" title="enter your password." type="password" />
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="update_password_confirm"><h4>Verify New Password</h4></label>
                          <input class="form-control" name="update_password_confirm" id="update_password_confirm" placeholder="new password confirmed" title="enter your password again." type="password" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-4">
                          <label for="update_theme"><h4>Site Theme</h4></label>
                          <br />
                          <select id="update_theme" name="update_theme" class="selectpicker">
                            <?php
                              // Show all themes.
                              foreach ($CONF['themes'] as $theme=>$name)
                              {
                                  $sel=($theme==$user->theme)?'selected="selected"':' ';
                                  if (in_array($theme, $CONF['themes']))
                                  {
                                    $sel="";
                                  }
                                  echo '<option ' . $sel . 'value="' . $theme . '">' . $name . '</option>';
                              }
                            ?>
                          </select>
                      </div>
                      <div class="form-group col-sm-4">
                          <label for="update_website"><h4>Website</h4></label>
                          <input class="form-control" id="update_website" name="update_website" placeholder="http://www.noneofyourbusiness.com/" title="enter your website" type="text" value="<?php echo $Profile_User->website; ?>" />
                      </div>
                      <div class="form-group col-sm-4">
                        <label for="update_quote"><h4>Quote</h4></label>
                          <input class="form-control" id="update_quote" name="update_quote" placeholder="I have a dream!" title="enter a memorable quote" type="text" value="<?php echo $Profile_User->quote; ?>" maxlength="140" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-sm-12">
                        <label for="update_about"><h4>About Yourself</h4></label>
                        <textarea class="form-control" name="update_about" id="update_about" placeholder="I'm awesome" title="enter any information you want to share with the world." data-provide="markdown" rows="10"><?php echo $Profile_User->about; ?></textarea>
                      </div>
                    </div>
                    <!-- Minecraft Settings
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <h3>Minecraft Settings</h3>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-sm-6">
                          <label for="update_minecraft"><h4>Username</h4></label>
                          <input class="form-control" id="update_minecraft" name="update_minecraft" placeholder="super_miner64" title="enter your minecraft username" type="text" value="<?php echo $Profile_User->minecraft_user; ?>" />
                      </div>
                    </div>
                    -->
                    <!-- Git Settings -->
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <h3>Git Settings</h3>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-sm-12">
                          <h4>Public Key(s)</h4>
                          <input id="update_public_key" name="update_public_key" type="hidden" value="<?php echo $Profile_User->public_key; ?>" />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12" id="public_key_list">
                      <?php
                        $keyList = array_filter(explode(",", $Profile_User->public_key));
                        $index = 1;
                        foreach ($keyList as $key)
                        {
                          ?>
                            <div class="public_key_<?php echo $index; ?>"><div class="input-group"><input type="text" class="form-control" id="public_key_input_<?php echo $index; ?>" value="<?php echo $key; ?>" readonly><span class="input-group-btn"><button class="btn btn-danger public_key_delete" type="button" id="<?php echo $index; ?>">Remove</button></span></div><br /></div>
                          <?php
                          $index++;
                        }
                      ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <button class="btn btn-md btn-primary" id="add_public_key"><i class="glyphicon glyphicon-plus"></i> Add Key</button>
                      </div>
                    </div>
                    <!-- Blog Settings -->
                    <div class="row">
                      <div class="col-sm-12 text-center">
                        <h3>Blog Settings</h3>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="form-group col-sm-6">
                          <label for="update_blog_title"><h4>Title</h4></label>
                          <input class="form-control" id="update_blog_title" name="update_blog_title" placeholder="click bait" title="enter your blog's title" type="text" value="<?php echo $Profile_User->blog_title; ?>" />
                      </div>
                      <div class="form-group col-sm-6">
                          <label for="update_blog_description"><h4>Description</h4></label>
                          <input class="form-control" id="update_blog_description" name="update_blog_description" placeholder="This blog is not worth reading." title="enter your blog's description" type="text" value="<?php echo $Profile_User->blog_desc; ?>" />
                      </div>
                    </div>
                    <div class="row">
                       <div class="form-group col-sm-12">
                            <br />
                            <button class="btn btn-lg btn-success" id="update_submit" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                            <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                      </div>
                    </div>
              	</form>
              </div><!--/tab-pane-->              
            <div class="tab-pane" id="privacy">
              <div class="row">
                <div class="col-sm-12 text-center">
                  <iframe style="border: 0; width: 100%;" src="https://stats.teknik.io/index.php?module=CoreAdminHome&action=optOut&language=en"></iframe>
                </div>
              </div>
            </div>
               <?php } ?>
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
  </div>
<?php
    set_page_title($Profile_User->username . " - Teknik");
  }
  else
  {
    set_page_title("Teknik");
  ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-12 text-center">
          <h2>Sorry, but I couldn't find that user.</h2>
        </div>
      </div>
    </div>
  <?php
  }
}
else
{
  redirect(get_page_url("home", $CONF));
}
include('../templates/'.$CONF['template'].'/footer.php');
?>