<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <?php
      if ($user->group == "Founder" || $user->group == "Admin" || $user->group == "Moderator")
      {
        $support_msgs = $db->select('support', "1=? ORDER BY date_added DESC", array("1"));
        
        $support_msg_list = array();
        foreach ($support_msgs as $support_msg)
        {
          if (!is_array($support_msg))
          {
            $support_msg_list = array($support_msgs);
            break;
          }
          array_push($support_msg_list, $support_msg);
        }
      ?>
      <h2 class="text-center"><strong>Support Messages</strong></h2>
        <hr>
        <div class="row">
          <div class="col-sm-2">
            <h4><strong>Date</strong></h4>
          </div>
          <div class="col-sm-2">
            <h4><strong>Sender</strong></h4>
          </div>
          <div class="col-sm-3">
            <h4><strong>Subject</strong></h4>
          </div>
          <div class="col-sm-5">
            <h4><strong>Message</strong></h4>
          </div>
        </div>
        <?php
          foreach ($support_msgs as $msg)
          {
        ?>
          <div class="row">
            <div class="col-sm-2">
              <p><?php echo $msg['date_added']; ?></p>
            </div>
            <div class="col-sm-2">
              <p><a href="mailto:<?php echo $msg['email']; ?>"><?php echo $msg['name']; ?></a></p>
            </div>
            <div class="col-sm-3">
              <p><?php echo $msg['subject']; ?></p>
            </div>
            <div class="col-sm-5">
              <p><?php echo $msg['message']; ?></p>
            </div>
          </div>
        <?php
          }
      }
      ?>
    </div>
  </div>
</div>