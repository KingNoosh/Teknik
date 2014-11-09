<?php
  $Results = $db->select_raw('ricehalla', "INNER JOIN votes ON ricehalla.id=votes.row_id WHERE votes.table_name=? GROUP BY votes.row_id ORDER BY TotalRank DESC, TotalVotes DESC, TotalPoints DESC", array("ricehalla"), 'ricehalla.url, ricehalla.user_id, ricehalla.id, ricehalla.tags, votes.points, votes.user_id, sum(votes.points) as TotalPoints, COUNT(votes.id) as TotalVotes, (sum(votes.points) / COUNT(votes.id)) * abs(sum(votes.points)) as TotalRank, ricehalla.date_added');

  $result_list = array();
  foreach ($Results as $result)
  {
    if (!is_array($result))
    {
      $result_list = array($Results);
      break;
    }
    array_push($result_list, $result);
  }
?>
<div class="container">
    <?php
    if ($logged_in)
    {
    ?>
    <div class="row">
      <center>
        <button type="button" class="btn btn-primary" id="uploader">Add Image</button>
      </center>
    </div>
    <?php
    }
    
    if ($Results)
    {
    ?>
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
              <div class="row filters">
                <div class="col-sm-1 filter-title"><input type="text" class="form-control text-center" placeholder="Rank" disabled></div>
                <div class="col-sm-<?php if ($logged_in) { echo '2'; } else { echo '1'; } ?> filter-title"><input type="text" class="form-control" placeholder="Points" disabled></div>
                <div class="col-sm-2 filter-title"><input type="text" class="form-control" placeholder="Owner" disabled></div>
                <div class="col-sm-2 filter-title"><input type="text" class="form-control" placeholder="Date Posted" disabled></div>
                <div class="col-sm-<?php if ($logged_in) { echo '3'; } else { echo '4'; } ?> filter-title"><input type="text" class="form-control" placeholder="Tags" disabled></div>
                <div class="col-sm-2 filter-title text-right"><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></div>
              </div>
            </div>
            <div class="panel-body">
              <?php
              $rank = 1;
              foreach ($result_list as $result)
              {
                $username = $userTools->get($result['user_id'])->username;
                $thumbnail_src = "../uploads/thumbnails/150_150_" . $result['url'];
                $image_src = get_page_url("u", $CONF).'/' . $result['url'];
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
              <div class="row desktop_row">
                <div class="col-sm-1 filter-col text-center">
                  <?php echo $rank++; ?>
                </div>
                <div class="col-sm-<?php if ($logged_in) { echo '2'; } else { echo '1'; } ?>">
                  <div class="col-sm-2 filter-col" id="points_<?php echo $result['id']; ?>">
                    (<?php echo $result['TotalPoints']; ?>)
                  </div>
                  <div class="col-sm-3">
                    <a href="<?php echo get_page_url("ricehalla", $CONF).'/'.$result['id']; ?>" target="_blank" class="btn btn-sm btn-hover btn-primary"><span class="glyphicon glyphicon-link"></span></a>
                  </div>
                  <?php
                  if ($logged_in)
                  {
                  ?>
                    <div class="col-sm-3">
                      <a href="#" class="btn btn-sm <?php echo $thumb_up; ?> btn-success vote_up" id="vote_up_<?php echo $result['id']; ?>" value="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-thumbs-up"></span></a>
                    </div>
                    <div class="col-sm-3">
                      <a href="#" class="btn btn-sm <?php echo $thumb_down; ?> btn-danger vote_down" id="vote_down_<?php echo $result['id']; ?>" value="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-thumbs-down"></span></a>
                    </div>
                    <?php
                    if ($result['user_id'] == $user->id)
                    {
                    ?>
                    <div class="col-sm-3">
                      <button class="btn btn-sm btn-danger delete_image" id="<?php echo $result['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                    <?php
                    }
                  }
                  ?>
                </div>
                <div class="col-sm-2 filter-col">
                  <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $username; ?>"><?php echo $username; ?></a>
                </div>
                <div class="col-sm-2 filter-col">
                  <?php echo $result['date_added']; ?>
                </div>
                <div class="col-sm-<?php if ($logged_in) { echo '3'; } else { echo '4'; } ?> filter-col">
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
                            suggestions:["Windows", "Linux", "Rice"],
                            
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
                <div class="col-sm-2 filter-col">
                  <a href="#" class="modalButton" data-toggle="modal" data-img="<?php echo $image_src; ?>" data-url="<?php echo get_page_url("u", $CONF).'/'.$result['url']; ?>" data-user="<?php echo $username; ?>" data-target="#viewCreation">
                    <img src="<?php echo $thumbnail_src; ?>" width="150" class="img-responsive img-rounded" alt="">
                  </a>
                </div>
              </div>
              <?php
              }
              ?>
            </div>
        </div>
      <div class="modal modal-wide fade" id="viewCreation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header text-center">
              <button type="button" class="close modalClose" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">Creation</h4>
            </div>
            <div class="modal-body">
              <center>
                <a href="" target="_blank">
                  <img src="" class="img-responsive" alt="">
                </a>
              </center>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div>
    <?php
    }
    else
    {
    ?>
    <div class="row text-center">
      <p>No Creations Available</p>
    </div>
    <?php
    }
    ?>
</div>
