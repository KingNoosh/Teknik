<?php
require_once('config.php');
 
//check to see that the form has been submitted
$id = 0;
$vote = "";
$points = 0;
if(isset($_POST) && $logged_in)
{
  $user_id = $user->id;
  $id = rawurldecode($_POST['id']);
  $table = rawurldecode($_POST['table']);
  $points = rawurldecode($_POST['vote']);
  $user_vote = $db->select('votes', 'table_name=? AND row_id=? AND user_id=? ORDER BY id DESC LIMIT 1', array($table, $id, $user_id));
  if ($user_vote)
  {
    $old_points = $user_vote['points'];
    if ($old_points != $points)
    {
      $points = $old_points + $points;
    }
    $data = array(
                "points" => $points
            );
    //update the row in the database
    $db->update($data, 'votes', 'id=?', array($user_vote['id']));
  }
  else
  {
    $data = array(
        "table_name" => $table,
        "row_id" => $id,
        "user_id" => $user_id,
        "points" => $points
    );
    
    $db->insert($data, 'votes');
  }
  $results = $db->select('votes', 'table_name=? AND row_id=?', array($table, $id), 'sum(points) totalPoints');
  $votes = $results['totalPoints'];
  echo $votes;
}
else
{
  echo "false";
}
?>