<?php
require_once('Role.class.php');
class User {
 
    public $id;
    public $username;
    public $hashedPassword;
    public $group;
    public $admin;
    public $join_date;
    public $last_seen;
    public $theme;
    public $public_key;
    public $profile_image;
    public $website;
    public $about;
    public $blog_title;
    public $blog_desc;
    public $roles;
 
    //Constructor is called whenever a new object is created.
    //Takes an associative array with the DB row as an argument.
    function __construct($data, $db) {
        $this->id = (isset($data['id'])) ? $data['id'] : "";
        $this->username = (isset($data['username'])) ? $data['username'] : "";
        $this->hashedPassword = (isset($data['password'])) ? $data['password'] : "";
        $this->group = (isset($data['group_name'])) ? $data['group_name'] : "";
        $this->admin = (isset($data['site_admin'])) ? (bool) $data['site_admin'] : "";
        $this->join_date = (isset($data['join_date'])) ? $data['join_date'] : "";
        $this->last_seen = (isset($data['last_seen'])) ? $data['last_seen'] : "";
        $this->theme = (isset($data['theme'])) ? $data['theme'] : "";
        $this->public_key = (isset($data['public_key'])) ? $data['public_key'] : "";
        $this->minecraft_user = (isset($data['minecraft_user'])) ? $data['minecraft_user'] : "";
        $this->website = (isset($data['website'])) ? $data['website'] : "";
        $this->about = (isset($data['about'])) ? $data['about'] : "";
        $this->quote = (isset($data['quote'])) ? $data['quote'] : "";
        $this->blog_title = (isset($data['blog_title'])) ? $data['blog_title'] : "";
        $this->blog_desc = (isset($data['blog_desc'])) ? $data['blog_desc'] : "";
        $this->roles = array();
        $results = $db->select("user_role as ur JOIN roles as r ON ur.role_id = r.role_id", "ur.user_id=?", array($this->id), "ur.role_id, r.role_name");
        $users = array();
        foreach ($results as $result)
        {
          $this->roles[$result["role_name"]] = Role::getRolePerms($result["role_id"], $db);
        }
    }
 
    public function save($db, $isNewUser = false) {
        //if the user is already registered and we're
        //just updating their info.
        if(!$isNewUser) {
            //set the data array
            $data = array(
                "username" => $this->username,
                "password" => $this->hashedPassword,
                "last_seen" => date("Y-m-d H:i:s",time()),
                "theme" => $this->theme,
                "public_key" => $this->public_key,
                "minecraft_user" => $this->minecraft_user,
                "website" => $this->website,
                "quote" => $this->quote,
                "about" => $this->about,
                "blog_title" => $this->blog_title,
                "blog_desc" => $this->blog_desc
            );
            
            //update the row in the database
            $db->update($data, 'users', 'id=?', array($this->id));
        }else {
        //if the user is being registered for the first time.
            $data = array(
                "username" => $this->username,
                "password" => $this->hashedPassword,
                "join_date" => date("Y-m-d H:i:s",time()),
                "last_seen" => date("Y-m-d H:i:s",time())
            );
            
            $this->id = $db->insert($data, 'users');
            $this->join_date = time();
        }
        return true;
    }
 
    public function delete($db) {
        //if the user is already registered and we're
        //just updating their info.
        //if the user is being registered for the first time.
        $db->delete('users', 'id=?', array($this->id));
        return true;
    }
}
?>