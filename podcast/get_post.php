<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  $postCount = rawurldecode($_POST['postCount']);
  $startPost = rawurldecode($_POST['startPost']);
  
  if (isset($_POST['postID']))
  {
    $posts = get_post('podcast', rawurldecode($_POST['postID']), $db);
  }
  else
  {
    $posts = get_podcast($db, $postCount, $startPost);
  }
  
  if ($posts)
  {
    foreach ($posts as $post)
    {
      $post_id = $post['id'];
      $date = $post['date_posted'];
      $title = $post['title'];
      $tags = $post['tags'];
      $file = $post['file_name'];
      $files = explode(',', $file);
      $post = $post['description'];
      $reply_msg = "";
      
      $replies = $db->select('comments', "reply_id=? AND service=?", array($post_id, 'podcast'), 'count(*) cnt');
      $reply_count = $replies['cnt'];
      if ($reply_count > 0)
      {
        $reply_msg = " | <a href='".get_page_url("podcast", $CONF)."/".$post_id."#replies'>Replies:".$reply_count."</a>";
      }
    ?>
      <script>
        var converter = new Markdown.getSanitizingConverter();
        var old_post = $("#post_<?php echo $post_id; ?>").text();
        var new_post = converter.makeHtml(old_post);
        $("#post_<?php echo $post_id; ?>").html(new_post);
      </script>
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
          <div class="podcast-post">
            <h2 class="podcast-post-title text-center"><a href="<?php echo get_page_url("podcast", $CONF); ?>/<?php echo $post_id; ?>" id="title_<?php echo $post_id; ?>"><?php echo $title; ?></a></h2>
            <p class="podcast-post-meta text-center text-muted">
              Posted on <?php echo date("F d, Y",strtotime($date)); ?><?php echo $reply_msg; ?>
              <?php
              if ($user->admin)
              {
              ?>
              <br />
              <button type="button" class="btn btn-info edit_post" id="<?php echo $post_id; ?>" data-toggle="modal" data-target="#editPodcast">Edit</button>
              <button type="button" class="btn btn-danger delete_post" id="<?php echo $post_id; ?>">Delete</button>
              <?php
              }
              ?>
            </p>
            <div class="text-center">
              <audio preload="auto" controls>
                <?php
                foreach ($files as $filename)
                {
                  $file_path = get_page_url("podcast", $CONF).'/Podcasts/'.$title.'/'.$filename;
                  $direct_path = $CONF['podcast_dir'].$title.'/'.$filename;
                  if (file_exists($direct_path))
                  {
                    $file_type = mime_content_type($direct_path);
                    ?>
                    <source src="<?php echo $file_path; ?>" type="<?php echo $file_type; ?>" />
                    <?php
                  }
                }
                ?>
              </audio>
            </div>
            <br />
            <p id="post_<?php echo $post_id; ?>"><?php echo $post; ?></p>
            <?php
            foreach ($files as $filename)
            {
              $file_path = get_page_url("podcast", $CONF).'/Podcasts/'.$title.'/'.$filename;
              $direct_path = $CONF['podcast_dir'].$title.'/'.$filename;
              if (file_exists($direct_path))
              {
                $file_type = mime_content_type($direct_path);
                ?>
                <div class="row text-center">
                  <a href="<?php echo $file_path; ?>">Direct Download - <?php echo explode('/', $file_type)[1]; ?></a>
                </div>
                <?php
              }
            }
            ?>
          </div>
        </div>
      </div>
  <?php
    }
  }
}
?>