<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  $postID = rawurldecode($_POST['postID']);
  $service = rawurldecode($_POST['service']);
  $postCount = rawurldecode($_POST['postCount']);
  $startPost = rawurldecode($_POST['startPost']);
  
  $comments = get_comments($service, $postID, $db, $postCount, $startPost);
  
  if ($comments)
  {
    foreach ($comments as $comment)
    {
      $own_comment = false;
      $post_id = $comment['id'];
      $author_id = $comment['user_id'];
      $reply_id = $comment['reply_id'];
      $author = $userTools->get($author_id);
      $date = $comment['date_posted'];
      $comment = $comment['post'];
      
      $reply_user_id = -1;
      $reply = $db->select($service, "id=? LIMIT 1", array($reply_id));
      if ($reply)
      {
        $reply_user_id = $reply['author_id'];
      }

      if ($author_id == $user->id || $user->admin || $user->id == $reply_user_id)
      {
        $own_comment = true;
      }
    ?>
      <script>
        var converter = new Markdown.getSanitizingConverter();
        var old_post = $("#comment_<?php echo $post_id; ?>").html();
        var new_post = converter.makeHtml(old_post);
        $("#comment_<?php echo $post_id; ?>").html(new_post);
      </script>
      <hr>
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
          <div class="post-comment">
            <p class="post-comment-meta text-muted">
              <a href="<?php echo get_subdomain_full_url("www", $CONF); ?>/<?php echo $author->username; ?>"><?php echo $author->username; ?></a> replied at <?php echo date("g:i:s a",strtotime($date)); ?> on <?php echo date("F d, Y",strtotime($date)); ?>
              <?php
              if ($own_comment && $logged_in)
              {
              ?>
              <br />
              <?php
              if ($author_id == $user->id || $user->admin)
              {
              ?>
              <button type="button" class="btn btn-info edit_comment" id="<?php echo $post_id; ?>" data-toggle="modal" data-target="#editComment">Edit</button>
              <?php
              }
              ?>
              <button type="button" class="btn btn-danger delete_comment" id="<?php echo $post_id; ?>">Delete</button>
              <?php
              }
              ?>
            </p>
            <p id="comment_<?php echo $post_id; ?>"><?php echo $comment; ?></p>
          </div>
        </div>
      </div>
  <?php
    }
  }
}
?>