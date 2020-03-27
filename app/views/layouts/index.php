<!DOCTYPE html>
<html>
    <head>
        <noscript>
            <meta http-equiv="refresh" content="1;URL=/user/nojs" />
        </noscript>
        <meta http-equiv="content-type" content="text\html;charset=utf-8" />
    </head>
    <body>
        <?php echo $error == '' ? '' : 'Ошибка: ' . $error; ?><br />
        <?php echo $modal; ?><br />
        <?php echo $content; ?>
        <a href="/users/forgot">Забыли пароль</a><br />
        <a href="/users/login">Войти</a><br />
        <a href="/users/registration">Зарегестрироваться</a>
    </body>
</html>