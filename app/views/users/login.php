<form action="/users/login" method="POST">
    <input type="hidden" name="token" value="<?php echo $token ?? ''; ?>" />
    Email: <input type="email" name="email" value="<?php echo ($email) ?? ''; ?>" /><br />
    Password: <input type="password" name="password" value="<?php echo ($password) ?? ''; ?>" /><br />
    <input type="submit" name="go" value="Войти" />
</form>