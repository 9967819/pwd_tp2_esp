<?php
#error_reporting(E_ALL|E_STRICT);

# module for template loading and rendering.

require_once __DIR__ . '/../config.local.php';

#$context = ['user' => 'Jack Bortone',
#	    'date' => date("l")
#];

$default_charset = 'utf8';

function replace_vars($match){
	global $context;
	foreach($context as $c => $value) {
		if ($c==$match[1]){
			return $value;
		}
	}
}

function render_html_template($name, $charset = 'utf8', $mode = 'r', $context = array()) {
	# Load and render a template containing user-defined $variables strings.
	$filename = sprintf("%s/%s.%s", TEMPLATE_DIR, $name, TEMPLATE_SUFFIX);
	$template = fopen($filename, $mode) 
		or die("fatal error opening file: $1\n");
	$input = fread($template, filesize($filename));
	
	$output = preg_replace_callback('/\s?{(\w+)\}/', 'replace_vars', $input);
	
	fclose($template);
	
	return $output;
}

##
