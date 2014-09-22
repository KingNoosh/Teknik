<?php
$irc_info = $db->select('irc', "1=? ORDER BY id DESC LIMIT 1", array("1"));
$max_count = $irc_info['max_nicks'];
$count = $irc_info['cur_nicks'];
$topic = $irc_info['topic'];
?>
<div class="container">
  <div class="jumbotron text-center">
    <p>
      Teknik is the website for the #/g/technology IRC channel on Rizon.
      <br />
      We host various channels services for our IRC community and by extension, 4chan's Technology board.
    </p>
  </div>
</div>
