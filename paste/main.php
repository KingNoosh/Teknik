<?php
	if (isset($page['post']['codecss'])) { 
		echo '<style type="text/css">'."\n";
		echo $page['post']['codecss'];
		echo '</style>'."\n";}
    
	?>
  
<?php
// Show errors
if (count($pastebin->errors)) { 
	echo '<div class="alert alert-error">';
	foreach($pastebin->errors as $err) { echo '<i class="icon-exclamation-sign"></i> ' . $err . ' </div>'; }
$page['post']['editcode']=$_POST['code'];
$page['current_format']=$_POST['format'];
$page['expiry']=$_POST['expiry'];
	if ($_POST['password'] != 'EMPTY') { $page['post']['password']=$_POST['password']; }
$page['title']="";
if(isset($_POST['title'])){ $page['title']=$_POST['title']; }

}

// Show a paste
function showMe() {
	global $sep;
	global $page;
	global $post;
	global $followups;
	global $CONF;
	?>
<div class="container">
  <?php
	if (strlen($page['post']['posttitle'])) { echo '<div class="alert alert-info">' . $page['post']['posttitle'] . ' - Format: ' . ($page['post']['format']) . '';
	
		if ($page['post']['parent_pid']>0) {
			echo ' - This is a modified post titled "<a href="' . $page['post']['parent_url'] . '" title="View original post">' . $page['post']['parent_title'] . '</a>".';
		}

		$followups=count($page['post']['followups']);
		if ($followups) { 
			echo ' - See newer version(s) of this paste titled ';
			$sep="";
			foreach($page['post']['followups'] as $idx=>$followup) {
				echo $sep . '<a title="Posted on ' . $followup['postfmt'] . '" href="' . $followup['followup_url'] . '">"' . $followup['title'] . '"</a>';
				$sep=($idx<($followups-2))?", ":" and ";
				}
			}
?>
              </div>
              
            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills">
                  <li><a href="<?php echo $page['post']['downloadurl'] ?>"><span class="glyphicon glyphicon-download-alt"></span> Download</a></li>
                  <li><a href="javascript:togglev();" title="Show/Hide line numbers"><span class="glyphicon glyphicon-list"></span> Hide Lines</a></li>
                </ul>
              </div>
            </div>
  <?php } // End post title ?>

  <div class="row">
    <div class="col-md-12">
    <?php if (isset($page['post']['pid'])) { ?>
      <div class="well" style="background-color: #FFF;" id="code">
      <?php echo $page['post']['codefmt'];?>
      </div>
    </div>
  </div>
</div>
<?php }
} // End showing of a paste

// Check for a password
$postPass = null;
if(isset($_POST['password'])){ $postPass = $_POST['password']; }



if (isset($pid) && $pid >0) {
	global $pid;
    $result = $pastebin->getPaste($pid);
    $pass = $result['password'];

if (isset($pass) && ($pass != "EMPTY")) { if (!isset($postPass)) { ?>

<div class="container">
  <div class="row text-center">
    <div class="col-sm-6 col-sm-offset-3">
      <form class="form-inline" method="post" action="">
        <h3><span class="glyphicon glyphicon-warning-sign"></span> This paste is password protected.</h3>
        <div class="well no-padding">	
            <div class="form-group">
              <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <button class="btn btn-primary" type="submit">Show</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php } else if (sha1($postPass) == $pass) { showMe(); } else { ?>

<div class="container">
  <div class="row text-center">
    <div class="col-sm-6 col-sm-offset-3">
    <div class="alert alert-danger">
      <span class="glyphicon glyphicon-warning-sign"></span> The password you entered was incorrect, <a href="#tryagain" onClick="history.go(-1); return false;">Try again.</a></i>
    </div>
    </div>
  </div>
</div>

<?php }
	} else { showMe(); }
}; // End password page

if (!(isset($pass) && (sha1($postPass) !== $pass)) || $pass == "EMPTY") {?>
<!-- Paste area -->

<div class="container">
  <div class="row">
    <div class="col-md-8">
    <form class="form-horizontal" name="editor" method="post" action="index.php">
    <input type="hidden" name="parent_pid" value="<?php if(isset($page['post']['pid'])){echo $page['post']['pid'];} ?>"/>
      <div class="top-bar"><h3><i class="icon-edit"></i> New Paste</h3></div>
      <div class="well">
        <div class="form-group">
          <div class="col-sm-12">
            <select id="id_select" name="format" class="selectpicker" data-live-search="true">		
             <optgroup label="Popular Formats">
              <?php // Show popular GeSHi formats
                foreach ($CONF['geshiformats'] as $code=>$name)
                {
                  if (in_array($code, $CONF['popular_formats']))
                  {
                    $sel=($code==$page['current_format'])?'selected="selected"':' ';
                    echo '<option ' . $sel . 'value="' . $code . '">' . $name . '</option>';
                  }
                }

                echo '</optgroup><optgroup label="All Formats">';

                // Show all GeSHi formats.
                foreach ($CONF['geshiformats'] as $code=>$name)
                {
                    $sel=($code==$page['current_format'])?'selected="selected"':' ';
                  if (in_array($code, $CONF['popular_formats']))
                    $sel="";
                    echo '<option ' . $sel . 'value="' . $code . '">' . $name . '</option>';
                }
              ?>
              </optgroup>
            </select>
          </div>
        </div>
        <div class="form-group">  
          <div class="col-sm-12">
            <textarea class="form-control" rows="15" id="code" name="code" onkeydown="return catchTab(this,event)"><?php if(isset($page['post']['editcode'])){
                              echo htmlspecialchars($page['post']['editcode']);
                          } ?></textarea>
          </div>
        </div>
      </div>

    <!-- Options -->
    <div class="top-bar"><h3><i class="icon-gear"></i> Paste Options</h3></div>
    <div class="well no-padding">
      <div class="form-group">
        <label for="title" class="col-sm-3 control-label">Paste Title</label>
        <div class="col-sm-9">
          <input class="form-control" type="text" maxlength="30" id="title" name="title" value="<?php 
                    $page['title']="";
                    if(isset($_POST['title'])){ $page['title']=$_POST['title']; }
                    echo $page['title'] ?>">    
        </div>
      </div>

      <div class="form-group">
        <label for="password" class="col-sm-3 control-label">Password</label>
        <div class="col-sm-9">
          <input class="form-control" type="password" id="password" value="<?php if (strcmp($postPass,'EMPTY') != 0) { echo $postPass; } else { echo ''; } ?>" name="password">    
        </div>
      </div>
      
      <div class="form-group">
        <label for="expire_select" class="col-sm-3 control-label">Paste Expiration</label>
        <div class="col-sm-9">
            <select class="selectpicker" id="expire_select" name="expiry" tabindex="1">
              <option id="expiry_forever" value="f" <?php if ($page['expiry']=='f') echo 'selected="selected"'; ?>>None</option>
              <option id="expiry_day" value="d" <?php if ($page['expiry']=='d') echo 'selected="selected"'; ?>>One Day</option>
              <option id="expiry_month" value="m" <?php if ($page['expiry']=='m') echo 'selected="selected"'; ?>>One Month</option>
            </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
          <button class="btn btn-primary" type="submit" name="paste">Submit</button>
        </div>
      </div>
    </form>   
    </div>
  </div>
    <!-- Recent Pastes -->
    <div class="col-md-4">
      <div class="top-bar"><h3><i class="icon-pencil"></i> Recent Pastes</h3></div>
        <div class="well no-padding" id="pagination-activity">
          <div class="list-widget pagination-content">
          <?php foreach($page['recent'] as $idx=>$entry) {
            if (isset($pid) && $entry['pid']==$pid) $cls="background-color: #e0e0e0;";
            else $cls="";?>
          <div class="item" style="display: block; <?php echo $cls;?>">
            <small class="pull-right"><?php echo $entry['agefmt'];?></small>
            <p class="no-margin"><i class="icon-code"></i>
            <?php if ( $mod_rewrite == true ) { 
            echo '<a href="'. get_subdomain_full_url('p', $CONF) . '/' . $entry['pid'] . '">' . $entry['title'] . '</a>'; } else { 
            echo '<a href="'. get_subdomain_full_url('p', $CONF) . '/' .'?paste='. $entry['pid'].'">' . $entry['title'] . '</a>'; } ?>
            </p>
          </div>
        <?php } ?>
        </div>
      </div>
    </div>
  </div> 
</div>
<?php } ?>

<p class="text-center">
  Tools: <a href="<?php echo get_subdomain_full_url('git', $CONF); ?>/Tools.git/blob/master/Paste/paste.sh">Bash Paste Script</a>
</p>