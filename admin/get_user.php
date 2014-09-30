<?php
require_once('../includes/config.php');
if(isset($_POST))
{
  $query = rawurldecode($_POST['query']);
  
  $user_list = $db->select('users', "username LIKE ?", array('%'.$query.'%'));

  $users = array();
  foreach ($user_list as $user)
  {
    if (!is_array($user))
    {
      $users = array($user_list);
      break;
    }
    array_push($users, $user);
  }
  
  if ($users)
  {
    foreach ($users as $parsed_user)
    {
      $user = $userTools->getUser($parsed_user['username']);
      ?>
      <div class="row">
        <div class="col-sm-12">
          <div class="row"><h3 class="text-center"><strong><?php echo $user->username; ?></strong></h3></div>
          <div class="row">
            <h4>Roles</h4>
            <?php
            foreach ($user->roles as $role => $perm)
            {
              ?>
              <div class="form-group">
                <label class="sr-only" for="<?php echo $user->id; ?>_<?php echo $role; ?>"><?php echo $role; ?></label>
                <button type="button" class="btn btn-danger" id="<?php echo $user->id; ?>_<?php echo $role; ?>">Remove</button>
              </div>
              <?php
            }
            ?>
          </div>
          <div class="row">
            <div class="col-sm-10">
              <select class="form-control" name="role_select_<?php echo $user->id; ?>" id="role_select_<?php echo $user->id; ?>">
                <?php
                $roles = Role::getRoles($db);
                foreach ($roles as $role)
                {
                  echo "<option>".$role['role_name']."</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-default" id="<?php echo $user->id; ?>">Add Role</button>
            </div>
          </div>
        
        </div>
      </div>
      <?php
    }
  }
}
?>