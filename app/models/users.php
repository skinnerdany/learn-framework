<?php

class users extends model
{
    public function saveRole($user)
    {
        $id = $user['id'];
        unset($user['id']);
        $user['admin'] = (int) isset($user['admin']);
        self::$db->update('users', $user, ['id' => $id]);
    }

    public function getUsers()
    {
        return self::$db->select('users', 'id, email, role_id, admin');
    }
}