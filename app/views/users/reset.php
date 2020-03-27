<form action="/users/reset" method="POST">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    Password: <input type="password" name="password" /><br />
    Retype password: <input type="password" name="repassword" /><br />
    <input type="submit" name="go" value="Reset" />
</form>