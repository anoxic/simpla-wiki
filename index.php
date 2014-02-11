<?php
require 'vendor/bento.php';

function name($_) {
	return preg_replace("/[^a-zA-Z0-9]/", "~", $_).".md";
}

function render($data, $template) {

}

get('/~<*:page>', function($_) {
	$file = name($_);

	if (file_exists($file)) {
		echo nl2br(file_get_contents($file));
	} else {
		halt(404);
	}
});

form('/@<*:page>', function($_) {
	$name = name($_);

	if (request_method('POST')) {
		if (file_put_contents($name, $_POST['content'])) {
			flash('notice', 'Post successfully saved. Yay!');
		} else {
			flash('error', 'Something went horribly wrong :(');
		}
		redirect();
	}

	if (file_exists($name)) {
		$file = e(file_get_contents($name));
	} else {
		$file = "";
	}

	echo "<form method=post><textarea name=content>$file</textarea>"
           . csrf_field()."<button>&gt;</button></form>";
});

return run(__FILE__);
