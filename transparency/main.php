<?php
  $transactions_bills = $db->select('transactions', "trans_type=? ORDER BY date_posted DESC", array("Bill"));
  
  $transaction_bill_list = array();
  foreach ($transactions_bills as $transaction)
  {
    if (!is_array($transaction))
    {
      $transaction_bill_list = array($transactions_bills);
      break;
    }
    array_push($transaction_bill_list, $transaction);
  }
  
  $transactions_one_time = $db->select('transactions', "trans_type=? ORDER BY date_posted DESC", array("One-Time"));
  
  $transaction_one_time_list = array();
  foreach ($transactions_one_time as $transaction)
  {
    if (!is_array($transaction))
    {
      $transaction_one_time_list = array($transactions_one_time);
      break;
    }
    array_push($transaction_one_time_list, $transaction);
  }
  
  $transactions_donations = $db->select('transactions', "trans_type=? ORDER BY date_posted DESC", array("Donation"));
  
  $transaction_donation_list = array();
  foreach ($transactions_donations as $transaction)
  {
    if (!is_array($transaction))
    {
      $transaction_donation_list = array($transactions_donations);
      break;
    }
    array_push($transaction_donation_list, $transaction);
  }
  
  $total_donations = $db->select('transactions', "trans_type=? GROUP BY currency", array("Donation"), "sum(amount) TotalAmount, currency");
  $total_donation_list = array();
  foreach ($total_donations as $total)
  {
    if (!is_array($total))
    {
      $total_donation_list = array($total_donations);
      break;
    }
    array_push($total_donation_list, $total);
  }
  $total_bills = $db->select('transactions', "trans_type=? GROUP BY currency", array("Bill"), "sum(amount) TotalAmount, currency");
  $total_bill_list = array();
  foreach ($total_bills as $total)
  {
    if (!is_array($total))
    {
      $total_bill_list = array($total_bills);
      break;
    }
    array_push($total_bill_list, $total);
  }
  $total_one_time = $db->select('transactions', "trans_type=? GROUP BY currency", array("One-Time"), "sum(amount) TotalAmount, currency");
  $total_one_time_list = array();
  foreach ($total_one_time as $total)
  {
    if (!is_array($total))
    {
      $total_one_time_list = array($total_one_time);
      break;
    }
    array_push($total_one_time_list, $total);
  }
  $total_net = $db->select('transactions', "1=? GROUP BY currency", array("1"), "sum(amount) TotalAmount, currency");
  $total_net_list = array();
  foreach ($total_net as $total)
  {
    if (!is_array($total))
    {
      $total_net_list = array($total_net);
      break;
    }
    array_push($total_net_list, $total);
  }
  
  $takedowns = $db->select('takedowns', "1=? ORDER BY date_requested DESC", array("1"));
  
  $takedown_list = array();
  foreach ($takedowns as $takedown)
  {
    if (!is_array($takedown))
    {
      $takedown_list = array($takedowns);
      break;
    }
    array_push($takedown_list, $takedown);
  }

  $total_size = $db->select('uploads', "1=? ORDER BY upload_date DESC", array("1"), "sum(filesize) TotalSize");
  $uploads = $db->select('uploads', "1=?", array("1"));
  
  $upload_list = array();
  foreach ($uploads as $upload)
  {
    if (!is_array($upload))
    {
      $upload_list = array($uploads);
      break;
    }
    array_push($upload_list, $upload);
  }
  
  $pastes = $db->select('paste', "1=? ORDER BY pid DESC LIMIT 1", array("1"));
  
  $paste_list = array();
  foreach ($pastes as $paste)
  {
    if (!is_array($paste))
    {
      $paste_list = array($pastes);
      break;
    }
    array_push($paste_list, $paste);
  }
  
  $users = $db->select('users', "1=?", array("1"));
  
  $user_list = array();
  foreach ($users as $use)
  {
    if (!is_array($use))
    {
      $user_list = array($users);
      break;
    }
    array_push($user_list, $use);
  }
?>
<div class="container">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <h2 class="text-center"><b>Behind the Scenes</b></h2>
        <hr>
        <p>
          Here you can view all of Teknik's financial information, takedown requests and the actions we took, as well as some general statistics for the site's services.
        </p>
        <p>
          If you would like to request additional information about Teknik, please feel free to contact us through our <a href="<?php echo get_page_url("contact", $CONF); ?>" target="_blank">Contact Form</a> or by emailing us at <a href="mailto:support@<?php echo $CONF['host']; ?>">support@<?php echo $CONF['host']; ?></a>.
        </p>
        <p>
          Want to make a donation?  Visit our <a href="<?php echo get_page_url("about", $CONF); ?>" target="_blank">About Page</a> and choose a donation method at the bottom.
        </p>
        <br />
      <h2 class="text-center"><b>Statistics</b></h2>
        <hr>
        <div class="row">
          <div class="col-sm-6">
            <h3>Uploads</h3>
              <p>Number of Uploads: <?php echo count($upload_list); ?></p>
              <p>Total Size: <?php echo bytesToSize($total_size['TotalSize']); ?></p>
          </div>
          <div class="col-sm-6">
            <h3>Pastes</h3>
              <p>Number of Pastes: <?php echo $paste_list[0]['pid']; ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <h3>Users</h3>
              <p>Number of Users: <?php echo count($user_list); ?></p>
          </div>
          <div class="col-sm-6">
            <h3>Usage</h3>
              <p>No Usage Reports</p>
          </div>
        </div>
        <br />
      <h2 class="text-center"><b>Transactions</b></h2>
        <hr>
        <h3 class="text-center">Total Amounts</h3>
        <div class="row">
          <div class="col-sm-3 text-center">
            <h4>Donations</h4>
            <?php
              foreach ($total_donation_list as $total)
              {
                echo "<p>".round($total['TotalAmount'], 2)." ".$total['currency'];
              }
            ?>
          </div>
          <div class="col-sm-3 text-center">
            <h4>Bills</h4>
            <?php
              foreach ($total_bill_list as $total)
              {
                echo "<p>".round($total['TotalAmount'], 2)." ".$total['currency'];
              }
            ?>
          </div>
          <div class="col-sm-3 text-center">
            <h4>One-Time Payments</h4>
            <?php
              foreach ($total_one_time_list as $total)
              {
                echo "<p>".round($total['TotalAmount'], 2)." ".$total['currency'];
              }
            ?>
          </div>
          <div class="col-sm-3 text-center">
            <h4>Net Profit</h4>
            <?php
              foreach ($total_net_list as $total)
              {
                echo "<p>".round($total['TotalAmount'], 2)." ".$total['currency'];
              }
            ?>
          </div>
        </div>
        <?php
        if ($transactions_bills)
        {
        ?>
        <h3>Bills</h3>
          <?php
            $current_month = null;
            $first_event = true;
            foreach ($transaction_bill_list as $transaction)
            {
              $transaction_date = (isset($transaction['date_posted'])) ? $transaction['date_posted'] : "";
              $transaction_reason = (isset($transaction['reason'])) ? $transaction['reason'] : "";
              $transaction_amount = (isset($transaction['amount'])) ? $transaction['amount'] : "";
              $transaction_currency = (isset($transaction['currency'])) ? $transaction['currency'] : "";
              
              $new_month_tag = false;
              if ($current_month != date("F",strtotime($transaction_date)) || $current_year != date("Y",strtotime($transaction_date)))
              {
                $new_month_tag = true;
              }
              $current_month = date("F",strtotime($transaction_date));
              $current_year = date("Y",strtotime($transaction_date));
            ?>
            <?php if (!$first_event && $new_month_tag) { ?>
              </div>
            <?php } ?>
            <?php if ($new_month_tag) { ?>
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#bills-<?php echo $current_month.'-'.$current_year; ?>"><?php echo $current_month.', '.$current_year; ?></button>
                </div>
              </div>
              <br />
              <div id="bills-<?php echo $current_month.'-'.$current_year; ?>" class="collapse">
                <div class="row">
                  <div class="col-sm-2">
                    <h4><strong>Date</strong></h4>
                  </div>
                  <div class="col-sm-2">
                    <h4><strong>Amount</strong></h4>
                  </div>
                  <div class="col-sm-8">
                    <h4><strong>Reason for Transaction</strong></h4>
                  </div>
                </div>
            <?php } ?>
                <div class="row">
                  <div class="col-sm-2">
                    <?php echo $transaction_date; ?>
                  </div>
                  <div class="col-sm-2">
                    <?php echo $transaction_amount." <var>".$transaction_currency."</var>"; ?>
                  </div>
                  <div class="col-sm-8">
                    <?php echo $transaction_reason; ?>
                  </div>
                </div>
                <br />
            <?php
              $first_event = false;
            }
            ?>
            </div>
            <?php
        }
        
        if ($transactions_one_time)
        {
        ?>
        <h3>One-Time Payments</h3>
          <?php
            $current_month = null;
            $first_event = true;
            foreach ($transaction_one_time_list as $transaction)
            {
              $transaction_date = (isset($transaction['date_posted'])) ? $transaction['date_posted'] : "";
              $transaction_reason = (isset($transaction['reason'])) ? $transaction['reason'] : "";
              $transaction_amount = (isset($transaction['amount'])) ? $transaction['amount'] : "";
              $transaction_currency = (isset($transaction['currency'])) ? $transaction['currency'] : "";
              
              $new_month_tag = false;
              if ($current_month != date("F",strtotime($transaction_date)) || $current_year != date("Y",strtotime($transaction_date)))
              {
                $new_month_tag = true;
              }
              $current_month = date("F",strtotime($transaction_date));
              $current_year = date("Y",strtotime($transaction_date));
            ?>
            <?php if (!$first_event && $new_month_tag) { ?>
              </div>
            <?php } ?>
            <?php if ($new_month_tag) { ?>
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#One-Time-<?php echo $current_month.'-'.$current_year; ?>"><?php echo $current_month.' - '.$current_year; ?></button>
                </div>
              </div>
              <br />
              <div id="One-Time-<?php echo $current_month.'-'.$current_year; ?>" class="collapse">
                <div class="row">
                  <div class="col-sm-2">
                    <h4><strong>Date</strong></h4>
                  </div>
                  <div class="col-sm-2">
                    <h4><strong>Amount</strong></h4>
                  </div>
                  <div class="col-sm-8">
                    <h4><strong>Reason for Transaction</strong></h4>
                  </div>
                </div>
            <?php } ?>
                <div class="row">
                  <div class="col-sm-2">
                    <?php echo $transaction_date; ?>
                  </div>
                  <div class="col-sm-2">
                    <?php echo $transaction_amount." <var>".$transaction_currency."</var>"; ?>
                  </div>
                  <div class="col-sm-8">
                    <?php echo $transaction_reason; ?>
                  </div>
                </div>
                <br />
            <?php
              $first_event = false;
            }
            ?>
            </div>
            <?php
        }
        
        if ($transactions_donations)
        {
        ?>
        <h3>Donations</h3>
          <?php
            $current_month = null;
            $first_event = true;
            foreach ($transaction_donation_list as $transaction)
            {
              $transaction_date = (isset($transaction['date_posted'])) ? $transaction['date_posted'] : "";
              $transaction_reason = (isset($transaction['reason'])) ? $transaction['reason'] : "";
              $transaction_amount = (isset($transaction['amount'])) ? $transaction['amount'] : "";
              $transaction_currency = (isset($transaction['currency'])) ? $transaction['currency'] : "";
              
              $new_month_tag = false;
              if ($current_month != date("F",strtotime($transaction_date)) || $current_year != date("Y",strtotime($transaction_date)))
              {
                $new_month_tag = true;
              }
              $current_month = date("F",strtotime($transaction_date));
              $current_year = date("Y",strtotime($transaction_date));
            ?>
            <?php if (!$first_event && $new_month_tag) { ?>
              </div>
            <?php } ?>
            <?php if ($new_month_tag) { ?>
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#donations-<?php echo $current_month.'-'.$current_year; ?>"><?php echo $current_month.' - '.$current_year; ?></button>
                </div>
              </div>
              <br />
              <div id="donations-<?php echo $current_month.'-'.$current_year; ?>" class="collapse">
                <div class="row">
                  <div class="col-sm-2">
                    <h4><strong>Date</strong></h4>
                  </div>
                  <div class="col-sm-2">
                    <h4><strong>Amount</strong></h4>
                  </div>
                  <div class="col-sm-8">
                    <h4><strong>Reason for Transaction</strong></h4>
                  </div>
                </div>
            <?php } ?>
                <div class="row">
                  <div class="col-sm-2">
                    <?php echo $transaction_date; ?>
                  </div>
                  <div class="col-sm-2">
                    <?php echo $transaction_amount." <var>".$transaction_currency."</var>"; ?>
                  </div>
                  <div class="col-sm-8">
                    <?php echo $transaction_reason; ?>
                  </div>
                </div>
                <br />
            <?php
              $first_event = false;
            }
            ?>
            </div>
            <?php
        }
        ?>
        <br />
      <h2 class="text-center"><b>Takedowns</b></h2>
        <hr>
       <?php
        if ($takedown_list)
        {
            $current_month = date("F",time())+1;
            $first_event = true;
            foreach ($takedown_list as $takedown)
            {
              $takedown_date = (isset($takedown['date_requested'])) ? $takedown['date_requested'] : "";
              $takedown_requester = (isset($takedown['requester'])) ? $takedown['requester'] : "";
              $takedown_reason = (isset($takedown['reason'])) ? $takedown['reason'] : "";
              $takedown_action = (isset($takedown['action'])) ? $takedown['action'] : "";
              
              $new_month_tag = false;
              if ($current_month != date("F",strtotime($takedown_date)))
              {
                $new_month_tag = true;
              }
              $current_month = date("F",strtotime($takedown_date));
              $current_year = date("Y",strtotime($takedown_date));
            ?>
            <?php if (!$first_event && $new_month_tag) { ?>
              </div>
            <?php } ?>
            <?php if ($new_month_tag) { ?>
              <div class="row">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#donations-<?php echo $current_month.'-'.$current_year; ?>"><?php echo $current_month.' - '.$current_year; ?></button>
                </div>
              </div>
              <br />
              <div id="donations-<?php echo $current_month.'-'.$current_year; ?>" class="collapse">
                <div class="row">
                  <div class="col-sm-2">
                    <h4><strong>Date</strong></h4>
                  </div>
                  <div class="col-sm-2">
                    <h4><strong>Requester</strong></h4>
                  </div>
                  <div class="col-sm-2">
                    <h4><strong>Action Taken</strong></h4>
                  </div>
                  <div class="col-sm-6">
                    <h4><strong>Takedown Reason</strong></h4>
                  </div>
                </div>
            <?php } ?>
                <div class="row">
                  <div class="col-sm-2">
                    <p><?php echo $takedown_date; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p><?php echo $takedown_requester; ?></p>
                  </div>
                  <div class="col-sm-2">
                    <p><?php echo $takedown_action; ?></p>
                  </div>
                  <div class="col-sm-6">
                    <?php echo $takedown_reason; ?>
                  </div>
                </div>
                <br />
            <?php
              $first_event = false;
            }
            ?>
            </div>
            <?php
        }
        else
        {
          echo "<h3 class='text-center'>No Takedowns Requested</h3>";
        }
        ?>
        <br />
    </div>
  </div>
</div>
