<div class="container">
  <div class="row">
    <center>
      <img src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/img/logo-blue.svg" class="img-responsive" alt="Teknik">
    </center>
  </div>
  <br />
  <div class="row text-center">
    <h2>
      Teknik is dedicated to the advancement of technology and ideas, and we provide these services to help those who try to innovate.
    </h2>
  </div>
</div>
<br />
<div class="container"> 
  <div class="col-sm-8">
  </div>
  <div class="col-sm-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title text-center">Recent Blog Posts</h3>
      </div>
      <div class="panel-body">
      <?php
        $new_posts = $db->select('blog', "1=? ORDER BY date_posted DESC LIMIT 10", array(1));
        $posts = array();
        foreach ($new_posts as $post)
        {
          if (!is_array($post))
          {
            $posts = array($new_posts);
            break;
          }
          array_push($posts, $post);
        }
        foreach ($posts as $post)
        {
          $post_id = $post['id'];
          $author_id = $post['author_id'];
          $author = $userTools->get($author_id);
          $date = $post['date_posted'];
          $title = $post['title'];
          $tags = $post['tags'];
          $post = $post['post'];
          $reply_msg = "";

          $replies = $db->select('comments', "reply_id=? AND service=?", array($post_id, 'blog'), 'count(*) cnt');
          $reply_count = $replies['cnt'];
          if ($reply_count > 0)
          {
            $reply_msg = " | <a href='".get_page_url("blog", $CONF)."/".$author->username."/".$post_id."#replies'>Replies:".$reply_count."</a>";
          }
        ?>
          <script>
            var converter = new Markdown.getSanitizingConverter();
            // Title Conversion
            var old_post = $("#title_<?php echo $post_id; ?>").text();
            var new_post = converter.makeHtml(old_post);
            $("#title_<?php echo $post_id; ?>").html(new_post);
          </script>
          <div class="row">
            <div class="col-sm-12">
              <div class="blog-post">
                <h2 class="blog-post-title-sm text-left"><a href="<?php echo get_page_url("blog", $CONF); ?>/<?php echo $author->username; ?>/<?php echo $post_id; ?>" id="title_<?php echo $post_id; ?>"><?php echo $title; ?></a></h2>
                <p class="blog-post-meta text-left text-muted">
                  Posted on <?php echo date("F d, Y",strtotime($date)); ?> by <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $author->username; ?>"><?php echo $author->username; ?></a><?php echo $reply_msg; ?>
                </p>
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
<br />
<div class="container">
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
  </div>
  <div class="row">
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
            <h3>Technical Podcasts</h3>
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
