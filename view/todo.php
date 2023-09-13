<?php


$dbc = _dbc();

$todo = $dbc->fetchRow('SELECT * FROM todo WHERE id = :t0', [ ':t0' => $_GET['t0'] ]);

if (preg_match('/(http[^\s]+)/', $todo['note'], $m)) {
	$todo['link'] = $m[1];
	$todo['link_trim'] = preg_replace('/^https?:\/\//', '', $todo['link']);
}

?>
<div class="container">
<h1><a href="<?= _link('/') ?>">Cras</a> :: Incoming</h1>

<form method="post">

<input class="form-control" name="id" value="<?= __h($todo['id']) ?>">
<input class="form-control" name="name" value="<?= __h($todo['name']) ?>">

<?php
if (!empty($todo['link'])) {
	printf('<div><a href="%s" rel="noreferrer" target="_blank">%s</a></div>', $todo['link'], $todo['link_trim']);
}
?>

<textarea class="form-control" name="note"><?= __h($todo['note']) ?></textarea>

<div>
	<button class="btn btn-primary" name="a" type="submit" value="todo-update">Update</button>
	<button class="btn btn-danger" name="a" type="submit" value="todo-delete">Delete</button>
</div>
<!--
<pre><?= $todo['created_at'] ?>; <?= $todo['updated_at']; ?>; <?= $todo['expires_at']; ?></pre>
-->

</form>

<pre><?php
var_dump($todo);
?></pre>

</div>
