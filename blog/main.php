<?php
$own_blog = false;
$blog_id = 0;
$blog_author = "";
$blog_title = $CONF['blog_title'];
$blog_desc = $CONF['blog_desc'];
$title_bar = $CONF['blog_title'];
$posts_per_load = 10;
$error = "";

if (isset($_GET['author']))
{
  if ($userTools->checkUsernameExists($_GET['author']))
  {
    $blog_user = $userTools->getUser($_GET['author']);
    $blog_id = $blog_user->id;
    $blog_author = $blog_user->username;
    $blog_title = $blog_user->blog_title;
    $blog_desc = $blog_user->blog_desc;

    if ($blog_id == $user->id)
    {
      $own_blog = true;
    }

    if (empty($blog_title))
    {
      $title_bar = safe($_GET['author'])."'s Blog";
    }
    else
    {
      $title_bar = $blog_title;
    }
  }
  else
  {
    $blog_id = -1;
    $blog_title = "";
    $blog_desc = "";
    $error = "That user does not exist!";
  }
}

$blog_posts = $db->select('blog', "user_id=?", array($blog_id));
if (!$blog_posts && $blog_id >= 0)
{
  $error = "There are currently no articles.";
}

if ($user->admin)
{
  $own_blog = true;
}
  
set_page_title($title_bar);
?>
<div class="container">
  <?php  
  if ($blog_id >= 0)
  {
  ?>
    <div class="row">
      <div class="col-sm-12 blog-heading">
        <h1 class="blog-title text-center"><?php echo $blog_title; ?></h1>
        <p class="lead blog-description text-center text-muted"><?php echo $blog_desc; ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <p>
          <a href="<?php echo get_subdomain_full_url('rss', $CONF).'/blog/'.$blog_author; ?>"><i class="fa fa-rss fa-2x fa-border"></i></a>
        </p>
      </div>
    </div>
  <?php
  }
  if ($own_blog)
  {
  ?>
  <div class="row">
    <div class="col-sm-12 text-center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPost">Create Post</button>
    </div>
  </div>
  <div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-labelledby="newPostLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form" action="##" method="post" id="publishPost">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
            <h4 class="modal-title" id="newPostLabel">Create a New Post</h4>
          </div>
          <div class="modal-body">
            <input name="blog_userid" id="blog_userid" type="hidden" value="<?php echo $blog_id; ?>" />
            <div class="row">
              <div class="form-group col-sm-12">
                  <label for="blog_title"><h4>Title</h4></label>
                  <input class="form-control" name="blog_title" id="blog_title" placeholder="generic click bait" title="enter a title for your post." type="text" />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="blog_post"><h4>Article</h4></label>
                <textarea class="form-control wmd-input" name="blog_post" id="blog_post" placeholder="I ate a burger today." title="enter any information you want to share with the world." data-provide="markdown" rows="10"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="blog_submit">Publish</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="editPost" tabindex="-1" role="dialog" aria-labelledby="editPostLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form" action="##" method="post" id="editPostForm">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
            <h4 class="modal-title" id="editPostLabel">Edit Your Post</h4>
          </div>
          <div class="modal-body">
            <input name="edit_blog_userid" id="edit_blog_userid" type="hidden" value="<?php echo $blog_id; ?>" />
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
  
  if (empty($error))
  {
  ?>
    <div class="blog-main" id="<?php echo $blog_id; ?>"></div>
    <script>
      var posts = <?php echo $posts_per_load; ?>;
      var start_post = 0;
        loadMorePosts(start_post, posts);
        start_post = start_post + posts;
    </script>
  <?php
  }
  else
  {
  ?>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2><?php echo $error; ?></h2>
      </div>
    </div>
  <?php
  }
  ?>
</div>
