<?php
class Role
{
    protected $permissions;
 
    protected function __construct() {
        $this->permissions = array();
    }
 
    // return a role object with associated permissions
    public static function getRolePerms($role_id, $db)
    {
        $role = new Role();
        $db->select_raw("role_perm as rp JOIN permissions as p ON rp.perm_id = p.perm_id", "WHERE rp.role_id=?", array($role_id), "p.perm_name");
        foreach ($results as $result)
        {
            $role->permissions[$result["perm_name"]] = true;
        }
        return $role;
    }
 
    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
    
    public function getRoles($db)
    {
        $role_list = $db->select('roles', "1=?", array(1));
        $roles = array();
        foreach ($role_list as $role)
        {
          if (!is_array($role))
          {
            $roles = array($role_list);
            break;
          }
          array_push($roles, $role);
        }
        return $roles;
    }
}
?>