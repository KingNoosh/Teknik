<?php
$irc_info = $db->select('irc', "1=? ORDER BY id DESC LIMIT 1", array("1"));
$max_count = $irc_info['max_nicks'];
$count = $irc_info['cur_nicks'];
$topic = $irc_info['topic'];
?>
<div class="container">
  <div class="row">
    <center>
      <img src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/img/logo-text.png" class="img-responsive" alt="Teknik">
    </center>
  </div>
  <br />
  <div class="row text-center">
    <h2>
      Teknik is the website for the #/g/technology IRC channel on Rizon.
      <br />
      We host various channels services for our IRC community and by extension, 4chan's Technology board.
    </h2>
  </div>
</div>
<div class="container"> 
  <div class="row">
    <div class="col-sm-12 text-center">
      <h1><strong>Services We Offer</strong></h1>
    </div>
  </div>
  <hr>
  <br />
  <div class="row">
    <a href="<?php echo get_page_url("help", $CONF); ?>/#Mail">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-at fa-5x"></i>
          <div class="caption">
            <h3>Free Email</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("help", $CONF); ?>/#Git">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-git fa-5x"></i>
          <div class="caption">
            <h3>Unlimited Git Repositories</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("blog", $CONF); ?>">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-rss fa-5x"></i>
          <div class="caption">
            <h3>Personal Blog</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("upload", $CONF); ?>">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-lock fa-5x"></i>
          <div class="caption">
            <h3>Encrypted File Uploads</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("paste", $CONF); ?>">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-code fa-5x"></i>
          <div class="caption">
            <h3>Clean Pastebin</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("podcast", $CONF); ?>">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-microphone fa-5x"></i>
          <div class="caption">
            <h3>Entertaining Podcasts</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("help", $CONF); ?>/#Mumble">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-comments fa-5x"></i>
          <div class="caption">
            <h3>Mumble Server</h3>
          </div>
        </div>
      </div>
    </a>
    <a href="<?php echo get_page_url("api", $CONF); ?>">
      <div class="col-md-3 text-center">
        <div class="thumbnail">
          <br />
          <i class="fa fa-exchange fa-5x"></i>
          <div class="caption">
            <h3>Easy to Use API</h3>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>