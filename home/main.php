<?php
$irc_info = $db->select('irc', "1=? ORDER BY id DESC LIMIT 1", array("1"));
$max_count = $irc_info['max_nicks'];
$count = $irc_info['cur_nicks'];
$topic = $irc_info['topic'];
?>
<div class="container">
  <div class="jumbotron text-center">
    <p>
      Teknik is the website for the #/g/technology IRC channel on Rizon.
      <br />
      We host various channels services for our IRC community and by extension, 4chan's Technology board.
    </p>
  </div>
</div>
<div class="container"> 
  <div class="row">
    <div class="col-sm-12 text-center">
      <h1><strong>Services</strong></h1>
    </div>
  </div>
  <hr>
  <br />
  <div class="row">
    <a href="<?php echo get_page_url("help", $CONF); ?>/#Mail">
      <div class="col-sm-4 col-md-3 text-center">
        <div class="thumbnail">
          <i class="fa fa-at fa-5x"></i>
          <div class="caption">
            <h3>Free Email</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("help", $CONF); ?>/#Git">
      <div class="col-sm-4 col-md-3 text-center">
        <div class="thumbnail">
          <i class="fa fa-git fa-5x"></i>
          <div class="caption">
            <h3>Unlimited Git Repositories</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("blog", $CONF); ?>">
      <div class="col-sm-4 col-md-3 text-center">
        <div class="thumbnail">
          <i class="fa fa-rss fa-5x"></i>
          <div class="caption">
            <h3>Personal Blog</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("upload", $CONF); ?>">
      <div class="col-sm-4 col-md-3 text-center">
        <div class="thumbnail">
          <i class="fa fa-lock fa-5x"></i>
          <div class="caption">
            <h3>Encrypted File Uploads</h3>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>