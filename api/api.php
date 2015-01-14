<?php
include('../includes/config.php');
include('../paste/includes/libraries/geshi.php');
include('../paste/includes/paste.php');

header('Content-Type: application/json');
$jsonArray = array();

if (isset($_GET['component']))
{
  $component = strtolower($_GET['component']);
  switch ($component)
  {
    case 'upload':
      if (isset($_GET['action']))
      {
        $action = strtolower($_GET['action']);
        switch ($action)
        {
          case "post":
            $results = upload($_FILES, $CONF, $db);
            if (isset($results))
            {
              if (isset($_POST['get_delete_key']))
              {
                $name = $results['results']['file']['name'];
                $delete_key = generate_code($name, $CONF);
                $data = array(
                    "delete_key" => $delete_key
                );
                
                $post_id = $db->update($data, 'uploads', 'url=?', array($name));
                $results['results']['file'] = $results['results']['file'] + $data;
              }
              array_push($jsonArray, $results);
            }
            else
            {
              array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
            }
            break;
          default:
            array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
            break;
        }
      }
      else
      {
        array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
      }
      break;
    case 'paste':      
      if (isset($_POST['code']))
      {
        // Create our pastebin object
        $pastebin = new Pastebin($CONF, $db);
        /// Clean up older posts 
        $pastebin->doGarbageCollection();
        
        $id = $pastebin->doPost($_POST);
        $post = $pastebin->getPaste($id);
        array_push($jsonArray, array('results' => 
                                  array('paste' =>
                                    array(
                                      'id' => $id,
                                      'url' => get_page_url("p", $CONF).'/'.$id,
                                      'title' => $post['title'],
                                      'format' => $post['format'],
                                      'expiration' => $post['expires'],
                                      'password' => $post['password']
                                    )
                                  )
                                )
                              );
      }
      else
      {
        array_push($jsonArray, array('error' => $CONF['errors']['NoPaste']));
      }
      break;
    case 'ricehalla':
      if (isset($_GET['action']))
      {
        $action = strtolower($_GET['action']);
        switch ($action)
        {
          case "post":
            if (isset($_POST['username']))
            {
              if (isset($_POST['password']))
              {
                $username = $_POST['username'];
                $password = hashPassword($_POST['password'], $CONF);
                if ($userTools->login($username, $password, false))
                {
                  $user = unserialize($_SESSION['user']);
                  
                  $results = upload($_FILES, $CONF, $db);
                  if (isset($results))
                  {
                    $filename = $results['results']['file']['name'];
                    $file_path  = $CONF['upload_dir'] . $filename;
                    $thumbnail_path  = $CONF['upload_dir'] . 'thumbnails/150_150_' . $filename;
                    $date_added = date("Y-m-d H:i:s",time());
                    $file_db = $db->select('uploads', "filename=? LIMIT 1", array($filename));
                    
                    if (file_exists($file_path) && $file_db)
                    {
                      $file_type = $file_db['type'];
                      $pattern = "/^(image)\/(.*)$/";
                      if(preg_match($pattern, $file_type))
                      {
                        $resizeObj = new resize($file_path);
                        // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
                        $resizeObj->resizeImage(150, 150, 'auto');
                        $resizeObj->saveImage($thumbnail_path, 70);
                        $data = array(
                                  "url" => $filename,
                                  "user_id" => $user->id,
                                  "date_added" => $date_added
                              );
                        $row_id = $db->insert($data, 'ricehalla');
                        $data = array(
                                  "table_name" => 'ricehalla',
                                  "row_id" => $row_id,
                                  "user_id" => $user->id,
                                  "points" => 1
                              );
                        $db->insert($data, 'votes');
                
                        array_push($jsonArray, array('image' =>
                                                    array(
                                                      'id' => $row_id,
                                                      'url' => get_page_url("ricehalla", $CONF).'/'.$row_id,
                                                      'image_src' => get_page_url("u", $CONF).'/'.$filename,
                                                      'votes' => 1,
                                                      'owner' => $user->username,
                                                      'date_posted' => $date_added,
                                                      'tags' => array()
                                                    )
                                                  )
                                                );
                      }
                      else
                      {
                        array_push($jsonArray, array('error' => $CONF['errors']['InvFile']));
                      }
                    }
                    else
                    {
                      array_push($jsonArray, array('error' => $CONF['errors']['NoFile']));
                    }
                  }
                  else
                  {
                    array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
                  }
                }
                else
                {
                  array_push($jsonArray, array('error' => $CONF['errors']['InvCred']));
                }
              }
              else
              {
                array_push($jsonArray, array('error' => $CONF['errors']['NoPass']));
              }
            }
            else
            {
              array_push($jsonArray, array('error' => $CONF['errors']['NoUser']));
            }
            break;
          case "get":
            $filter = "votes.table_name=?";
            $filter_content = array("ricehalla");
            $order_by = "TotalRank";
            $order = "DESC";
            $limit = "";
            if (isset($_POST['id']))
            {
              $filter .= " AND ricehalla.id=?";
              array_push($filter_content, $_POST['id']);
            }
            if (isset($_POST['owner']))
            {
              $user_id = $userTools->getUser($_POST['owner'])->id;
              $filter .= " AND ricehalla.user_id=?";
              array_push($filter_content, $user_id);
            }
            if (isset($_POST['order']))
            {
              if (strtolower($_POST['order']) == "asc")
              {
                $order = "ASC";
              }
            }
            if (isset($_POST['order_by']))
            {
              switch ($_POST['order_by'])
              {
                case 'id':
                  $order_by = "ricehalla.id";
                  break;
                case 'owner':
                  $order_by = "ricehalla.user_id";
                  break;
                case 'date':
                  $order_by = "ricehalla.date_added";
                  break;
                default:
                  break;
              }
            }
            if (isset($_POST['limit']))
            {
              if (is_numeric($_POST['limit']))
              {
                $limit = " LIMIT ".$_POST['limit'];
              }
            }
            $Results = $db->select_raw('ricehalla', "INNER JOIN votes ON ricehalla.id=votes.row_id WHERE ".$filter." GROUP BY votes.row_id ORDER BY ".$order_by." ".$order.$limit, $filter_content, 'ricehalla.url, ricehalla.user_id, ricehalla.id, ricehalla.tags, votes.points, sum(votes.points) TotalPoints, COUNT(votes.id) as TotalVotes, (sum(votes.points) / COUNT(votes.id)) * abs(sum(votes.points)) as TotalRank, ricehalla.date_added');
            if ($Results)
            {
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
              
              $result_array = array();
              // Generate object for each result
              foreach ($result_list as $result)
              {
                $id = $result['id'];
                $rank = multi_array_search($rank_list, array('id' => $id))[0] + 1;
                $username = $userTools->get($result['user_id'])->username;
                $image_src = $result['url'];
                $date_posted = $result['date_added'];
                $user_vote = $result['TotalPoints'];
                $tags = explode(',', $result['tags']);
                
                array_push($result_array, array('image' =>
                                            array(
                                              'id' => $id,
                                              'url' => get_page_url("ricehalla", $CONF).'/'.$id,
                                              'image_src' => get_page_url("u", $CONF).'/'.$image_src,
                                              'rank' => $rank,
                                              'votes' => $user_vote,
                                              'owner' => $username,
                                              'date_posted' => $date_posted,
                                              'tags' => $tags
                                            )
                                          )
                                        );
              }
              array_push($jsonArray, array('results' => $result_array));
            }
            else
            {
              array_push($jsonArray, array('error' => $CONF['errors']['NoImages']));
            }
            break;
          default:
            array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
            break;
        }
      }
      else
      {
        array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
      }
      break;
    default:
      array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
      break;
  }
}
else
{
  array_push($jsonArray, array('error' => $CONF['errors']['InvRequest']));
}

echo json_encode($jsonArray);
?>