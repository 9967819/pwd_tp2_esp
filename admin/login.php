<?php
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../model.php';
require_once __DIR__ . '/../config.local.php';


function login($context = array()) {
	# Create a new article. (View) 
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		return process_user_data($context);
	}
	global $context; #must keep this because php 
	$template = 'login';
	$form = <<<HTML
	<p><label for=username>Username: </label><input name=username type=text required></p>
	<p><label for=password>Password: </label><input name=password type=password required></p>
	<p><button type="submit">Login</button>&nbsp;<button type=reset>Try again</button></p>
HTML;
	$context['form'] = $form;
	$context['xsrf'] = '1234abcll334411c';

	$output = render_html_template($template, $context);
	return $output;
}

function process_user_data($context = array()){
	# a basic form processing view.
	$template = 'form';
	$output = "";
	if (ENABLE_DEVELOPER_MODE) {
		$output .= "<p><strong>Developer mode is enabled!</strong></p>";
	}
	http_response_code(401);
	return $output;
}
