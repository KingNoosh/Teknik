<?php
  $FounderUsers = $userTools->getUsersFromGroup("Founder");
  $AdminUsers = $userTools->getUsersFromGroup("Admin");
  $ModUsers = $userTools->getUsersFromGroup("Moderator");
            
  $history_events = $db->select('history', "1=? ORDER BY event_date DESC", array("1"));
  
  $history = array();
  foreach ($history_events as $history_event)
  {
    if (!is_array($history_event))
    {
      $history = array($history_events);
      break;
    }
    array_push($history, $history_event);
  }
?>

<div class="container">
  <div class="row">
    <div class="col-ms-12">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#about_us" data-toggle="tab">About Us</a></li>
        <?php if ($FounderUsers || $AdminUsers || $ModUsers) { ?>
        <li><a href="#staff" data-toggle="tab">Staff</a></li>
        <?php } ?>
        <li><a href="#history" data-toggle="tab">History</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="about_us">
          <h2 class="text-center">What we are About</h2>
            <hr>
            <p>
              Teknik was created to provide our users free services that they can trust.  All of our services are treated with the utmost care to provide you with the best experience possible, and the best security with your data that we can give.
            </p>
            <p>
              You can view our complete activity and statistics by visiting the <a href="<?php echo get_page_url("transparency", $CONF); ?>" target="_blank">Transparency</a> page.
            </p>
          <h2 class="text-center">What we Offer</h2>
            <hr>
            <div class="row">
              <div class="col-sm-4 col-sm-offset-2 text-center">
                <h4><a href="<?php echo get_page_url("paste", $CONF); ?>" target="_blank">Fast and Secure Pastebin</a></h4>
                <h4><a href="<?php echo get_page_url("upload", $CONF); ?>" target="_blank">Encrypted File Uploads</a></h4>
                <h4><a href="<?php echo get_page_url("mail", $CONF); ?>" target="_blank">Free Email Address</a></h4>
                <h4><a href="<?php echo get_page_url("api", $CONF); ?>" target="_blank">Easy to Use API</a></h4>
                <h4><a href="<?php echo get_page_url("help", $CONF); ?>#Git" target="_blank">Personal Git Repositories</a></h4>
              </div>              
              <div class="col-sm-4 text-center">
                <h4><a href="<?php echo get_page_url("blog", $CONF); if ($logged_in) { echo "/".$user->username; }?>" target="_blank">Personal Blog</a></h4>
                <h4><a href="<?php echo get_page_url("podcast", $CONF); ?>" target="_blank">Entertaining Podcasts</a></h4>
                <h4><a href="<?php echo get_page_url("help", $CONF); ?>#Mumble" target="_blank">Mumble Server</a></h4>
                <h4><a href="<?php echo get_page_url("transparency", $CONF); ?>" target="_blank">Full Transparency</a></h4>
                <h4><a href="<?php echo get_page_url("git", $CONF); ?>/Teknik.git/" target="_blank">Completely Open Source</a></h4>
              </div>
            </div>
              
          <h2 class="text-center">How can I help?</h2>
            <hr>
            <p>
              Teknik hosts an open <a href="<?php echo get_page_url("git", $CONF); ?>">Git Repository</a> for all our internal tools projects.  This is open to all registered users so feel free to add to it!
              <br />
              <br />
              Have a cool suggestion for the site?  Just submit it using the <a href="<?php echo get_page_url("contact", $CONF); ?>">Feedback Form</a>!
            </p>
            <div class="alert alert-info">
              <div class="text-center">
                <p>
                  While we provide these services for free, sadly that doesn't make the cost magically go away.  If you think we are doing a great job and would like to say thanks, we would greatly appreciate a small donation so that we can pay the bills!  (Or buy some beer)
                </p>
                <p>
                    <div class="input-group col-sm-6 col-sm-offset-3">
                        <span class="input-group-addon" id="basic-addon1">Bitcoin Address</span>
                        <input type="text" class="form-control" name="bitcoin_address" value="<?php echo $CONF['bitcoin_address']; ?>" readonly>
                    </div>
                    <br />
                    PayPal Address: <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MBG93VKQ343P4">admin@teknik.io</a>
                </p>
                </p>
              </div>
            </div>
        </div>
        <div class="tab-pane" id="staff">
          <?php
            if ($FounderUsers || $AdminUsers || $ModUsers)
            {
          ?>
          <?php
            if ($FounderUsers)
            {
          ?>
            <div class="row">
              <div class="col-md-12">
                <h2>Founders</h2>
              </div>
            </div>
            <div class="row">
            <?php
              foreach ($FounderUsers as $founderuser)
              {
            ?>
                <div class="col-md-6">
                    <div class="blockquote-box blockquote-danger clearfix">
                        <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $founderuser->username; ?>">
                          <div class="square pull-left">
                            <span class="glyphicon glyphicon-tower glyphicon-lg"></span>
                          </div>
                        </a>
                        <h4>
                            <?php echo $founderuser->username; ?></h4>
                        <p>
                            <?php echo $founderuser->quote; ?>
                        </p>
                    </div>
                  </div>
            <?php
              }
            ?>
            </div>
          <?php
            }
            
            if ($AdminUsers)
            {
          ?>
            <div class="row">
              <div class="col-md-12">
                <h2>Administrators</h2>
              </div>
            </div>
            <div class="row">
            <?php
              foreach ($AdminUsers as $adminuser)
              {
            ?>
                <div class="col-md-6">
                    <div class="blockquote-box blockquote-primary clearfix">
                        <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $adminuser->username; ?>">
                          <div class="square pull-left">
                            <span class="glyphicon glyphicon-star glyphicon-lg"></span>
                          </div>
                        </a>
                        <h4>
                            <?php echo $adminuser->username; ?></h4>
                        <p>
                            <?php echo $adminuser->quote; ?>
                        </p>
                    </div>
                  </div>
            <?php
              }
            ?>
            </div>
          <?php
            }
            
            if ($ModUsers)
            {
          ?>
            <div class="row">
              <div class="col-md-12">
                <h2>Moderators</h2>
              </div>
            </div>
            <div class="row">
            <?php
              foreach ($ModUsers as $moduser)
              {
            ?>
                <div class="col-md-6">
                    <div class="blockquote-box blockquote-success clearfix">
                        <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $moduser->username; ?>">
                          <div class="square pull-left">
                            <span class="glyphicon glyphicon-star-empty glyphicon-lg"></span>
                          </div>
                        </a>
                        <h4>
                            <?php echo $moduser->username; ?></h4>
                        <p>
                            <?php echo $moduser->quote; ?>
                        </p>
                    </div>
                  </div>
            <?php
              }
            ?>
            </div>
          <?php
            }
            ?>
          <?php
          }
          ?>
        </div>
        <?php
        if ($history)
        {
        ?>
        <div class="tab-pane" id="history">
          <div class="page-header text-center">
            <h1>The History of #/g/technology</h1>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  In early 2012 one of the current owners, dissatisfied with the /g/ channels that existed, sought to create a different one. One that was actually about technology. He posted on /g/ about his desire to create a new channel, and invited others to join him. He then invited his friends from former software projects and #/g/technology was born.
                  <br /><br />
                  The channel has grown a lot since then, and is now considered the de-facto /g/ channel and is by far the largest channel for 4chan's technology board.
                </div>
              </div>
            </div>
          </div>
          <div id="timeline">
            <?php
            $current_day = date("d",time())+1;
            $current_month = date("m",time())+1;
            $current_year = date("Y",time());
            $first_event = true;
            $position = "right";
            foreach ($history as $event)
            {
              $event_date = (isset($event['event_date'])) ? $event['event_date'] : "";
              $event_title = (isset($event['title'])) ? $event['title'] : "";
              $event_description = (isset($event['description'])) ? $event['description'] : "";
              
              $new_day_tag = false;
              $new_year_tag = false;
              if ($current_day != date("d",strtotime($event_date)) || $current_month != date("m",strtotime($event_date)))
              {
                $new_day_tag = true;
              }
              if ($current_year != date("Y",strtotime($event_date)))
              {
                $new_year_tag = true;
              }
              if ($position == "left")
              {
                $position = "right";
              }
              else
              {
                $position = "left";
              }
              $current_day = date("d",strtotime($event_date));
              $current_month = date("m",strtotime($event_date));
              $current_year = date("Y",strtotime($event_date));
            ?>
            <?php if (!$first_event && $new_day_tag) { ?>
              </div>
            <?php } ?>
            <?php if ($new_year_tag) { ?>
              <div class="row timeline-movement timeline-movement-top">
                <div class="timeline-badge">
                  <span class="timeline-balloon-date-year"><?php echo date("Y",strtotime($event_date)); ?></span>
                </div>
              </div>
            <?php } ?>
            <?php if ($new_day_tag) { ?>
              <div class="row timeline-movement">
            <?php } ?>
              <?php if ($new_day_tag) { ?>
                <div class="timeline-badge">
                  <span class="timeline-balloon-date-day"><?php echo date("d",strtotime($event_date)); ?></span>
                  <span class="timeline-balloon-date-month"><?php echo date("M",strtotime($event_date)); ?></span>
                </div>
              <?php } ?>
                <div class="col-sm-6 <?php if ($position == "right") { echo "col-sm-offset-6"; } ?> timeline-item">
                  <div class="row">
                    <div class="col-sm-11 <?php if ($position == "right") { echo "col-sm-offset-1"; } ?>">
                      <div class="timeline-panel <?php echo $position; ?>">
                        <ul class="timeline-panel-ul">
                          <li><span class="importo"><?php echo $event_title; ?></span></li>
                          <li><span class="causale"><?php echo $event_description; ?></span> </li>
                          <li><p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo date("Y-m-d H:i:s", strtotime($event_date)); ?></small></p> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
            <?php
              $first_event = false;
            }
            ?>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
