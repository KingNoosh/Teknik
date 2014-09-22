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
    public function login($username, $password, $remember_me)
    {
        $result = $this->db->select("users", "username=? AND password=?", array($username, $password));
        if($result)
        {
            $user = new User($result);
            $_SESSION["user"] = serialize($user);
            $_SESSION["logged_in"] = 1;
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
              setcookie('auth', "$identifier:$token", time() + 60 * 60 * 24 * 7, '/', '.'.$this->conf['host']);
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
    public function logout()
    {
        if (isset($_COOKIE['auth']))
        {
          $user = unserialize($_SESSION['user']);
          list($identifier, $token) = explode(':', $_COOKIE['auth']);
          $this->db->delete("sessions", "user_id=?", array($user->id));
          setcookie('auth', false, time() + 60 * 60 * 24 * 7, '/', '.'.$this->conf['host']);
        }
        unset($_SESSION['user']);
        unset($_SESSION['logged_in']);
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
        return new User($result);
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
            return array(new User($results));
          }
          $users[] = new User($result);
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
            return array(new User($results));
          }
          array_push($users, new User($result));
        }
        return $users;
    }
    
    //get a user
    //returns a User object. Takes the users id as an input
    public function get($id)
    {
        $result = $this->db->select('users', "id=?", array($id));
        
        return new User($result);
    }
    
}
?>