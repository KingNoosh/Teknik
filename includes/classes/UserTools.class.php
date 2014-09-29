<?php
require_once('User.class.php');
require_once('DB.class.php');

class UserTools {
 
    function UserTools($db, $CONF)
    {
      $this->db=$db;
      $this->conf=$CONF;
    }
    //Log the user in. First checks to see if the
    //username and password match a row in the database.
    //If it is successful, set the session variables
    //and store the user object within.
    public function login($username, $password, $remember_me, $CONF)
    {
        $result = $this->db->select("users", "username=? AND password=?", array($username, $password));
        if($result)
        {
            $user = new User($result, $this->db);
            $_SESSION[$CONF['session_prefix']."user"] = serialize($user);
            $_SESSION[$CONF['session_prefix']."logged_in"] = 1;
            if ($remember_me)
            {
              $identifier = hashPassword($username, $this->conf);
              $token = bin2hex(openssl_random_pseudo_bytes(20));
              $data = array(
                "user_id" => $user->id,
                "identifier" => $identifier,
                "token" => $token,
                "timeout" => date("Y-m-d H:i:s",time() + 60 * 60 * 24 * 7)
              );
              $this->db->insert($data, "sessions");
              setcookie($CONF['session_prefix'].'auth', "$identifier:$token", time() + 60 * 60 * 24 * 7, '/', '.'.$this->conf['host']);
            }
            return true;
        }else{
            return false;
        }
    }
    
    // Checks to see if the password provided is valid for the username
    public function checkPass($username, $password)
    {
        $result = $this->db->select("users", "username=? AND password=?", array($username, $password));
        if($result)
        {
          return true;
        }
        return false;
    }
    
    //Log the user out. Destroy the session variables.
    public function logout($CONF)
    {
        if (isset($_COOKIE['auth']))
        {
          $user = unserialize($_SESSION[$CONF['session_prefix'].'user']);
          list($identifier, $token) = explode(':', $_COOKIE['auth']);
          $this->db->delete("sessions", "user_id=?", array($user->id));
          setcookie($CONF['session_prefix'].'auth', false, time() + 60 * 60 * 24 * 7, '/', '.'.$this->conf['host']);
        }
        unset($_SESSION[$CONF['session_prefix'].'user']);
        unset($_SESSION[$CONF['session_prefix'].'logged_in']);
        session_destroy();
    }
 
    //Check to see if a username exists.
    //This is called during registration to make sure all user names are unique.
    public function checkUsernameExists($username) {
        $result = $this->db->select("users", "username=?", array($username), "id");
        if($result['id'])
        {
            return true;
           }else{
            return false;
        }
    }
 
    //Check to see if a email exists.
    //This is called during registration to make sure a user is not added that would co-incide with an email address already made.
    public function checkEmailExists($domain, $email) {
        try
        {
          $account = $domain->Accounts->ItemByAddress($email);
        }
        catch(Exception $e)
        {
            return false;
        }
        return true;
    }
 
    //Check to see if a username exists.
    //This is called during registration to make sure all user names are unique.
    public function getUser($username) 
    {    
        $result = $this->db->select('users', "username=?", array($username));
        return new User($result, $this->db);
    }
 
    //Grab all of the users from a select group
    public function getUsersFromGroup($group) 
    {    
        $results = $this->db->select('users', "group_name=?", array($group));
        $users = array();
        foreach ($results as $result)
        {
          if (!is_array($result))
          {
            return array(new User($results, $this->db));
          }
          $users[] = new User($result, $this->db);
        }
        return $users;
    }
    
    //Grab all users
    public function getUsers() 
    {    
        $results = $this->db->select('users', "1=?", array("1"));
        $users = array();
        foreach ($results as $result)
        {
          if (!is_array($result))
          {
            return array(new User($results, $this->db));
          }
          array_push($users, new User($result, $this->db));
        }
        return $users;
    }
    
    //get a user
    //returns a User object. Takes the users id as an input
    public function get($id)
    {
      $result = $this->db->select('users', "id=?", array($id));      
      return new UserUser($result, $this->db);
    }    
 
    // check if user has a specific privilege
    public function hasPrivilege($perm)
    {
      foreach ($this->roles as $role)
      {
          if ($role->hasPerm($perm))
          {
              return true;
          }
      }
      return false;
    }
    
    // check if a user has a specific role
    public function hasRole($role_name)
    {
      return isset($this->roles[$role_name]);
    }
     
    // insert a new role permission association
    public function insertPerm($role_id, $perm_id)
    {
      $data = array(
              "role_id" => $role_id,
              "perm_id" => $perm_id
          );
      $this->db->insert($data, "role_perm");
      return true;
    }
     
    // delete ALL role permissions
    public function deletePerms()
    {
      $db->delete('role_perm', '1=?', array(1));
      return true;
    }
    
    // insert a new role
    public function insertRole($role_name)
    {
      $data = array(
              "role_name" => $role_name
          );
      $this->db->insert($data, "roles");
      return true;
    }
     
    // insert array of roles for specified user id
    public function insertUserRoles($user_id, $roles)
    {
      foreach ($roles as $role_id)
      {
        $data = array(
                "user_id" => $user_id,
                "role_id" => $role_id
            );
        $this->db->insert($data, "user_role");
      }
      return true;
    }
     
    // delete array of roles, and all associations
    public static function deleteRoles($roles)
    {
      foreach ($roles as $role_id)
      {
        $db->delete('roles as t1 JOIN user_role as t2 on t1.role_id = t2.role_id JOIN role_perm as t3 on t1.role_id = t3.role_id', 't1.role_id=?', array($role_id), "t1, t2, t3");
      }
      return true;
    }
     
    // delete ALL roles for specified user id
    public static function deleteUserRoles($user_id)
    {
      $db->delete('user_role', 'user_id=?', array($user_id));
      return true;
    }
}
?>