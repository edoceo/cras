<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="height=device-height, width=device-width, initial-scale=1">
<meta name="theme-color" content="#336699">
<link rel="manifest" href="/cras/manifest.json">
<link rel="stylesheet" href="/cras/vendor/bootstrap/bootstrap.min.css">
<script src="/cras/vendor/jquery/jquery.slim.min.js"></script>
<title><?= $title ?></title>
<style>
html {
	height: 100%;
	width: 100%;
}
body {
	/* height: 100vh; */
	width: 100%;
}
input, textarea {
	display: block;
	max-width: 100%;
	width: 100%;
}
textarea {
	height: 30vh;
}

section.todo {
	border-bottom: 2px solid #111;
	margin: 0 1vw 0.50rem 1vw;
	position: relative;
}
section.todo div.note {
	padding: 0.25rem;
	max-width: 100%;
	overflow: auto;
}
section.todo div.todo-actions {
	position: absolute;
	right: 0;
	top: 0;
}
section.todo div.todo-actions button {
	font-weight: 700;
	margin: 0;
	padding: 0.25rem;
}

div.tag-list-wrap {
	display: flex;
	flex-direction: wrap;
	flex-wrap: wrap;
	margin: 0 1vw;
}
</style>
</head>
<body>

<?= $body ?>

</body>
</html>
