<?php
/**
 *
 */

$dbc = _dbc();
// $dbc->query('CREATE TABLE todo (id, name, note, tags, sort, created_at, updated_at, expires_at)', []);

?>

<div class="container">
<div class="d-flex justify-content-between">
	<div>
		<h1><a href="/cras">Cras</a></h1>
	</div>
	<div class="p-2">
		<a class="btn btn-sm btn-success" href="/cras/create">Add</a>
	</div>
</div>

<div class="mb-2">
<form action="/cras/search">
	<div class="input-group">
		<label class="input-group-text">Search</label>
		<input class="form-control" name="q">
		<button class="btn btn-primary">Go</button>
	</div>
</form>
</div>

<?php

$off = intval($_GET['o']);
$off = max(0, $off);

$tag_list = [];

$arg = [];
$sql = <<<SQL
SELECT *
FROM todo
ORDER BY id DESC, sort, expires_at DESC, updated_at DESC
LIMIT 20
OFFSET $off
SQL;

if (!empty($_GET['tag'])) {

	$arg = [ ':t1' => sprintf('%%#%s%%', $_GET['tag']) ];

	$sql = <<<SQL
	SELECT *
	FROM todo
	WHERE note LIKE :t1
	ORDER BY id, sort, expires_at DESC, updated_at DESC
	LIMIT 20
	OFFSET 0
	SQL;

}


$todo_list = $dbc->fetchAll($sql, $arg);

ob_start();

foreach ($todo_list as $todo) {

	if (empty($todo['name'])) {
		$todo['name'] = '-unknown-';
	}

	$todo_tag_list = [];

	if (preg_match_all('/#(\w+)\b/', $todo['note'], $m)) {
		// var_dump($m);
		foreach ($m[1] as $t) {
			$tag_list[$t]++;
			$todo_tag_list[] = $t;
		}
	}

?>
	<section class="todo">

		<h2><a href="<?= _link(sprintf('/todo/%s', $todo['id'])) ?>"><?= __h($todo['name']) ?></a></h2>
		<div class="note">
			<code style="color: #c00;"><?= implode(', ', $todo_tag_list) ?></code>
			<?= __h($todo['note']) ?>
		</div>
		<!-- <?php
		var_dump($todo);
		?> -->

		<!-- <div class="todo-actions">
			<form action="<?= _link(sprintf('/todo/%s', $todo['id'])) ?>" method="post">
				<button class="btn btn-danger todo-delete-exec"
					data-todo-id="<?= $todo['id'] ?>"
					name="a" type="submit" value="todo-delete">X</button>
			</form>
		</div> -->

	</section>

<?php
}
?>

<div>
	<div class="d-flex justify-content-between">
	<?php
	$older = max(0, $off - 20);
	if ($older) {
		printf('<a class="btn btn-secondary" href="?o=%s">Back 20</a>', $older);
	} else {
		printf('<a class="btn btn-outline-secondary disabled" disabled href="#">Back 20</a>', $older);
	}
	$newer = $off + 20;
	printf('<a class="btn btn-secondary" href="?o=%s">Next 20</a>', $newer);
	?>
	</div>
	<p>Maybe trigger an auto-load the next block?</p>
</div>

</div>

<?php

$html = ob_get_clean();

// Tags
arsort($tag_list);
echo '<div class="tag-list-wrap">';
foreach ($tag_list as $tag => $c) {
	$tag_link = _link(sprintf('/start?tag=%s', $tag));
	printf('<div class="tag-chip"><a class="btn btn-sm btn-outline-secondary" href="%s">%s <small>[%d]</small></a></div>', $tag_link, $tag, $c);
}
echo '</div>';

// Body
echo $html;

?>

<script>
document.addEventListener('click', function(e) {

	if (!e.target.matches('.todo-delete-exec')) return;
	e.preventDefault();

	// Find Parent
	var form = null;
	var node = e.target.parentNode;
	while (node) {

		if (node.matches('form')) {
			form = node;
		}

		if (node.matches('section.todo')) {
			break;
		}

		node = node.parentNode;
	}

	if (node) {
		node.parentNode.removeChild(node);
	}

	data = new FormData(form);
	data.append('a', 'todo-delete');
	// data.add

	// fetch(form.action, {
	// 	method: 'POST',
	// 	mode: 'no-cors',
	// 	cache: 'no-cache',
	// 	headers: {
	// 		'content-type': 'application/x-www-form-urlencoded',
	// 	},
	// 	redirect: 'manual',
	// 	body: new URLSearchParams(data),
	// });

});
</script>
