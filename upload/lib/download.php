<?php
include('../../includes/config.php');
$fileURL = $_GET['file'];
$file_db = $db->select('uploads', "url=? LIMIT 1", array($fileURL));
$file_path  = $CONF['upload_dir'] . $file_db['filename'];
$temp_path = sys_get_temp_dir()."\\".$file_db['filename'];

if (file_exists($file_path) && $file_db)
{
  if ($file_db['hash'] != "")
  {
    $crypt = new Cryptography();
    $result = $crypt->Decrypt($CONF['key'], $file_db['hash'], $file_path, $temp_path, $file_db['cipher']);
    if ($result)
    {
      $file_path = $temp_path;
    }
  }
  $file_type = $file_db['type'];
  $pattern = "/^((image)|(text)|(audio)|(video))\/(.*)$/";
  if(!preg_match($pattern, $file_type))
  {
    header("Content-Disposition: attachment; filename=\"".$file_db['filename']."\"");
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Type: '.$file_type);
    header('Content-Length: '.$file_db['filesize']);
    set_time_limit(0);
  }
  else
  {
    header('Content-Type: '.$file_type);
    header('Content-Length: '.$file_db['filesize']);
  }  
  readfile($file_path);
  if ($file_db['hash'] != "")
  {
    $result = unlink($file_path);
  }
}
else
{
  header('Location: '.get_page_url("error", $CONF).'/404');
}
?>