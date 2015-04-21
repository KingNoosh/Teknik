<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  $own_blog = false;
  $userID = rawurldecode($_POST['userID']);
  $postCount = rawurldecode($_POST['postCount']);
  $startPost = rawurldecode($_POST['startPost']);

  if ($userID == $user->id)
  {
    $own_blog = true;
  }

  if ($user->admin)
  {
    $own_blog = true;
  }
  
  if (isset($_POST['postID']))
  {
    $posts = get_post(rawurldecode('blog', $_POST['postID']), $db);
  }
  else
  {
    $posts = get_blog($userID, $db, $postCount, $startPost);
  }
  
  if ($posts)
  {
    $viewablePosts = 0;
    foreach ($posts as $post)
    {
      $post_id = $post['id'];
      $author_id = $post['author_id'];
      $author = $userTools->get($author_id);
      $date = $post['date_posted'];
      $published = $post['published'];
      $title = $post['title'];
      $tags = $post['tags'];
      $post = $post['post'];
      $reply_msg = "";
      
      if ($published || $own_blog)
      {

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
          // Post Conversion
          var old_post = $("#post_<?php echo $post_id; ?>").text();
          var new_post = converter.makeHtml(old_post);
          $("#post_<?php echo $post_id; ?>").html(new_post);
        </script>
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <div class="blog-post">
              <h2 class="blog-post-title text-center"><a href="<?php echo get_page_url("blog", $CONF); ?>/<?php echo $author->username; ?>/<?php echo $post_id; ?>" id="title_<?php echo $post_id; ?>"><?php echo $title; ?></a></h2>
              <p class="blog-post-meta text-center text-muted">
                Posted on <?php echo date("F d, Y",strtotime($date)); ?> by <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $author->username; ?>"><?php echo $author->username; ?></a><?php echo $reply_msg; ?>
                <?php
                if ($own_blog)
                {
                ?>
                <br />
                <button type="button" class="btn btn-info edit_post" id="<?php echo $post_id; ?>" data-toggle="modal" data-target="#editPost">Edit</button>
                  <?php
                  if ($published)
                  {
                  ?>
                <button type="button" class="btn btn-warning unpublish_post" id="<?php echo $post_id; ?>">Unpublish</button>
                  <?php
                  }
                  else
                  {
                  ?>
                <button type="button" class="btn btn-success publish_post" id="<?php echo $post_id; ?>">Publish</button>
                  <?php
                  }
                  ?>
                <button type="button" class="btn btn-danger delete_post" id="<?php echo $post_id; ?>">Delete</button>
                <?php
                }
                ?>
              </p>
              <p id="post_<?php echo $post_id; ?>"><?php echo $post; ?></p>
            </div>
          </div>
        </div>
    <?php
        $viewablePosts++;
      }
    }
    if ($viewablePosts == 0)
    {
      ?>
      <div class="row">
        <div class="col-sm-12 text-center">
          <h2>There are currently no articles.</h2>
        </div>
      </div>
      <?php
    }
  }
}
?>
