<?php

/**
 * GitList 0.3
 * https://github.com/klaussilveira/gitlist
 */

// Set the default timezone for systems without date.timezone set in php.ini
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

require_once('../includes/config.php');

$url = curPageURL();
$pattern = "/^(.*)((\/zipball\/)|(\/tarball\/)|(\/raw\/))(.*)$/";
if(!preg_match($pattern, $url))
{
  include('../templates/'.$CONF['template'].'/header.php');

  set_page_title("Teknik Git");
}

if (php_sapi_name() == 'cli-server' && file_exists(substr($_SERVER['REQUEST_URI'], 1))) {
    return false;
}

if (!is_writable(__DIR__ . DIRECTORY_SEPARATOR . 'cache')) {
    die(sprintf('The "%s" folder must be writable for GitList to run.', __DIR__ . DIRECTORY_SEPARATOR . 'cache'));
}

require 'vendor/autoload.php';

$config = GitList\Config::fromFile('config.ini');
$config->set('app', 'clone_url', 'git://teknik.io/');
if ($_GET['user'])
{
  if ($userTools->checkUsernameExists($_GET['user']))
  {
    if (is_dir("G:\\Repositories\\u\\".$_GET['user']))
    {
      $_SERVER['HTTP_X_ORIGINAL_URL'] = str_replace("/u/".$_GET['user'], "", $_SERVER['HTTP_X_ORIGINAL_URL']);
      $config->set('git', 'repositories', array('G:\\Repositories\\u\\'.$_GET['user']));
      $config->set('git', 'hidden', array(''));
      $config->set('app', 'path_prefix', '/u/'.$_GET['user']);
      $config->set('app', 'clone_url', 'git://teknik.io/u/'.$_GET['user'].'/');
      $app = require 'boot.php';
      $app->run();
    }
    else
    {
    ?>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>That user has no repositories</h2>
      </div>
    </div>
    <?php
    }
  }
  else
  {
  ?>
  <div class="row">
    <div class="col-sm-12 text-center">
      <h2>That user doesn't exist</h2>
    </div>
  </div>
  <?php
  }
}
else
{
  $app = require 'boot.php';
  $app->run();
}

if(!preg_match($pattern, $url))
{
  include('../templates/'.$CONF['template'].'/footer.php');
}
?>
