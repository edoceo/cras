<?php
/**
 * Edoceo Cras Bootstrap
 */

define('APP_ROOT', __DIR__);
define('APP_BASE', '/cras');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once(__DIR__ . '/vendor/autoload.php');

function _dbc()
{
	static $dbc = null;

	if (empty($dbc)) {
		// $cfg = \OpenTHC\Config::get('database');
		// $c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
		// $u = $cfg['username'];
		// $p = $cfg['password'];
		// $dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);
		$dsn = sprintf('sqlite:%s/var/cras.sqlite', APP_ROOT);
		$dbc = new \Edoceo\Radix\DB\SQL($dsn);
	}

	return $dbc;

}

function __exit_301($path)
{

	// if (!empty($_SERVER['HTTP_SEC_FETCH_DEST'])) {
	// 	$sfd = $_SERVER['HTTP_SEC_FETCH_DEST'];
	// 	if ('empty' == $sfd) {
	// 		__exit_text('', 200);
	// 	}
	// }

	$path = trim($path, '/');
	$path = sprintf('%s/%s', APP_BASE, $path);

	header('HTTP/1.1 302 See Other', true, 302);
	header(sprintf('location: %s', $path));

	$body = sprintf('<p>See: <a href="%s>%s</a></p>', $path, $path);

	__exit_html($body, 302);

}

function __exit_404()
{
	header('HTTP/1.1 404 Not Found', true, 404);

	$body = '<div class="container"><h1>404 Not Found</h1></div>';
	ob_start();
	require_once(APP_ROOT . '/output/html.php');
	$html = ob_get_clean();

	__exit_html($html, 404);

}

function _link($link)
{
	$link = ltrim($link, '/');
	return sprintf('%s/%s', APP_BASE, $link);
}

function _markdown($t)
{
	static $pde;
	if (empty($pde)) {
		$pde = new ParsedownExtra();
	}

	return $pde->text($t);

}
