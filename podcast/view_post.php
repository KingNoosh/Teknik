<?php
require_once('../includes/config.php');
include('../templates/'.$CONF['template'].'/header.php');

$post_num = 0;
$comments_per_load = 10;
$error = "";

if (isset($_GET['post']))
{
  if (is_numeric($_GET['post']))
  {
    $post_num = (int) rawurldecode($_GET['post']);

    $posts = get_post('podcast', $post_num, $db);

    if ($posts)
    {
      $post = $posts[0];
      $post_id = $post['id'];
      $date = $post['date_posted'];
      $title = $post['title'];
      $tags = $post['tags'];
      $file = $post['file_name'];
      $files = explode(',', $file);
      $post = $post['description'];

      set_page_title($title);
      ?>
      <div class="container">
        <?php
        if ($user->admin)
        {
        ?>
        <div class="modal fade" id="editPodcast" tabindex="-1" role="dialog" aria-labelledby="editPodcastLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form class="form" action="##" method="post" id="editPodcastForm">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
                  <h4 class="modal-title" id="editPodcastLabel">Edit Your Post</h4>
                </div>
                <div class="modal-body">
                  <input name="edit_podcast_postid" id="edit_podcast_postid" type="hidden" />
                  <div class="row">
                    <div class="form-group col-sm-12">
                      <label for="edit_podcast_title"><h4>Title</h4></label>
                      <input class="form-control" name="edit_podcast_title" id="edit_podcast_title" placeholder="Awesome Podcast Title" title="enter a title for the podcast." type="text" />
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-12">
                      <label for="edit_podcast_post"><h4>Podcast Description</h4></label>
                      <textarea class="form-control wmd-input" name="edit_podcast_post" id="edit_podcast_post" placeholder="We talked about awesome stuff." title="enter what the podcast was about." data-provide="markdown" rows="10"></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-12">
                      <label for="uploadPodcast"><h4>Upload Podcast</h4></label>
                      <input id="edit_uploadPodcast" name="file" type="file" placeholder="podcast.ogg" title="select the podcast file." />
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-sm-12" id="edit_uploadedPodcasts"></div>
                    <input name="edit_podcast_file" id="edit_podcast_file" type="hidden" />
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
        <div class="podcast-main" id="<?php echo $post_id; ?>">
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1 podcast-main">
              <div class="podcast-post">
                <h2 class="podcast-post-title text-center"><a href="<?php echo get_page_url("podcast", $CONF); ?>/<?php echo $post_id; ?>" id="title_<?php echo $post_id; ?>"><?php echo $title; ?></a></h2>
                <p class="podcast-post-meta text-center text-muted">
                  Posted on <?php echo date("F d, Y",strtotime($date)); ?>
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
        <div class="post-comments" id="<?php echo $post_id; ?>" name="replies"></div>
        <script>
          $( function()
          {
            linkAudioPlayer('audio');
          });
          var converter = new Markdown.getSanitizingConverter();
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
      set_page_title("Invalid Podcast");
    ?>
      <div class="row">
        <div class="col-sm-12 text-center">
          <h2>That podcast does not exist</h2>
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
    set_page_title("Invalid Podcast");
  ?>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Invalid Podcast Number</h2>
      </div>
    </div>
  <?php
  }
}
else
{
  set_page_title("Invalid Podcast");
?>
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2>That podcast does not exist</h2>
    </div>
  </div>
<?php
}
include('../templates/'.$CONF['template'].'/footer.php');
?>