<?php
require_once('../includes/config.php');
include('../templates/'.$CONF['template'].'/header.php');

$own_blog = false;
$author_id = 0;
$post_num = 0;
$comments_per_load = 10;
$error = "";

if (isset($_GET['post']))
{
  if (is_numeric($_GET['post']))
  {
    $post_num = (int) rawurldecode($_GET['post']);

    $posts = get_post('blog', $post_num, $db);

    if ($posts)
    {
      $post = $posts[0];
      $post_id = $post['id'];
      $author_id = $post['author_id'];
      $author = $userTools->get($author_id);
      $date = $post['date_posted'];
      $title = $post['title'];
      $tags = $post['tags'];
      $post = $post['post'];

      if ($author_id == $user->id || $user->admin)
      {
        $own_blog = true;
      }

      set_page_title($title);
      ?>
      <div class="container">
        <?php
        if ($own_blog)
        {
        ?>
        <div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-labelledby="editPostLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form class="form" action="##" method="post" id="editPostForm">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
                  <h4 class="modal-title" id="editPostLabel">Edit Your Post</h4>
                </div>
                <div class="modal-body">
                  <input name="edit_blog_userid" id="edit_blog_userid" type="hidden" value="<?php echo $author_id; ?>" />
                  <input name="edit_blog_postid" id="edit_blog_postid" type="hidden" />
                  <div class="row">
                    <div class="form-group col-sm-12">
                        <label for="edit_blog_title"><h4>Title</h4></label>
                        <input class="form-control" name="edit_blog_title" id="edit_blog_title" placeholder="generic click bait" title="enter a title for your post." type="text" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-12">
                      <label for="edit_blog_post"><h4>Article</h4></label>
                      <textarea class="form-control" name="edit_blog_post" id="edit_blog_post" placeholder="I ate a burger today." title="enter any information you want to share with the world." data-provide="markdown" rows="10"></textarea>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary" id="edit_submit">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        }        
        ?>
        <div class="blog-main" id="<?php echo $post_id; ?>">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1 blog-main">
              <div class="blog-post">
                <h2 class="blog-post-title text-center"><a href="<?php echo get_page_url("blog", $CONF); ?>/<?php echo $author->username; ?>/<?php echo $post_id; ?>" id="title_<?php echo $post_id; ?>"><?php echo $title; ?></a></h2>
                <p class="blog-post-meta text-center text-muted">
                  Posted on <?php echo date("F d, Y",strtotime($date)); ?> by <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $author->username; ?>"><?php echo $author->username; ?></a>
                  <?php
                  if ($own_blog)
                  {
                  ?>
                  <br />
                  <button type="button" class="btn btn-info edit_post" id="<?php echo $post_id; ?>" data-toggle="modal" data-target="#editPost">Edit</button>
                  <button type="button" class="btn btn-danger delete_post" id="<?php echo $post_id; ?>">Delete</button>
                  <?php
                  }
                  ?>
                </p>
                <p id="post_<?php echo $post_id; ?>"><?php echo $post; ?></p>
              </div>
            </div>
          </div>
        </div>
        <?php
        if ($logged_in)
        {
        ?>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newComment">Add Comment</button>
            </div>
          </div>
          <br />
          <div class="modal fade" id="newComment" tabindex="-1" role="dialog" aria-labelledby="newCommentLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form class="form" action="##" method="post" id="publishComment">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
                    <h4 class="modal-title" id="newCommentLabel">Add a New Comment</h4>
                  </div>
                  <div class="modal-body">
                    <input name="post_id" id="post_id" type="hidden" value="<?php echo $post_id; ?>" />
                    <div class="row">
                      <div class="form-group col-sm-12">
                        <label for="comment_post"><h4>Comment</h4></label>
                        <textarea class="form-control wmd-input" name="comment_post" id="comment_post" placeholder="Nice post!" title="enter what you think about the post." data-provide="markdown" rows="10"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="comment_submit">Publish</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
          <div class="modal fade" id="editComment" tabindex="-1" role="dialog" aria-labelledby="editCommentLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form class="form" action="##" method="post" id="editCommentForm">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
                    <h4 class="modal-title" id="editCommentLabel">Edit Your Comment</h4>
                  </div>
                  <div class="modal-body">
                    <input name="edit_comment_postid" id="edit_comment_postid" type="hidden" />
                    <div class="row">
                      <div class="form-group col-sm-12">
                        <label for="edit_comment_post"><h4>Comment</h4></label>
                        <textarea class="form-control" name="edit_comment_post" id="edit_comment_post" placeholder="What an interesting article!" title="enter what you thought about the article." data-provide="markdown" rows="10"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="edit_comment_submit">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
	<a name="replies">
        <div class="post-comments" id="<?php echo $post_id; ?>"></div>
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
          
          var posts = <?php echo $comments_per_load; ?>;
          var start_post = 0;
          var view_post_id = <?php echo $post_num; ?>;
          loadMoreComments(start_post, posts);
          start_post = start_post + posts;
        </script>
    <?php
    }
    else
    {
      set_page_title("Invalid Post");
    ?>
      <div class="row">
        <div class="col-sm-12 text-center">
          <h2>That post does not exist</h2>
        </div>
      </div>
    <?php
    }
    ?>
  </div>
<?php
  }
  else
  {
    set_page_title("Invalid Post");
  ?>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Invalid Post Number</h2>
      </div>
    </div>
  <?php
  }
}
else
{
  set_page_title("Invalid Post");
?>
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2>That post does not exist</h2>
    </div>
  </div>
<?php
}
include('../templates/'.$CONF['template'].'/footer.php');
?>