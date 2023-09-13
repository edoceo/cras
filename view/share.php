<?php
/**
 * Create a new TODO in Cras
 */

$dt = new \DateTime();

$dbc = _dbc();

$todo = [];
$todo['id'] = \Edoceo\Radix\ULID::create();
$todo['name'] = $_POST['title'];
$todo['note'] = $_POST['text'];
$todo['created_at'] = $dt->format(\DateTime::RFC3339);
$todo['updated_at'] = $dt->format(\DateTime::RFC3339);
$todo['expires_at'] = $dt->add(new \DateInterval('P14D'))->format(\DateTime::RFC3339);

$dbc->insert('todo', $todo);

// @todo Redirect to /todo or something?
foreach ($_FILES as $f) {
	$file_output = sprintf('%s/var/%s-%s.bin', APP_ROOT, $todo['id'], rawurlencode($f['name']));
	move_uploaded_file($f['tmp_name'], $file_output);
}

?>

<div class="container">

<h1><a href="<?= _link('/') ?>">Cras</a> :: Create</h1>

<form method="post">

<div class="input-group mb-2">
	<div class="input-group-text">Name:</div>
	<input class="form-control" name="name" value="<?= __h($todo['name']) ?>">
</div>

<!-- <input class="form-control" name="link" value="<?= __h($todo['link']) ?>"> -->

<div class="mb-2">
	<textarea
		class="form-control"
		id="text-output-full"
		name="note"><?= __h($todo['note']) ?></textarea>

	<pre id="text-pending"></pre>
</div>

<div>
	<input name="id" type="hidden" value="<?= __h($todo['id']) ?>">
	<button class="btn btn-secondary" id="btn-text-record" name="a" type="button" value="text-record">Record</button>
	<button class="btn btn-primary" name="a" type="submit" value="todo-update">Update</button>
	<button class="btn btn-danger" name="a" type="submit" value="todo-delete">Cancel</button>
</div>

</form>

<hr>

<pre id="evt-console"></pre>

<pre><strong>_GET</strong>
<?php var_dump($_GET) ?>
<strong>_POST</strong>
<?php var_dump($_POST) ?>
<strong>_FILES</strong>
<?php var_dump($_FILES) ?>
</pre>

</div>

<script>
var SRE = null;

var text_full = '';
var transcript_middle = '';

var $TextPending = $('#text-pending');
var $TextFull = $('#text-output-full');

var $Log = $('#evt-console');

// Console Wrapper
(function(console) {

	var log0 = console.log;
	console.log = function(e) {
		log0(e);
		$Log.append(e);
		$Log.append("\n");
	};

})(console);

// function capitalize(s) {
// 	return s.replace(first_char, function(m) { return m.toUpperCase(); });
// }

if (!('webkitSpeechRecognition' in window)) {
	$('#btn-text-record').removeClass('btn-secondary');
	$('#btn-text-record').addClass('disabled');
} else {

	var SRE = new webkitSpeechRecognition();

	// On Desktop
	// @see https://developer.mozilla.org/en-US/docs/Web/API/SpeechRecognition/continuous
	SRE.continuous = true;
	// @see https://developer.mozilla.org/en-US/docs/Web/API/SpeechRecognition/interimResults
	SRE.interimResults = true;

	console.log(`SRE.continuous = ${SRE.continuous}`);
	console.log(`SRE.interimResults = ${SRE.interimResults}`);

	// On Mobile
	// @see https://stackoverflow.com/questions/39340422/android-webkitspeechrecognition-isfinal-variable-not-showing-correct-value
	SRE.continuous = false;
	SRE.interimResults = false;

	SRE.is_recording = false;
	SRE.lang = 'en-US';

	SRE.onaudioend = function(e) {
		console.log('SRE!onaudioend');
		$TextFull.val(text_full);
	}

	SRE.onaudiostart = function(e) {
		console.log('SRE!onaudiostart');
	}

	SRE.onstart = function() {
		console.log('SRE!onstart');
		SRE.is_recording = true;
		$('#btn-text-record').removeClass('btn-secondary').addClass('btn-success');
	};

	SRE.onerror = function(event) {
		console.log('SRE!onerror');
		// if (event.error == 'no-speech') {
		//   start_img.src = 'mic.gif';
		//   showInfo('info_no_speech');
		//   ignore_onend = true;
		// }
		// if (event.error == 'audio-capture') {
		//   start_img.src = 'mic.gif';
		//   showInfo('info_no_microphone');
		//   ignore_onend = true;
		// }
		// if (event.error == 'not-allowed') {
		//   if (event.timeStamp - start_timestamp < 100) {
		//     showInfo('info_blocked');
		//   } else {
		//     showInfo('info_denied');
		//   }
		//   ignore_onend = true;
		// }
	};

	SRE.onend = function() {

		console.log('SRE!onend');
		SRE.is_recording = false;
		$('#btn-text-record').removeClass('btn-success').addClass('btn-secondary');

		// if (ignore_onend) {
		//   return;
		// }

		// if (window.getSelection) {
		//   window.getSelection().removeAllRanges();
		//   var range = document.createRange();
		//   range.selectNode(document.getElementById('final_span'));
		//   window.getSelection().addRange(range);
		// }
	};

	SRE.onresult = function(event) {

		console.log(`SRE!onresult(${event.resultIndex}, ${event.results.length})`);
		// console.log(event);

		var text_temp = '';

		for (var i = event.resultIndex; i < event.results.length; ++i) {
			console.log(`evt: ${event.results[i].isFinal} / ${event.results[i][0].transcript}`);
			if (event.results[i].isFinal) {
				text_full += event.results[i][0].transcript;
				text_full += "\n\n";
			} else {
				text_temp += '.'; // event.results[i][0].transcript;
			}
		}

		$TextPending.text(text_temp);
		$TextFull.val(text_full);

	};
}

$('#btn-text-record').on('click', function() {
	if (SRE.is_recording) {
		SRE.stop();
	} else {
		SRE.start();
	}
});
</script>
