<?php
/**
 * Main Controller
 */

require_once('../boot.php');

session_start();

// Authentication
if (empty($_SESSION['sql_file'])) {
	if ( ! empty($_COOKIE['cras-secret'])) {
		$_SESSION['sql_file'] = $_COOKIE['cras-secret'];
		setcookie('cras-secret', $_COOKIE['cras-secret'], [
			'expires' => $_SERVER['REQUEST_TIME'] + 60 * 60 * 24 * 7,
		]);
	}
}

//
if (empty($_SESSION['sql_file'])) {

	switch ($_POST['a']) {
		case 'open':

			$_SESSION = [];

			$hash = sodium_bin2base64(sodium_crypto_generichash($_POST['auth']), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
			$sql_file = sprintf('%s/var/%s.sqlite', APP_ROOT, $hash);
			if ( ! is_file($sql_file)) {
				__exit_text("Create the file:\n  $sql_file\nto authenticate\n", 403);
			}
			if ( ! is_writable($sql_file)) {
				__exit_text("The file:\n  $sql_file\nmust be writable by the web-server\n", 403);
			}

			$_SESSION['sql_file'] = $sql_file;

			setcookie('cras-secret', $sql_file, [
				'expires' => $_SERVER['REQUEST_TIME'] + 60 * 60 * 24 * 7,
			]);

			__exit_301('/start');

	}

	$title = 'Cras :: Authenticate';

	$body = <<<HTML
	<div class="container mt-4">
	<form method="post">
		<div class="input-group">
			<div class="input-group-text">Auth:</div>
			<input class="form-control" name="auth">
			<button class="btn btn-primary" name="a" type="submit" value="open">Open</button>
		</div>
	</form>
	</div>
	HTML;

	require_once(APP_ROOT . '/output/html.php');

	exit(0);

}


$path = $_SERVER['REQUEST_URI'];
$path = strtok($path, '?');
$path = str_replace(APP_BASE, '', $path);
$path = trim($path, './');

$path_list = explode('/', $path);
// $path_list = array_filter($path_list);
// var_dump($path_list);


$r0 = sprintf('/%s', $path_list[0]);
// var_dump($r0); exit;

$head = [];
$head['title'] = 'Cras';

$body = null;

ob_start();
switch ($r0) {
	case '/': // v2
	case '/start': // v1
		$head['title'] = 'Cras';
		require_once(APP_ROOT . '/view/start.php');
		break;
	case '/create':
	case '/incoming':
	case '/share':
		// var $C = new ShareController();
		switch ($_POST['a']) {
			case 'todo-delete':
				$dbc = _dbc();
				$dbc->query('DELETE FROM todo WHERE id = :t0', [ ':t0' => $_POST['id'] ]);
				__exit_301('/start');
			case 'todo-update':
				$update = [];
				$update['name'] = $_POST['name'];
				$update['note'] = $_POST['note'];
				$dbc = _dbc();
				$dbc->update('todo', $update, [ 'id' => $_POST['id'] ]);
				__exit_301('/start');
		}

		$head['title'] = 'Cras Share Incoming';
		require_once(APP_ROOT . '/view/share.php');

		break;

	case '/todo':
		$head['title'] = 'Cras Todo';
		$id = $path_list[1];
		switch ($_POST['a']) {
			case 'todo-delete':
				$dbc = _dbc();
				$dbc->query('DELETE FROM todo WHERE id = :t0', [ ':t0' => $id ]);
				__exit_301('/start');
				break;
			case 'todo-update':
				$update = [];
				$update['name'] = $_POST['name'];
				$update['note'] = $_POST['note'];
				$dbc = _dbc();
				$dbc->update('todo', $update, [ 'id' => $id ]);
				__exit_301('/start');
		}

		array_shift($path_list);

		$_GET['t0'] = array_shift($path_list);

		require_once(APP_ROOT . '/view/todo.php');

		break;
	default:
		__exit_404();
}
$body = ob_get_clean();

require_once(APP_ROOT . '/output/html.php');
