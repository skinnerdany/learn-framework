<?php

class roles extends model
{
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