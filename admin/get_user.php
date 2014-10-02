<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  $query = rawurldecode($_POST['query']);
  
  $user_list = $db->select('users', "username LIKE ?", array('%'.$query.'%'));

  $users = array();
  foreach ($user_list as $var)
  {
    if (!is_array($var))
    {
      $users = array($user_list);
      break;
    }
    array_push($users, $var);
  }
  
  if ($users)
  {
    foreach ($users as $parsed_user)
    {
      $item = $userTools->getUser($parsed_user['username']);
      ?>
      <div class="row">
        <div class="col-sm-12">
          <div class="row"><h3 class="text-center"><strong><?php echo $item->username; ?></strong></h3></div>
          <div class="row">
            <div class="col-sm-6">
              <ul class="list-group">
                <li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span> <?php echo $item->join_date; ?></li>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Last Seen</strong></span> <?php echo $item->last_seen; ?></li>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Email</strong></span> <a href="mailto:<?php echo $item->username; ?>@teknik.io"><?php echo $item->username; ?>@teknik.io</a></li>
                <?php if ($item->blog_title) { ?>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Blog</strong></span> <a href="<?php echo get_page_url('blog', $CONF).'/'.$item->username; ?>" id="blog_title"><?php echo $item->blog_title; ?></a></li>
                <?php } ?>
                <?php if ($item->website) { ?>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Website</strong></span> <a href="<?php echo $item->website; ?>" id="website"><?php echo $item->website; ?></a></li>
                <?php } ?>
                 <?php if ($item->quote) { ?>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Quote</strong></span> <?php echo $item->quote; ?></li>
                <?php } ?>
                <li class="list-group-item text-center"><button type="button" class="btn btn-danger delete_account" id="<?php echo $item->id; ?>">Delete Account</button></li>
              </ul>
            </div>
            <div class="col-sm-6">
              <div class="row">
                <h4>Roles</h4>
                <?php
                foreach ($item->roles as $role_name => $perm)
                {
                  $role = Role::getRole($db, $role_name);
                  ?>
                  <div class="form-group">
                    <label class="col-sm-8 control-label" for="<?php echo $item->id; ?>_<?php echo $role['role_id']; ?>"><?php echo $role['role_name']; ?></label>
                    <div class="col-sm-4">
                      <button type="button" class="btn btn-danger btn-sm remove_user_role" id="<?php echo $item->id; ?>_<?php echo $role['role_id']; ?>">Remove</button>
                    </div>
                  </div>
                  <?php
                }
                ?>
              </div>
              <br />
              <div class="row">
                <div class="col-sm-8">
                  <select class="selectpicker" name="role_select_<?php echo $item->id; ?>" id="role_select_<?php echo $item->id; ?>">
                    <?php
                    $roles = Role::getRoles($db);
                    foreach ($roles as $role)
                    {
                      echo "<option value=\"".$role['role_id']."\">".$role['role_name']."</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-sm-4">
                  <button type="button" class="btn btn-default add_user_role" id="<?php echo $item->id; ?>">Add Role</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr />
      <?php
    }
  }
}
?>