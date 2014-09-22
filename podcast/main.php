<?php
$podcast_title = $CONF['podcast_title'];
$podcast_desc = $CONF['podcast_desc'];
$posts_per_load = $CONF['podcasts_per_page'];
$error = "";

$podcast_posts = $db->select('podcast', "1=?", array(1));
if (!$podcast_posts)
{
  $error = "There are currently no podcasts.";
}
  
set_page_title($podcast_title);
?>
<div class="container">
    <div class="row">
      <div class="col-sm-12 blog-heading">
        <h1 class="podcast-title text-center"><?php echo $podcast_title; ?></h1>
        <p class="lead podcast-description text-center text-muted"><?php echo $podcast_desc; ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <p>
          <a href="<?php echo get_subdomain_full_url('rss', $CONF).'/podcast/'; ?>"><i class="fa fa-rss fa-2x fa-border"></i></a>
        </p>
      </div>
    </div>
  <?php
  if ($user->admin)
  {
  ?>
  <div class="row">
    <div class="col-sm-12 text-center">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newPodcast">Create Podcast</button>
    </div>
  </div>
  <div class="modal fade" id="newPodcast" tabindex="-1" role="dialog" aria-labelledby="newPodcastLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="form" action="##" method="post" id="publishPodcast">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cancel</span></button>
            <h4 class="modal-title" id="newPodcastLabel">Create a New Podcast</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="podcast_title"><h4>Title</h4></label>
                <input class="form-control" name="podcast_title" id="podcast_title" placeholder="Awesome Podcast Title" title="enter a title for the podcast." type="text" />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="podcast_post"><h4>Podcast Description</h4></label>
                <textarea class="form-control wmd-input" name="podcast_post" id="podcast_post" placeholder="We talked about awesome stuff." title="enter what the podcast was about." data-provide="markdown" rows="10"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
                <label for="uploadPodcast"><h4>Upload Podcast</h4></label>
                <input id="uploadPodcast" name="file" type="file" placeholder="podcast.ogg" title="select the podcast file." />
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12" id="uploadedPodcasts"></div>
              <input name="podcast_file" id="podcast_file" type="hidden" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="podcast_submit">Publish</button>
          </div>
        </form>
      </div>
    </div>
  </div>
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
  
  if (empty($error))
  {
  ?>
    <div class="podcast-main"></div>
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
