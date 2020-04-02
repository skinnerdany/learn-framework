<?php foreach ($users as $user) { ?>
<form method="POST" action="/users/attachRoles">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
    <input type="checkbox" name="admin" value="1" <?php echo $user['admin'] == 1 ? 'checked="checked"' : ''; ?> />
    <select name="role_id">
        <option value="0" <?php echo 0 == $user['role_id'] ? 'selected="selected"' : ''; ?>>
            ----
        </option>
        <?php foreach ($roles as $role) { ?>
        <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $user['role_id'] ? 'selected="selected"' : ''; ?>>
            <?php echo $role['name']; ?>
        </option>
        <?php } ?>
    </select>
    <input type="submit" name="go" value="Сохранить">
    <?php echo $user['email']; ?>
</form>
<?php } ?>
