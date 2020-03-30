<form action="" method="POST">
    <input type="hidden" name="id" value="<?php echo $id ?? 0; ?>" />
    Назввание роли: <input type="text" name="name" value="<?php echo $name ?? ''; ?>" /><br />

    <?php foreach ($privileges as $privilege) { ?>
    <?php
    //$checked = (int) isset($checked_privileges[$privilege['id']]);
    
    $checked = 0;
    foreach ($checked_privileges as $cp) {
        if ($privilege['id'] == $cp['id']) {
            $checked = 1;
        }
    }
    /**/
    ?>
    <input 
        <?php if ($checked == 1) { ?>
        checked="checked"
        <?php } ?>
        type="checkbox" 
        name="privileges[]" 
        value="<?php echo $privilege['id']; ?>" /> <?php echo $privilege['name']; ?><br />
    <?php } ?>
    
    <input type="submit" name="go" value="Сохранить роль" />
</form>