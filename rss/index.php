<?php
require_once('../includes/config.php');

$rss_id = 0;
$rss_author = "";

if ($_GET['content'])
{
  $rss_content = safe($_GET['content']);
  
  switch ($rss_content)
  {
    case "blog":
      $rss_title = $CONF['blog_title'];
      $rss_desc = $CONF['blog_desc'];
      if ($_GET['author'])
      {
        if ($userTools->checkUsernameExists($_GET['author']))
        {
          $rss_user = $userTools->getUser($_GET['author']);
          $rss_id = $rss_user->id;
          $rss_author = $rss_user->username;
          $rss_title = $rss_user->blog_title;
          $rss_desc = $rss_user->blog_desc;
        }
      }
      break;
    case "podcast":
      $rss_title = $CONF['podcast_title'];
      $rss_desc = $CONF['podcast_desc'];
      break;
    default:
      break;
  }
  header("Content-Type: application/xml; charset=UTF-8");

  $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
  $rssfeed .= '<rss version="2.0">';
  $rssfeed .= '<channel>';
  $rssfeed .= '<title><![CDATA['.$rss_title.']]></title>';
  $rssfeed .= '<link>'.get_page_url($rss_content, $CONF).'/'.$rss_author.'</link>';
  $rssfeed .= '<description><![CDATA['.$rss_desc.']]></description>';
  $rssfeed .= '<language>en-us</language>';
  $rssfeed .= '<copyright>Copyright (C) 2013-'.date('Y', time()).' Teknik.io';
  if (!empty($rss_author))
  {
    $rssfeed .= 'and '.$rss_author;
  }
  $rssfeed .= '</copyright>';

  
  switch ($rss_content)
  {
    case "blog":
      // Grab blog posts //
      $blog_posts = $db->select('blog', "user_id=? ORDER BY date_posted DESC", array($rss_id));

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

      if ($posts)
      {
        foreach ($posts as $post)
        {
          $post_id = $post['id'];
          $author_id = $post['author_id'];
          $author = $userTools->get($author_id);
          $date = $post['date_posted'];
          $title = $post['title'];
          $tags = $post['tags'];
          $post = $post['post'];
          
          $rssfeed .= '<item>';
          $rssfeed .= '<title><![CDATA[' . $title . ']]></title>';
          $rssfeed .= '<description><![CDATA[' . $post . ']]></description>';
          $rssfeed .= '<link>' . get_page_url("blog", $CONF) .'/'. $author->username .'/'. $post_id . '</link>';
          $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O",strtotime($date)) . '</pubDate>';
          $rssfeed .= '</item>';
        }
      }
      break;
    case "podcast":      
      // Grab podcasts //
      $podcasts = $db->select('podcast', "1=? ORDER BY date_posted DESC", array(1));

      $pods = array();
      foreach ($podcasts as $pod)
      {
        if (!is_array($pod))
        {
          $pods = array($podcasts);
          break;
        }
        array_push($pods, $pod);
      }

      if ($pods)
      {
        foreach ($pods as $pod)
        {
          $post_id = $pod['id'];
          $user_id = $pod['user_id'];
          $date = $pod['date_posted'];
          $title = $pod['title'];
          $tags = $pod['tags'];
          $file = $pod['file_name'];
          $files = explode(',', $file);
          $post = $pod['description'];
          
          $rssfeed .= '<item>';
          $rssfeed .= '<title><![CDATA[' . $title . ']]></title>';
          $rssfeed .= '<description><![CDATA[' . $post . ']]></description>';
          
          foreach ($files as $filename)
          {
            $file_path = get_page_url("podcast", $CONF).'/Podcasts/'.$title.'/'.$filename;
            $direct_path = $CONF['podcast_dir'].$title.'/'.$filename;
            $file_type = mime_content_type($direct_path);
            $file_length = filesize($direct_path);
            $rssfeed .= '<enclosure url="'.$file_path.'" length="'.$file_length.'" type="'.$file_type.'" />';
          }
          
          $rssfeed .= '<link>' . get_page_url("podcast", $CONF) .'/' . $post_id . '</link>';
          $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O",strtotime($date)) . '</pubDate>';
          $rssfeed .= '</item>';
        }
      }

      break;
    default:
      break;
  }

  $rssfeed .= '</channel>';
  $rssfeed .= '</rss>';

  echo $rssfeed;
}
?>