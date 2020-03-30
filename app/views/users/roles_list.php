<a href="/users/roleEdit">Создать роль</a><hr />
<?php foreach ($roles as $role) { ?>
<a href="/users/roleEdit?id=<?php echo $role['id']; ?>"><?php echo $role['name'];  ?></a><br />
<?php } ?>