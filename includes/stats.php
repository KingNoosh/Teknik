<?php
require_once('config.php');
include_once('classes/SmartIRC.php');
$irc = &new Net_SmartIRC();
//$irc->startBenchmark();
//$irc->setDebug(SMARTIRC_DEBUG_ALL);
$irc->setUseSockets(TRUE);
//$irc->setBenchmark(TRUE);
$irc->connect($CONF['irc_network'], $CONF['irc_port']);
$irc->login($CONF['irc_nick'], $CONF['irc_realname'], 0, $CONF['irc_nick'], $CONF['irc_pass']);
$irc->getList($CONF['irc_channel']);
$result_count = $irc->listenFor(SMARTIRC_TYPE_LIST);
$irc->disconnect();
//$irc->stopBenchmark();
if (is_array($result_count)) {
  $ircdata = $result_count[0];
  $count = $ircdata->rawmessageex[4];
  $topic = "";
  for ($i = 6; $i < sizeof($ircdata->rawmessageex); $i++)
  {
    $topic .= " ".$ircdata->rawmessageex[$i];
  }
  
  //$topic = mirc2html(trim($topic));
  $topic = preg_replace_callback("/(\x03)(\d\d?,\d\d?|\d\d?)(\s?.*?)(?(?=\x03)|$)/","color_rep",trim($topic));
  
  $irc_data = $db->select('irc', "1=? ORDER BY id DESC LIMIT 1", array("1"));
  if ($irc_data['max_nicks'] < $count)
  {
    $max_count = $count;
  }
  else
  {
    $max_count = $irc_data['max_nicks'];
  }
  if (empty($topic))
  {
    $topic = $irc_data['topic'];
  }
  $db->insert(array('max_nicks' => $max_count, 'cur_nicks' => $count, 'topic' => $topic, 'check_date' => date("Y-m-d H:i:s",time())), 'irc');
}
?>