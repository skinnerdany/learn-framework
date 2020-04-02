<?php

class roles extends model
{
    public function saveRole($roleData)
    {
        $id = $roleData['id'] ?? 0;
        $privileges = $roleData['privileges'] ?? [];
        unset($roleData['id'], $roleData['privileges']);

        if ($id == 0) {
            $checkRole = self::$db->select('roles', 'id', ['name' => $roleData['name']]);
            if (!empty($checkRole)) {
                throw new Exception('role_duplicate');
            }
            $id = self::$db->insert('roles', $roleData, true);
        } else {
            $checkRole = self::$db->select('roles', '*', ['name' => $roleData['name']]);
            if (!empty($checkRole) && $id != $checkRole[0]['id']) {
                throw new Exception('role_duplicate');
            }
            self::$db->update('roles', $roleData, ['id' => $id]);
        }
        
        self::$db->delete('roles_privileges', ['role_id' => $id]);
        foreach ($privileges as $privilege) {
            self::$db->insert('roles_privileges', ['role_id' => $id, 'privilege_id' => $privilege]);
        }
    }
    
    public function getIndexRoles()
    {
        $roles = $this->getRoles();
        $out = [];
        foreach ($roles as $role) {
            $out[$role['id']] = $role;
        }
        return $out;
    }
    
    public function getRoles($id = 0)
    {
        $where = [];
        if ($id > 0) {
            $where = [
                'id' => $id
            ];
        }
        $roles = self::$db->select('roles', '*', $where);
        if ($id > 0) {
            if (empty($roles)) {
                return [];
            }
            return reset($roles);
        }
        return $roles;
    }

    /**
     * при пустойй роли все чекбоксы будут отмечены
     */
    public function getPrivileges($roleId = 0)
    {
        $where = '';
        if ($roleId > 0) {
            $rolesPrivileges = self::$db->select('roles_privileges', '*', ['role_id' => $roleId]);
            if (count($rolesPrivileges) > 0) {
                $ids = [];
                foreach ($rolesPrivileges as $privilege) {
                    $ids[] = $privilege['privilege_id'];
                }
                $where = ' where id in (' . implode(',', $ids) . ')';
            } else {
                return [];
            }
        }
        $privileges = self::$db->query('select * from privileges' . $where);
        return $privileges;
    }
}