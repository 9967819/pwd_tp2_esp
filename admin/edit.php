<?php
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../model.php';
require_once __DIR__ . '/../config.local.php';


function render_edit_view($context = array()) {
	# Edit a new article. 
	global $context, $categories; #must keep this because php 

	#$today = date("Y-m-d");

	# Primary key
	$pk = $_GET['pk'];

	$template = 'update'; # THIS 
	$instance = select_record_by_id(DSN, 'articles', DB_USER, DB_PASSWORD, $pk);
	$category = select_record_by_id(DSN, 'categories', DB_USER, DB_PASSWORD, $instance['category_id']);

	$context['callback'] = "update_object_wrapper";
	$context['pk'] = $pk;
	$context['title'] = $instance['title'];
	$context['description'] = $instance['description'];
	#$context['summary'] = $instance['summary'];
	$context['full_text'] = $instance['full_text'];
	$context['pub_date'] = $instance['pub_date'];
	$context['categoryid'] = $category['id'];
	$context['categoryname'] = $category['name'];
	$category_block = '';
	foreach($categories as $value) {
		$cid = $value['id'];
		$category_block .= "<option value=$cid>" . $value['name'] . "</option>";
	}
	$context['categories'] = $category_block;

	#$html_output = '';
	
	$output = render_html_template($template, $context);
	return $output;
}

function update_object_wrapper($context, $data = array()){
	# basic form processing.
	$url_to = "index.php"; # location for redirect
	echo "hello smart!";

	$result = update_article(DSN, 'articles', DB_USER, DB_PASSWORD, $data);
	
	if ( $result === false ) {
		header("Location: $url_to");
		exit;
	} else { 	
		http_response_code(500);
		$output = "Server error UPDATING data in db! Please try again later.\n";
	}	
	return $output;
}
