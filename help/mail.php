<div class="row">
  <h2><b>Mail</b></h2>
  <hr>
  <h3>Mail Server Settings</h3>
  <p>
      At registration, each user is given an email address with <b>1 GB</b> of storage space. 
      You can either access your email via the <a href="<?php echo get_page_url("mail", $CONF); ?>" target="_blank">Web Client</a> or by using a client of your choosing with support for IMAP or POP3.
  </p>
  <div class="row">
    <div class="col-md-12">
      <ul class="list-group">
        <li class="list-group-item text-center"><h4>Outlook</h4></li>
        <br />
        <div class="col-sm-6">
          <ul class="list-group">
            <li class="list-group-item text-center">User Information</li>
            <li class="list-group-item">Email Address:<div class="pull-right"><b>[username]@<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item text-center">Server Information</li>
            <li class="list-group-item">Incoming Server:<div class="pull-right"><b>mail.<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Outgoing Server:<div class="pull-right"><b>mail.<?php echo $CONF['host']; ?></b></div></li>
          </ul>
        </div>
        <div class="col-sm-6">
          <ul class="list-group">
            <li class="list-group-item text-center">Logon Information</li>
            <li class="list-group-item">Username:<div class="pull-right"><b>[username]@<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Password:<div class="pull-right"><b>[password]</b></div></li>
            <li class="list-group-item text-center">More Settings</li>
            <li class="list-group-item">Requires Authentication:<div class="pull-right"><b>Both</b></div></li>
            <li class="list-group-item">Incoming Server (IMAP):<div class="pull-right"><b>143 (993 SSL)</b></div></li>
            <li class="list-group-item">Outgoing Server (SMTP):<div class="pull-right"><b>25 (465 SSL)</b></div></li>
          </ul>
        </div>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <ul class="list-group">
        <li class="list-group-item text-center"><h4>Thunderbird</h4></li>
        <br />
        <div class="col-sm-6">
          <ul class="list-group">
            <li class="list-group-item text-center">Server Settings</li>
            <li class="list-group-item">Server Name:<div class="pull-right"><b>mail.<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Port:<div class="pull-right"><b>143 (993 SSL)</b></div></li>
            <li class="list-group-item">User Name:<div class="pull-right"><b>[username]@<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Connection Security:<div class="pull-right"><b>None (SSL/TLS)</b></div></li>
            <li class="list-group-item">Authentication method:<div class="pull-right"><b>Password</b></div></li>
          </ul>
        </div>
        <div class="col-sm-6">
          <ul class="list-group">
            <li class="list-group-item text-center">Outgoing Server (SMTP)</li>
            <li class="list-group-item">Server Name:<div class="pull-right"><b>mail.<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Port:<div class="pull-right"><b>25 (465 SSL)</b></div></li>
            <li class="list-group-item">User Name:<div class="pull-right"><b>[username]@<?php echo $CONF['host']; ?></b></div></li>
            <li class="list-group-item">Connection Security:<div class="pull-right"><b>None (SSL/TLS)</b></div></li>
            <li class="list-group-item">Authentication method:<div class="pull-right"><b>Password</b></div></li>
          </ul>
        </div>
      </ul>
    </div>
  </div>
</div>