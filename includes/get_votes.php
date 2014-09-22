<?php

require_once('config.php');
$id = 0;
$votes = 0;
if(isset($_POST))
{
  $id = rawurldecode($_POST['id']);
  $results = $db->select('votes', 'id=?', array($id), 'sum(points) totalPoints');
  $votes = $results['totalPoints'];
}
echo $votes;
?>