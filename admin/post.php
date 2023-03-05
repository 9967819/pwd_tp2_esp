<?php
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../model.php';
require_once __DIR__ . '/../config.local.php';


function render_form_view($context = array()) {
	# Create a new article. (View) 
	global $context; #must keep this because php 

	$today = date("Y-m-d");

	#if ($_SERVER['REQUEST_METHOD'] == 'POST'){ 
	#	$data = $_POST;
	#	return process_form_data($context, $data);
	#}
	#
	$template = 'form';
	$categories = select_categories(DSN, 'categories', DB_USER, DB_PASSWORD) or die("omg, the server has exploded!\n");
	$html_output = '';
	
	foreach($categories as $c){
		$html_output .= sprintf("<option value=%s>%s</option>", $c['id'],
			$c['name']);
	}
	
	$context['categories'] = $html_output;
	$context['pub_date'] = $today;
	$context['callback'] = 'process_form_data';
	$context['header'] .= "<p><a href=index.php>Home</a></p>";
	#var_dump($context);
	$output = render_html_template($template, $context);
	return $output;
}

function process_form_data($context = array(), $data = array()){
	# basic form processing.(create article)
	$output = "";
	$url_to = "index.php";
	$error = false;

	$result = insert_article(DSN, 'articles', DB_USER, DB_PASSWORD, $data);
	
	if ( $result === false ) {
		header("Location: $url_to");
		exit;
	} else { 	
		http_response_code(500);
		$output = "Server error inserting data in db! Please try again later.\n";
		#var_dump($result);
		return $output;
	}
}
