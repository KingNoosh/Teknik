<?php
function get_page_url($page, $CONF, $full = true)
{
  $full_url = get_http($CONF).$CONF['host'];
  switch ($CONF['url_type'])
  {
    case 'sub':
      if ($page == $CONF['default_page'])
      {
        $page = 'www';
      }
      $full_url = get_subdomain_full($page, $CONF);
      break;
    case 'page':
      $cur_sub = get_subdomain();
      $full_url = get_http($CONF).$cur_sub.".".$CONF['host']."/".$page;
      break;
  }
  return $full_url;
}

function get_subdomain_full($sub_part, $CONF)
{
  $url = get_http($CONF).$sub_part.".".$CONF['host'];
  return $url;
}

function extract_domain($domain)
{
    if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches))
    {
        return $matches['domain'];
    } else {
        return $domain;
    }
}

function extract_subdomains($domain)
{
    $subdomains = $domain;
    $domain = extract_domain($subdomains);

    $subdomains = rtrim(strstr($subdomains, $domain, true), '.');

    return $subdomains;
}

function get_subdomain()
{
  $sub = extract_subdomains($_SERVER['HTTP_HOST']);
  if ($sub == "")
  {
    $sub = "www";
  }
  return $sub;
}

function get_page()
{
  $url_array = explode("/",explode("/",$_SERVER["REQUEST_URI"])[1]);

  return $url_array[1];
}

function get_http($CONF)
{
  if ($CONF['https'] != "on")
  {
    $http = "http://";
  }
  else
  {
    $http = "https://";
  }
  return $http;
}

function get_active($page)
{
  if ($page == $CONF['default_page'])
  {
    $page = 'www';
  }
  $cur_page = '';
  switch ($CONF['url_type'])
  {
    case 'sub':
      $cur_page = get_subdomain();
      break;
    case 'page':
      $cur_page = get_page();
      break;
  }
  if ($cur_page == $page)
  {
    return 'active';
  }
  return "";
}

function checkemail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function safe($input)
{
  $valid_input = addslashes($input);
  return $valid_input;
}

function safe_register($input)
{
  $input = rawurlencode($input);
  $valid_input = mysql_real_escape_string($input);
  return $valid_input;
}

function rand_string( $length ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}

function hashPassword($password, $CONF)
{
  $hashed_pass = hash("sha256",sha1($CONF['salt'].$password.$CONF['salt_2']));
  return $hashed_pass;
}

function generate_code($key, $CONF)
{
  $randomString = rand_string(6);
  $key = hash("sha256",sha1($randomString.$CONF['salt'].$key.$CONF['salt_2']));
  return $key;
}

function get_mime_type($filepath) {
    ob_start();
    system("file -i -b {$filepath}");
    $output = ob_get_clean();
    $output = explode("; ",$output);
    if ( is_array($output) ) {
        $output = $output[0];
    }
    return $output;
}

function set_page_title($title)
{

  $output = ob_get_contents();
  // clean the buffer to avoid duplicates
  ob_clean();
  // replace the title with the generated title
  $output = str_replace('{title_holder}', $title,$output);
  // put the html back in buffer
  echo $output;

}

function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
    }else{
      header('Location: ' . $url);
      die();
    }    
}

function upload($files, $CONF, $db)
{
  if (!empty($files)) {
      $filesize = filesize($files['file']['tmp_name']);
      $file_type = mime_content_type($files['file']['tmp_name']);
      if ($logged_in == 1)
      {
        $user_id = $user->id;
      }
      else
      {
        $user_id = 0;
      }
      if ($filesize <= (pow(1024, 2) * $CONF['max_upload_size']))
      {
        $iv = rand_string(32);
        $targetFile = upload_file($files, $CONF['upload_dir'], $CONF['key'], $iv, $CONF['cipher']);
        $data = array(
                    "filename" => $targetFile,
                    "type" => $file_type,
                    "user_id" => $user_id,
                    "upload_date" => date("Y-m-d H:i:s",time()),
                    "filesize" => $filesize,
                    "hash" => $iv,
                    "cipher" => $CONF['cipher']
                );
        $db->insert($data, 'uploads');
        $_SESSION[$targetFile] = $targetFile;
        return array('results' => array('file' => array('name' => $targetFile, 'url' => get_page_url("u", $CONF).'/'.$targetFile, 'type' => $file_type, 'size' => $filesize)));
      }
      return array('error' => $CONF['errors']['InvFile']);
  }
  return array('error' => $CONF['errors']['NoFile']);
}

function upload_file($file, $destination, $key, $iv, $cipher)
{
    $tempFile = $file['file']['tmp_name'];
    
    $fileType = pathinfo($file['file']['name'], PATHINFO_EXTENSION);
    $file_used = true;
    while ($file_used)
    {
      $randomString = rand_string(6);
      $targetFile = $randomString.'.'.$fileType;
      if (!file_exists($destination.$targetFile))
      {
        $file_used = false;
      }
    }
    
    $crypt = new Cryptography();
    $crypt->Encrypt($key, $iv, $tempFile, $destination.$targetFile, $cipher);
    $result = unlink($tempFile);
    return $targetFile;
}

function get_blog($blog_id, $db, $post_count = null, $start_post = null)
{  
  if ($post_count != null && $start_post != null)
  {
    $limit = " LIMIT ".$start_post.", ".$post_count;
  }
  else if ($post_count != null)
  {
    $limit = " LIMIT ".$post_count;
  }
  else
  {
    $limit == "";
  }
  
  $blog_posts = $db->select('blog', "user_id=? ORDER BY date_posted DESC".$limit, array($blog_id));

  $posts = array();
  foreach ($blog_posts as $post)
  {
    if (!is_array($post))
    {
      $posts = array($blog_posts);
      break;
    }
    array_push($posts, $post);
  }
  
  return $posts;
}

function get_podcast($db, $post_count = null, $start_post = null)
{  
  if ($post_count != null && $start_post != null)
  {
    $limit = " LIMIT ".$start_post.", ".$post_count;
  }
  else if ($post_count != null)
  {
    $limit = " LIMIT ".$post_count;
  }
  else
  {
    $limit == "";
  }
  
  $podcast_posts = $db->select('podcast', "1=? ORDER BY date_posted DESC".$limit, array(1));

  $posts = array();
  foreach ($podcast_posts as $post)
  {
    if (!is_array($post))
    {
      $posts = array($podcast_posts);
      break;
    }
    array_push($posts, $post);
  }
  
  return $posts;
}

function get_comments($service, $post_id, $db, $comment_count = null, $start_comment = null)
{  
  if ($comment_count != null && $start_comment != null)
  {
    $limit = " LIMIT ".$start_comment.", ".$comment_count;
  }
  else if ($comment_count != null)
  {
    $limit = " LIMIT ".$comment_count;
  }
  else
  {
    $limit == "";
  }
  
  $post_comments = $db->select('comments', "service=? AND reply_id=? ORDER BY date_posted ASC".$limit, array($service, $post_id));

  $comments = array();
  foreach ($post_comments as $comment)
  {
    if (!is_array($comment))
    {
      $comments = array($post_comments);
      break;
    }
    array_push($comments, $comment);
  }
  
  return $comments;
}

function get_post($service, $post_id, $db)
{
  $all_posts = $db->select($service, "id=?", array($post_id));

  $posts = array();
  foreach ($all_posts as $post)
  {
    if (!is_array($post))
    {
      $posts = array($all_posts);
      break;
    }
    array_push($posts, $post);
  }
  
  return $posts;
}

function run_command($command, $cwd = ".") {
  $descriptorspec = array(
    1 => array('pipe', 'w'),
    2 => array('pipe', 'w'),
  );
  $pipes = array();
  /* Depending on the value of variables_order, $_ENV may be empty.
   * In that case, we have to explicitly set the new variables with
   * putenv, and call proc_open with env=null to inherit the reset
   * of the system.
   *
   * This is kind of crappy because we cannot easily restore just those
   * variables afterwards.
   *
   * If $_ENV is not empty, then we can just copy it and be done with it.
   */
  if(count($_ENV) === 0) {
    $env = NULL;
    foreach($this->envopts as $k => $v) {
      putenv(sprintf("%s=%s",$k,$v));
    }
  } else {
    $env = array_merge($_ENV, $this->envopts);
  }
  $resource = proc_open($command, $descriptorspec, $pipes, $cwd, $env);
  $stdout = stream_get_contents($pipes[1]);
  $stderr = stream_get_contents($pipes[2]);
  foreach ($pipes as $pipe) {
    fclose($pipe);
  }

  $status = trim(proc_close($resource));
  if ($status) throw new Exception($stderr);
  //exec($command, $stdout);
  return $stdout;
}

function mirc2html($x) {

  $tokenizer = new Tokenizer($x);
   
  while(list($token, $data) = $tokenizer->getNext())
  {
      switch($token)
      {
          case 'color-fgbg':
              printf('<%s:%d,%d>', $token, $data[1], $data[2]);
              break;
   
          case 'color-fg':
              printf('<%s:%d>', $token, $data[1]);
              break;
   
          case 'color-reset':
          case 'style-bold';
              printf('<%s>', $token);
              break;
   
          case 'catch-all':
              echo $data[0];
              break;
   
          default:
              throw new Exception(sprintf('Unknown token <%s>.', $token));
      }
  }

    //$c = array("FFF","000","00007F","009000","FF0000","7F0000","9F009F","FF7F00","FFFF00","00F800","00908F","00FFFF","0000FF","FF00FF","7F7F7F","CFD0CF");
    $x = preg_replace("/\x02(.*?)((?=\x02)\x02|$)/", "<b>$1</b>", $x);
    $x = preg_replace("/\x1F(.*?)((?=\x1F)\x1F|$)/", "<u>$1</u>", $x);
    $x = preg_replace("/\x1D(.*?)((?=\x1D)\x1D|$)/", "<i>$1</i>", $x);
    /*
    $x = preg_replace("/\x03(\d\d?),(\d\d?)(.*?)(?(?=\x03)|$)/e", "'</span><span style=\"color: #'.\$c[$1].'; background-color: #'.\$c[$2].';\">$3</span>'", $x);
    $x = preg_replace("/\x03(\d\d?)(.*?)(?(?=\x03)|$)/e", "'</span><span style=\"color: #'.\$c[$1].';\">$2</span>'", $x);
    $x = preg_replace("/(\x0F|\x03)(.*?)/", "<span style=\"color: #000; background-color: #FFF;\">$2</span>", $x);
    //$x = preg_replace("/\x16(.*?)/", "<span style=\"color: #FFF; background-color: #000;\">$1</span>", $x);
    //$x = preg_replace("/\<\/span\>/","",$x,1);
    //$x = preg_replace("/(\<\/span\>){2}/","</span>",$x);
    */
    //preg_replace_callback("/(\x03)(\d\d?,\d\d?|\d\d?)(\s?.*?)(?(?=\x03)|$)/","color_rep",trim($topic));
    $x = preg_replace_callback('/\^C([0-9]{1,2}),?([0-9]{1,2})(.*?)\^C/', 'mycallback', $x);
    return $x;
}

function mycallback($matches) {
    $bindings = array(
       0=>'white',
       1=>'black',
       2=>'blue',
       3=>'green',
       4=>'red',
       5=>'brown',
       6=>'purple',
    );
    $c = array("FFF","000","00007F","009000","FF0000","7F0000","9F009F","FF7F00","FFFF00","00F800","00908F","00FFFF","0000FF","FF00FF","7F7F7F","CFD0CF");

    $fg = isset($c[$matches[1]]) ? $c[$matches[1]] : 'transparent';
    $bg = isset($c[$matches[2]]) ? $c[$matches[2]] : 'transparent';

    return '<span style="color: #'.$fg.'; background: #'.$bg.';">'.$matches[3].'</span>';
}


function color_rep($matches) {
    $matches[2] = ltrim($matches[2], "0");
    $bindings = array(0=>'white',1=>'black',2=>'blue',3=>'green',4=>'red',5=>'brown',6=>'purple',7=>'orange',8=>'yellow',9=>'lightgreen',10=>'#00908F',
        11=>'lightblue',12=>'blue',13=>'pink',14=>'grey',15=>'lightgrey');
    $preg = preg_match_all('/(\d\d?),(\d\d?)/',$matches[2], $col_arr);
    //print_r($col_arr);
    $fg = isset($bindings[$matches[2]]) ? $bindings[$matches[2]] : 'transparent';
    if ($preg == 1) {
        $fg = $bindings[$col_arr[1][0]];
        $bg = $bindings[$col_arr[2][0]];
    }
    else {
        $bg = 'transparent';
    }
    
    return '<span style="color: '.$fg.'; background: '.$bg.';">'.$matches[3].'</span>';
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}


/*
// Extend the rcon class to tweak it for minecraft.
class minecraftRcon extends rcon {
  function mcSendCommand($Command) {
    $this->_Write(SERVERDATA_EXECCOMMAND,$Command,'');
  }

  function mcRconCommand($Command) {
    $this->mcSendcommand($Command);

    $ret = $this->Read();

    return $ret[$this->_Id]['S1'];
  }
  
  function Auth () {
    $PackID = $this->_Write(SERVERDATA_AUTH,$this->Password);$ret = $this->_PacketRead();
    if ($ret[0]['ID'] == -1) {
      die("Authentication Failure\n");
    }
    return true;
  }
}
*/
function full_copy($source, $target)
{
  if ( is_dir( $source ) )
  {
    @mkdir( $target );
  
    $d = dir( $source );
  
    while ( FALSE !== ( $entry = $d->read() ) )
    {
        if ( $entry == '.' || $entry == '..' )
        {
            continue;
        }
      
        $Entry = $source . '/' . $entry;          
        if ( is_dir( $Entry ) )
        {
            full_copy( $Entry, $target . '/' . $entry );
            continue;
        }
        copy( $Entry, $target . '/' . $entry );
    }
  
    $d->close();
  }
  else
  {
      copy( $source, $target );
  }
}

function deleteAll($directory, $empty = false)
{
  if(substr($directory,-1) == "/") {
      $directory = substr($directory,0,-1);
  }

  if(!file_exists($directory) || !is_dir($directory)) {
      return false;
  } elseif(!is_readable($directory)) {
      return false;
  } else {
      $directoryHandle = opendir($directory);
     
      while ($contents = readdir($directoryHandle)) {
          if($contents != '.' && $contents != '..') {
              $path = $directory . "/" . $contents;
             
              if(is_dir($path)) {
                  deleteAll($path);
              } else {
                  unlink($path);
              }
          }
      }
     
      closedir($directoryHandle);

      if($empty == false) {
          if(!rmdir($directory)) {
              return false;
          }
      }
      return true;
  }
} 

function bytesToSize($bytes, $precision = 2)
{	
	$kilobyte = 1024;
	$megabyte = $kilobyte * 1024;
	$gigabyte = $megabyte * 1024;
	$terabyte = $gigabyte * 1024;
	
	if (($bytes >= 0) && ($bytes < $kilobyte)) {
		return $bytes . ' B';

	} elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
		return round($bytes / $kilobyte, $precision) . ' KB';

	} elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
		return round($bytes / $megabyte, $precision) . ' MB';

	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
		return round($bytes / $gigabyte, $precision) . ' GB';

	} elseif ($bytes >= $terabyte) {
		return round($bytes / $terabyte, $precision) . ' TB';
	} else {
		return $bytes . ' B';
	}
}

function trim_value(&$value)
{
    $value = trim($value);    // this removes whitespace and related characters from the beginning and end of the string
}
?>