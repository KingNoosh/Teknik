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
        $perm_list = $db->select_raw("role_perm as rp JOIN permissions as p ON rp.perm_id = p.perm_id", "WHERE rp.role_id=?", array($role_id), "p.perm_name");
        $perms = array();        
        foreach ($perm_list as $perm)
        {
          if (!is_array($perm))
          {
            $perms = array($perm_list);
            break;
          }
          array_push($perms, $perm);
        }
        foreach ($perms as $perm)
        {
            $role->permissions[$perm["perm_name"]] = true;
        }
        return $role;
    }
 
    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }
    
    // Get Role by Name
    public function getRole($db, $role_name)
    {
        $role_list = $db->select('roles', "role_name=?", array($role_name));
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
    
    // Get all Roles
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