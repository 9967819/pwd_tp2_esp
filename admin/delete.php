<?php
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../model.php';
require_once __DIR__ . '/../config.local.php';


function render_delete_view($context = array()) {
	# METHODS : GET 
	# args : `pk` (required)
	# Show a form in order to confirm removal of a
	# specific record.
	global $context, $categories; #must keep this because php 

	# XXX The primary key needs to be properly validated !
	$pk = $_GET['pk'];

	$template = 'delete'; # THIS 
	$instance = select_record_by_id(DSN, 'articles', DB_USER, DB_PASSWORD, $pk);
	#$category = select_record_by_id(DSN, 'categories', DB_USER, DB_PASSWORD, $instance['category_id']);

	$context['callback'] = "delete_object_wrapper";
	$context['pk'] = $pk;
	$context['title'] = $instance['title'];
	#$context['description'] = $instance['description'];
	#$context['summary'] = $instance['summary'];
	#$context['full_text'] = $instance['full_text'];
	#$context['pub_date'] = $instance['pub_date'];
	#$context['categoryid'] = $category['id'];
	#$context['categoryname'] = $category['name'];
	#$category_block = '';
	#foreach($categories as $value) {
	#		$cid = $value['id'];
	#	$category_block .= "<option value=$cid>" . $value['name'] . "</option>";
	#}
	#$context['categories'] = $category_block;

	#$html_output = '';
	
	$output = render_html_template($template, $context);
	return $output;
}

function delete_object_wrapper($context, $data = array()){
	$url_to = "index.php"; # location for redirect
	if (array_key_exists('callback', $data)){
		unset($data['callback']);
	}	
	$result = delete_article_by_id(DSN, 'articles', DB_USER, DB_PASSWORD, $data);
	
	if ( $result === false ) {
		header("Location: $url_to");
		exit;
	} else { 	
		http_response_code(500);
		$output = "<p>Server error DELETING data in db! Please try again later.</p>";
	}	
	return $output;
}
