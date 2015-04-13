<!DOCTYPE html>
<html>
  <head>
    <?php ob_start(); ?>
    <title>{title_holder}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="<?php echo $CONF['sitedescription']; ?>" />
    <meta name="author" content="<?php echo $CONF['siteowner']; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/img/favicon.ico" type="image/x-icon" />

  <?php
    $cssFiles = array();
    $jsFiles = array();
    
    // Global CSS Files //
    array_push($cssFiles, 'font-awesome.min.css', 'common.css');
    
    // Global JS Files //
    array_push($jsFiles, 'jquery/1.10.2/jquery.min.js', 'common.js', 'bootstrap/bootstrap.min.js', 'bootstrap/select/bootstrap-select.js');
    
    // Service Specific Files //
    switch ($CONF['page'])
    {
      case "home":
        array_push($cssFiles, 'bootstrap-markdown.min.css');
        array_push($jsFiles, 'bootbox/bootbox.min.js', 
                    'PageDown/Markdown.Converter.js', 
                    'PageDown/Markdown.Sanitizer.js', 
                    'bootstrap/markdown/bootstrap-markdown.js',
                    'jquery/jquery.blockUI.js',
                    'profile.js');
        break;
      case "admin":
        array_push($jsFiles, 'admin.js');
        break;
      case "about":
        array_push($cssFiles, 'jquery.cointipper.min.css');
        array_push($jsFiles, 'jquery/jquery.cointipper-pack.js');
        break;
      case "blog":
        array_push($cssFiles, 'bootstrap-markdown.min.css');
        array_push($jsFiles, 
                    'bootbox/bootbox.min.js', 
                    'PageDown/Markdown.Converter.js', 
                    'PageDown/Markdown.Sanitizer.js', 
                    'bootstrap/markdown/bootstrap-markdown.js',
                    'ocupload/1.1.2/ocupload.js',
                    'blog.js');
        break;
      case "contact":
        array_push($jsFiles, 'contact.js');
        break;
      case "git":      
        $url = curPageURL();
        $pattern = "/^(.*)((\/zipball\/)|(\/tarball\/)|(\/raw\/))(.*)$/";
        if(!preg_match($pattern, $url))
        {
          array_push($jsFiles, 
                      //'raphael/raphael.js',
                      'showdown/showdown.js',
                      'codemirror/codemirror.js');
        }
        array_push($jsFiles, 'git.js');
        break;
      case "help":      
        array_push($cssFiles, 'jquery.tocify.css');
        array_push($jsFiles,
                    'jquery/1.10.2/jquery-ui.widgets.js',
                    'jquery/jquery.tocify.min.js',
                    'help.js');
        break;
      case "pod":
      case "podcast":
        array_push($cssFiles, 'bootstrap-markdown.min.css', 'audioplayer.css');
        array_push($jsFiles, 
                    'bootbox/bootbox.min.js', 
                    'PageDown/Markdown.Converter.js', 
                    'PageDown/Markdown.Sanitizer.js', 
                    'bootstrap/markdown/bootstrap-markdown.js',
                    'jquery/1.10.2/jquery-ui.widgets.js',
                    'jquery/jquery.iframe-transport.js',
                    'jquery/jquery.fileupload.js',
                    'audioplayer/audioplayer.min.js',
                    'podcast.js');
        break;
      case "ricehalla":
      case "desktops":
        array_push($cssFiles, 'bootstrap-tags.css', 'bootstrap-modal.css');
        array_push($jsFiles, 
                    'bootbox/bootbox.min.js',
                    'ocupload/1.1.2/ocupload.js',
                    'jquery/jquery.zoom.min.js',
                    'bootstrap/modal/bootstrap-modalmanager.js',
                    'bootstrap/modal/bootstrap-modal.js',
                    'bootstrap/tags/bootstrap-tags.js',
                    'ricehalla.js');
        break;
      case "upload":
      case "u":
        array_push($cssFiles, 'dropzone.css');
        array_push($jsFiles, 
                    'dropzone/dropzone.js',
                    'upload.js',
                    'bootbox/bootbox.min.js');
        break;
      case "paste":
      case "p":
        array_push($jsFiles, 'paste.js');
        break;
      case "server":
        array_push($jsFiles,
                    'sorttable/sorttable.js',
                    'transparency/transparency.min.js',
                    'server.js');
      case "w":
      case "walls":
        array_push($cssFiles, 'blueimp-gallery.min.css', 'bootstrap-image-gallery.min.css');
        array_push($jsFiles,
                    'blueimp/blueimp-gallery.min.js',
                    'jquery/jquery.blueimp-gallery.min.js',
                    'bootstrap/image-gallery/bootstrap-image-gallery.min.js',
                    'walls.js');
        break;
    }
    
    // Check to see if the JS files have changed //
    $jsChanged = false;
    $jsCacheFile = dirname(__FILE__).'/cache/js_cache_'.$CONF['page'].'.txt';
    if (file_exists($jsCacheFile))
    {
      $cache_arr = explode('|', file_get_contents($jsCacheFile));
      $time_str = $cache_arr[0];
      $files_arr = explode(',', $cache_arr[1]);
      if (!empty($time_str) && files_arr)
      {
        $time = strtotime($time_str);
        foreach($jsFiles as $file)
        {
          if (!in_array($file, $files_arr))
          {
            $jsChanged = true;
            break;
          }
          if(filemtime(dirname(__FILE__)."/js/".$file)>$time)
          {
            $jsChanged = true;
            break;
          }
        } 
      }
      else
      {
        $jsChanged = true;
      }
    }
    else
    {
      $jsChanged = true;
    }

    // If they have changed, minify them and 
    if($jsChanged)
    {
      file_put_contents($jsCacheFile, date("Y-m-d H:i:s",time()).'|'.implode(",", $jsFiles)); 
      $js = ""; 
      foreach($jsFiles as $file)
      {
        $js .= \JShrink\Minifier::minify(file_get_contents(dirname(__FILE__).'/js/'.$file)); 
      } 

      file_put_contents(dirname(__FILE__)."/cache/".$CONF['page'].".teknik.min.js", $js); 
    }
    
    
    // Check to see if the CSS files have changed //
    $cssChanged = false;
    $cssCacheFile = dirname(__FILE__).'/cache/css_cache_'.$CONF['page'].'.txt';
    if (file_exists($cssCacheFile) && file_exists(dirname(__FILE__)."/cache/".$CONF['page'].".teknik.min.css"))
    {
      $cache_arr = explode('|', file_get_contents($cssCacheFile));
      $time_str = $cache_arr[0];
      $files_arr = explode(',', $cache_arr[1]);
      if (!empty($time_str) && files_arr)
      {
        $time = strtotime($time_str);
        foreach($cssFiles as $file)
        {
          if (!in_array($file, $files_arr))
          {
            $cssChanged = true;
            break;
          }
          if(filemtime(dirname(__FILE__)."/css/".$file)>$time)
          {
            $cssChanged = true;
            break;
          }
        } 
      }
      else
      {
        $cssChanged = true;
      }
    }
    else
    {
      $cssChanged = true;
    }
       
    // If they have changed, minify them and 
    if($cssChanged)
    {
      file_put_contents($cssCacheFile, date("Y-m-d H:i:s",time()).'|'.implode(",", $cssFiles)); 

      $css_str = ""; 
      foreach ($cssFiles as $file)
      {
        $css_str .= file_get_contents(dirname(__FILE__)."/css/".$file)."\r\n"; 
      }
      $final_css = compress($css_str);
      
      file_put_contents(dirname(__FILE__)."/cache/".$CONF['page'].".teknik.min.css", $css_str); 
    }
    
    /*
    foreach ($cssFiles as $file)
    {
    ?>
      <link href="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/css/<?php echo $file; ?>" rel="stylesheet" />
    <?php
    }*/
  ?>
  <link  href="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/css/bootstrap.<?php echo $CONF['theme']; ?>.min.css" rel="stylesheet" />
  <link href="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/cache/<?php echo $CONF['page']; ?>.teknik.min.css" rel="stylesheet" />
  <script src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/cache/<?php echo $CONF['page']; ?>.teknik.min.js"></script>
  
  <?php
    // Theme specific files    
    $cssTheme = array();
    $jsTheme = array();
    
    switch ($CONF['theme'])
    {
      case "default":
        array_push($cssTheme, 'bootstrap.default.min.css');
        break;
      case "darkly":
        array_push($cssTheme, 'bootstrap.darkly.min.css');
        break;
      case "flat-ui":
        array_push($cssTheme, 'bootstrap.default.min.css', 'flat-ui.min.css');
        array_push($jsTheme, 'respond/respond.min.js', 'flat-ui/flat-ui.min.js');
        break;
      case "flatly":
        array_push($cssTheme, 'bootstrap.flatly.min.css');
        break;
      case "lumen":
        array_push($cssTheme, 'bootstrap.lumen.min.css');
        break;
      case "material":
        array_push($cssTheme, 'bootstrap.default.min.css', 'material.css');
        array_push($jsTheme, 'material/material.js');
        break;
      case "paper":
        array_push($cssTheme, 'bootstrap.paper.min.css');
        break;
      case "sandstone":
        array_push($cssTheme, 'bootstrap.sandstone.min.css');
        break;
      case "simplex":
        array_push($cssTheme, 'bootstrap.samplex.min.css');
        break;
      case "superhero":
        array_push($cssTheme, 'bootstrap.superhero.min.css');
        break;
    }
    
    foreach ($cssTheme as $file)
    {
    ?>
      <link href="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/css/<?php echo $file; ?>" rel="stylesheet" />
    <?php
    }
    
    foreach ($jsTheme as $file)
    {
    ?>
      <script src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/js/<?php echo $file; ?>"></script>
    <?php
    }
  ?>
    
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/js/html5/html5.js"></script>
    <![endif]-->
  </head>
  <?php flush(); ?>
  <body data-twttr-rendered="true">
    <div id="wrap">
      <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo get_page_url("home", $CONF); ?>"><img src="<?php echo get_page_url("cdn", $CONF); ?>/<?php echo $CONF['template']; ?>/img/logo-black.svg" height="20px" alt="Teknik"></a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="<?php echo get_active("home", $CONF); ?>"><a href="<?php echo get_page_url("home", $CONF); ?>">Home</a></li>
              <li class="<?php echo get_active("about", $CONF); ?>"><a href="<?php echo get_page_url("about", $CONF); ?>">About</a></li>
              <li class="divider-vertical"></li>
              
              <li class="dropdown">
                <a href="#" id="services_menu" class="dropdown-toggle" data-toggle="dropdown">Services <strong class="caret"></strong></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="services_menu">                
                  <li class="<?php echo get_active("blog", $CONF); ?>">
                    <a href="<?php echo get_page_url("blog", $CONF); ?>">Blog</a>
                  </li>
                  <li class="<?php echo get_active("podcast", $CONF); echo get_active("pod", $CONF); ?>">
                    <a href="<?php echo get_page_url("podcast", $CONF); ?>">Podcast</a>
                  </li>
                  <li class="divider"></li>
                  <li class="<?php echo get_active("upload", $CONF); echo get_active("u", $CONF); ?>">
                    <a href="<?php echo get_page_url("upload", $CONF); ?>">Upload</a>
                  </li>
                  <li class="<?php echo get_active("paste", $CONF); echo get_active("p", $CONF); ?>">
                    <a href="<?php echo get_page_url("paste", $CONF); ?>">Paste</a>
                  </li>
                  <li class="<?php echo get_active("git", $CONF); ?>">
                    <a href="<?php echo get_page_url("git", $CONF); ?>">Git</a>
                  </li>
                  <li class="<?php echo get_active("mail", $CONF); ?>">
                    <a href="<?php echo get_page_url("mail", $CONF); ?>" target="_blank">Mail</a>
                  </li>
                  <li class="<?php echo get_active("mumble", $CONF); ?>">
                    <a href="mumble://mumble.<?php echo $CONF['host']; ?>:64738/?version=1.2.5" target="_blank">Mumble</a>
                  </li>
                  <li class="divider"></li>
                  <li class="<?php echo get_active("ricehalla", $CONF); echo get_active("desktops", $CONF); ?>">
                    <a href="<?php echo get_page_url("ricehalla", $CONF); ?>">Ricehalla</a>
                  </li>
                </ul>
              </li>
              <li class="<?php echo get_active("contact", $CONF); ?>">
                <a href="<?php echo get_page_url("contact", $CONF); ?>">Contact</a>
              </li>
              <li class="<?php echo get_active("help", $CONF); ?>">
                <a href="<?php echo get_page_url("help", $CONF); ?>">Help</a>
              </li>
            </ul>
            <ul class="nav navbar-nav pull-right">
            
            <?php
              if ($logged_in)
              {
            ?>
              <li class="dropdown">
                <a href="#" id="user_menu" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user->username; ?> <strong class="caret"></strong></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="user_menu">
                  <li>
                    <a href="<?php echo get_page_url("home", $CONF); ?>/<?php echo $user->username; ?>">Profile</a>
                  </li>
                  <li>
                    <a href="<?php echo get_page_url("blog", $CONF); ?>/<?php echo $user->username; ?>">Blog</a>
                  </li>
                  <?php
                  if ($user->group == "Founder" || $user->group == "Admin" || $user->group == "Moderator")
                  {
                  ?>
                    <li>
                      <a href="<?php echo get_page_url("admin", $CONF); ?>">Administration</a>
                    </li>
                  <?php
                  }
                  ?>
                  <li>
                    <a href="#" id="logout">Logout</a>
                  </li>
                </ul>
              </li>
            <?php
              }
              else
              {
            ?>
              <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="reg_dropdown">Sign Up <strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                  <form role="form" id="registrationForm" action="#" method="post" accept-charset="UTF-8">
                    <div id="reg_err"></div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="reg_username" placeholder="Username" name="reg_username" maxlength="30" />
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" id="reg_password" placeholder="Password" name="reg_password" />
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" id="reg_password_confirm" placeholder="Confirm" name="reg_password_confirm" />
                    </div>
                    <div class="form-group text-center">
                      <button class="btn btn-primary" id="reg_submit" type="submit" name="submit">Sign Up</button>
                    </div>
                  </form>
                </div>
              </li>
              
              <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="login_dropdown">Sign In <strong class="caret"></strong></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                  <form role="form" id="loginForm" action="#" method="post" accept-charset="UTF-8">
                    <div id="login_err"></div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="login_username" placeholder="Username" name="login_username" />
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" id="login_password" placeholder="Password" name="login_password" />
                    </div>
                    <div class="checkbox">
                      <label>
                        <input id="login_remember_me" type="checkbox" name="login_remember_me" /> Remember Me
                      </label>
                    </div>
                    <div class="form-group text-center">
                      <button class="btn btn-primary" id="login_submit" type="submit" name="submit">Sign In</button>
                    </div>
                  </form>
                </div>
              </li>
            <?php
              }
            ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12 text-center">
            <div id="top_msg"></div>
          </div>
        </div>
      </div>
      <!-- NoScript Alert -->
      <noscript>
        <div class="container">
          <div class="row">
            <div class="col-xs-12 text-center">
              <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <span><strong>Notice: </strong> JavaScript is not enabled. To experience the site at it's best, <a href="http://enable-javascript.com/" class="alert-link">please enable JavaScript</a>.</span>
              </div>
            </div>
          </div>
        </div>
      </noscript>
