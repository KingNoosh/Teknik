<?php
/*
* Project Teknik - By Chris Woodward
* Integration of all my services under one roof.
* Maybe awesome?
*
* Single Creation Page
*
*/

require_once('../includes/config.php');
include('../templates/'.$CONF['template'].'/header.php');

if (isset($_GET['id']))
{
  $desktop_id = $_GET['id'];
  $result = $db->select_raw('ricehalla', "INNER JOIN votes ON ricehalla.id=votes.row_id WHERE votes.table_name=? AND ricehalla.id=? GROUP BY votes.row_id ORDER BY TotalPoints DESC", array("ricehalla", $desktop_id), 'ricehalla.url, ricehalla.user_id, ricehalla.id, ricehalla.tags, votes.points, votes.user_id, sum(votes.points) TotalPoints, ricehalla.date_added');
  
  if ($result)
  {              
    // Generate Ranking List
    $rankResults = $db->select_raw('ricehalla', "INNER JOIN votes ON ricehalla.id=votes.row_id WHERE votes.table_name=? GROUP BY votes.row_id ORDER BY TotalRank DESC, TotalVotes DESC, TotalPoints DESC", array("ricehalla"), 'ricehalla.id, sum(votes.points) as TotalPoints, COUNT(votes.id) as TotalVotes, (sum(votes.points) / COUNT(votes.id)) * abs(sum(votes.points)) as TotalRank');
    $rank_list = array();
    foreach ($rankResults as $rank_result)
    {
      if (!is_array($rank_result))
      {
        $result_list = array($rankResults);
        break;
      }
      array_push($rank_list, $rank_result);
    }
    
    $username = $userTools->get($result['user_id'])->username;
    $rank = multi_array_search($rank_list, array('id' => $desktop_id))[0] + 1;
    $image_src = get_page_url("u", $CONF).'/'.$result['url'];
    $user_vote = $db->select('votes', 'table_name=? AND row_id=? AND user_id=? ORDER BY id DESC LIMIT 1', array('ricehalla', $result['id'], $user->id));
    $thumb_up = "btn-hover";
    $thumb_down = "btn-hover";
    if ($user_vote)
    {
      if ($user_vote['points'] > 0)
      {
        $thumb_up = "";
      }
      if ($user_vote['points'] < 0)
      {
        $thumb_down = "";
      }
    }
?>
<div class="container">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <div class="row">
        <div class="col-sm-1">Rank</div>
        <div class="col-sm-<?php if ($logged_in) { echo '2'; } else { echo '1'; } ?>">Points</div>
        <div class="col-sm-2">Owner</div>
        <div class="col-sm-2">Date Posted</div>
        <div class="col-sm-5">Tags</div>
      </div>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-1">
          <?php echo $rank; ?>
        </div>
        <div class="col-sm-<?php if ($logged_in) { echo '2'; } else { echo '1'; } ?>">
          <div class="row">
            <div class="col-sm-2" id="points_<?php echo $result['id']; ?>">
              (<?php echo $result['TotalPoints']; ?>)
            </div>
            <?php
            if ($logged_in)
            {
            ?>
              <div class="col-sm-2">
                <a href="#" class="btn btn-sm <?php echo $thumb_up; ?> btn-success vote_up" id="vote_up_<?php echo $result['id']; ?>" value="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-thumbs-up"></span></a>
              </div>
              <div class="col-sm-2">
                <a href="#" class="btn btn-sm <?php echo $thumb_down; ?> btn-danger vote_down" id="vote_down_<?php echo $result['id']; ?>" value="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-thumbs-down"></span></a>
              </div>
              <?php
              if ($result['user_id'] == $user->id)
              {
              ?>
              <div class="col-sm-2">
                <button class="btn btn-sm btn-danger delete_image" id="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
              </div>
              <?php
              }
            }
            ?>
          </div>
        </div>
        <div class="col-sm-2">
          <?php echo $username; ?>
        </div>
        <div class="col-sm-2">
          <?php echo $result['date_added']; ?>
        </div>
        <div class="col-sm-5">
          <div id="taglist-<?php echo $result['id']; ?>" class="tag-list"></div>
        </div>
        <script>
            $(function() {                                
                var tag_str = "<?php echo $result['tags']; ?>";
                if (tag_str != "")
                {
                  var tag_list = tag_str.split(",");
                  var tags = new Array();
                  
                  for (var i = 0; i < tag_list.length; i++)
                  {
                    tags.push(tag_list[i]);
                  }
                }
                
                $('#taglist-<?php echo $result['id']; ?>').tags({
                    suggestions:["Windows", "Linux"],
                    
                    <?php
                    if (!$logged_in || $result['user_id'] != $user->id)
                    {
                    ?>
                      readOnly: true,
                    <?php
                    }
                    ?>
                    tagData: tags,
                    tagSize: "sm",
                    tagClass: "btn-primary",
                    afterAddingTag: function(tag){
                      var current_tags = $('#taglist-<?php echo $result['id']; ?>').tags().getTags();
                      var url = "../edit_tags.php";
                      var data = "id="+encodeURIComponent(<?php echo $result['id']; ?>)+"&tags="+encodeURIComponent(current_tags);
                      $.ajax({
                        type: "POST",
                        url: url,
                        data: data
                      });
                    },
                    afterDeletingTag: function(tag){
                      var current_tags = $('#taglist-<?php echo $result['id']; ?>').tags().getTags();
                      var url = "../edit_tags.php";
                      var data = "id="+encodeURIComponent(<?php echo $result['id']; ?>)+"&tags="+encodeURIComponent(current_tags);
                      $.ajax({
                        type: "POST",
                        url: url,
                        data: data
                      });
                    }
                });
            });
        </script>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row text-center">
    <div class="col-sm-12 view_image" style="overflow: hidden;">
      <a href="<?php echo get_page_url("u", $CONF).'/'.$result['url']; ?>" value="<?php echo $image_src; ?>" target="_blank">
        <img src="<?php echo $image_src; ?>" class="img-responsive img-thumbnail" alt="">
      </a>
    </div>
  </div>
</div>
<br />
<?php
    include('../templates/'.$CONF['template'].'/footer.php');

    set_page_title($username . "'s Creation - Teknik's Ricehalla");
  }
  else
  {
    set_page_title("Teknik's Ricehalla");
  ?>
<div class="container">
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2>The specified creation does not exist.</h2>
    </div>
  </div>
</div>
  <?php
  }
}
else
{
  redirect(get_page_url("home", $CONF));
}
?>