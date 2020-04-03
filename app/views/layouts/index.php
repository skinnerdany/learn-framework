<!DOCTYPE html>
<html>
    <head>
        <noscript>
            <meta http-equiv="refresh" content="1;URL=/user/nojs" />
        </noscript>
        <meta http-equiv="content-type" content="text\html;charset=utf-8" />
        <script src="/js/jquery.js"></script>
    </head>
    <body>
        <?php echo $error == '' ? '' : 'Ошибка: ' . $error; ?><br />
        <?php echo $modal; ?><br />
        <?php echo $content; ?>
        <br />
        <hr />
        <hr />
        <a href="/users/forgot">Забыли пароль</a><br />
        <a href="/users/login">Войти</a><br />
        <a href="/users/registration">Зарегистрироваться</a><br />
        <a href="/users/logout">Выйти</a><br /><br />
        <?php if (core::app()->user->checkPrivilege("role_change")) { ?>
        <a href="/users/roles">Управление ролями</a><br />
        <?php } ?>
        <?php if (core::app()->user->checkPrivilege("user_role")) { ?>
        <a href="/users/attachRoles">Назначение ролей пользователям</a><br />
        <?php } ?>
    </body>
</html>