<?php

session_start();

/*
status
0 - пользователь зарегестрирован и уз подтверждена
1 - пользователь зарегестрирован и уз не подтверждена
2 - пользователь зарегестрирован, уз подтверждена, но пароль сброшен
3 - пользователь зарегестрирован, уз не подтверждена, но пароль сброшен
/**/
class user
{
    protected $db = false;
    public $isUser = false;

    public function __construct()
    {
        $this->db = new pgsql();
        // проверить, есть ли в сессии запись(БД) пользователя
        if (!isset($this->id)) {
            // если !п.1) то проверить, есть ли токен в куки пользователя
            if (isset($_COOKIE['token'])) {
        //die('aaa');
                // по токену получаем запись пользователя из бд
                $user = $this->db->select('users', 'id, email, token, status, role_id, admin', ['token' => $_COOKIE['token']]);
                if (!empty($user)) {
                    $user = reset($user);
                    // сохраняем запись из БД в сессию
                    foreach ($user as $param => $value) {
                        $this->$param = $value;
                    }
                    $this->privileges = $this->getPrivileges($user['role_id']);
                }
            }
        }

        if (isset($this->id)) {
            // продлить в куках токен пользователя
            setcookie('token', $this->token, time() + 365 * 86400, '/');
            $this->isUser = true;
        }
    }
    
    public function checkPrivilege($code)
    {
        return $this->admin == 1 || isset($this->privileges[$code]);
    }
    
    protected function getPrivileges($roleId)
    {
        $sql = 'select 
                    p.code 
                from 
                    roles_privileges rp
                join
                    privileges p
                        ON p.id=rp.privilege_id
                where 
                    rp.role_id = ' . $this->db->escape($roleId);
        $result = $this->db->query($sql);
        $privileges = [];
        foreach ($result as $privilege) {
            $privileges[$privilege['code']] = $privilege['code'];
        }
        return $privileges;
    }

    public function __get($name)
    {
        return $_SESSION[$name] ?? null;
    }
    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    public function logout()
    {
        $id = $this->id;
        $_SESSION = [];
        setcookie('token', '', time()-1, '/');
        $this->db->update('users', ['token' => ''], ['id' => $id]);
    }
    
    public function login($user = [])
    {
        // убедиться, что пользователь прислал валидные данные
        if (empty($user['email']) || empty($user['password'])) {
            throw new Exception('email_empty');
        }
        $user = $this->authenticate($user);
        $this->authorization($user);
    }
    
    protected function authenticate($user)
    {
        // по email'у извлечь запись о пользователе из БД и убедиться, что такая запись есть
        $userCheck = $this->db->select('users', '*', ['email' => $user['email']]);
        if (empty($userCheck)) {
            throw new Exception('Email not registered');
        }
        $userCheck = reset($userCheck);
        // сгернерировать хэш на основе введенного пользователем пароля и соли, которая лежит в БД
        // сравнить полученный в п.3 хэш с тем, что сохранен в БД
        
        if ($userCheck['password'] != $this->getPasswordHash($user['password'], $userCheck['salt'])) {
            throw new Exception('wrong_password');
        }

        // если УЗ нахоодится в состоянии "неактивирована", то сравнить токен полученный из ссылки пользователя с тем, который 
//записан в БД
        if ($userCheck['status'] == 1) {
            if ($user['token'] != $userCheck['token']) {
                throw new Exception('invalid_confirmation_link');
            }
            // Изменить статус УЗ на 0
            $this->db->update('users', ['token' => '', 'status' => 0], ['id' => $userCheck['id']]);
        }

        return $userCheck;
    }

    protected function authorization($user)
    {
        // содержимое записи из БД сохраняю в сессии(для доступа к этой информаации без запроса в БД)
        unset($user['password'], $user['salt']);
        foreach ($user as $param => $value) {
            $this->$param = $value;
        }
        $p = $this->getPrivileges($user['role_id']);
        $this->privileges = $this->getPrivileges($user['role_id']);
        // генерирую токен
        $token = $this->getSalt();
        // сохраняю токен в БД
        $this->db->update('users', ['token' => $token], ['id' => $user['id']]);
        // сохраняю токен в куки пользователя
        setcookie('token', $token, time() + 365 * 86400, '/');
    }

    public function registration($user = [])
    {
        //получить email, пароль и подтверждение пароля из формы
        // проверить, заполнены ли эти поля
        if (empty($user['email'])) {
            throw new Exception('email_empty');
        }
        if (empty($user['password']) || empty($user['repassword'])) {
            throw new Exception('Registration password or confirmation is empty');
        }
        // сравнить пароль и его подтверждение
        if ($user['password'] != $user['repassword']) {
            throw new Exception('password_confirmation_failed');
        }

        // убедиться, что в системе не зарегестрирован эмэйл
        $userCheck = $this->db->select('users', '*', ['email' => $user['email']]);
        if (!empty($userCheck)) {
            throw new Exception('Email busy');
        }

        // сгенерировать соль и токен(ссылка на активацию)
        $salt = $this->getSalt();
        $token = $this->getSalt();
        
        // сохранить данные в БД
        $this->db->insert('users', [
            'email'     => $user['email'],
            'salt' => $salt,
            // получить хэш пароля
            'password'  => $this->getPasswordHash($user['password'], $salt),
            'status' => 1,
            'token' => $token
        ]);
        
        // отправить на почту ссылку на активацию
        //mail($user['email'], 'confirm' , '<a href="/users/login?token='.$token.'">Cnfirmation</a>');
    }

    public function forgot($email = [])
    {
        // получить email от пользователя
        // убедиться, что email зарегестрирован 
        $userCheck = $this->db->select('users', '*', ['email' => $email]);
        if (empty($userCheck)) {
            throw new Exception('Email not registered');
        }
        $userCheck = reset($userCheck);
        // сгенерировать токен(ссылкой на изменение пароля)
        $token = $this->getSalt();
        // сохранить токе в БД, в БД изменить status x => 2
        $this->db->update('users', [
            'token' => $token,
            'status' => 2
        ], ['id' => $userCheck['id']]);
        // отправить письмо со ссылкой на изменение пароля
        //mail($user['email'], 'confirm' , '<a href="/users/login?token='.$token.'">Cnfirmation</a>');
    }
    
    public function reset($data = [])
    {
        // проверить равенство пароля и подтверждения
        if ($data['password'] != $data['repassword']) {
            throw new Exception('password_confirmation_failed');
        }
        // на основе токена, полученного из ссылки на изменение пароля извлечь из БД запись пользователя
        $user  = $this->db->select('users', '*', ['token' => $data['token']]);
        if (empty($user)) {
            throw new Exception('Bad reset url');
        }
        $user = reset($user);
        
        // убедиться, что УЗ находится в состоянии сброшенного пароля
        if ($user['status'] != 2) {
            throw new Exception('Bad reset url');
        }
        // сгенерировать соль
        $salt = $this->getSalt();
        //сохранить в БД новую соль новый хэш пароля и статус => 0
        $this->db->update('users', [
            'salt' => $salt,
            // получить хэш пароля
            'password' => $this->getPasswordHash($data['password'], $salt),
            'status' => 0,
            'token' => ''
        ], ['id' => $user['id']]);
        // redirect to /users/login
    }
    
    protected function getSalt()
    {
        return md5(random_bytes(64));
    }

    protected function getPasswordHash($password, $salt)
    {
        return md5(md5($salt) . md5($password) . $salt);
    }
}

/**
Регистрация:

1) получить email, пароль и подтверждение пароля из формы
2) проверить, заполнены ли эти поля
-3) провести прочую валидацию
4) сравнить пароль и его подтверждение
5) убедиться, что в системе не зарегестрирован эмэйл
6) сгенерировать соль и токен(ссылка на активацию)
7) получить хэш пароля
8) сохранить данные в БД
9) отправить на почту ссылку на активацию

Сброс пароля
1) получить email от пользователя
* убедиться, что email зарегестрирован
2) сгенерировать токен(ссылкой на изменение пароля)
3) сохранить токе в БД, в БД изменить status x => 2
4) отправить письмо со ссылкой на изменение пароля


Изменение пароля
1) проверить равенство пароля и подтверждения
2) на основе токена, полученного из ссылки на изменение пароля извлечь из БД запись пользователя
3) сгенерировать соль
4) получить хэш пароля
5) сохранить в БД новую соль новый хэш пароля и статус => 0
6) redirect to /users/login

Вход
-- аутентификация
1) убедиться, что пользователь прислал валидные данные
2) по email'у извлечь запись о пользователе из БД и убедиться, что такая запись есть
3) сгернерировать хэш на основе введенного пользователем пароля и соли, которая лежит в БД
4) сравнить полученный в п.3 хэш с тем, что сохранен в БД
5) если УЗ нахоодится в состоянии "неактивирована", то сравнить токен полученный из ссылки пользователя с тем, который 
записан в БД
5.1) Изменить статус УЗ на 0
-- авторризация
6) содержимое записи из БД сохраняю в сессии(для доступа к этой информаации без запроса в БД)
7) генерирую токен
8) сохраняю токен в куки пользователя
9) сохраняю токен в БД

Воссстановление сессии через токен(чекбокс "запомнить меня")
1) проверить, есть ли в сессии запись(БД) пользователя
2) если !п.1) то проверить, есть ли токен в куки пользователя
3.1) если п.2) то по токену получаем запись пользователя из бд
3.2) сохраняем запись из БД в сессию
4) продлить в куках токен пользователя

 */