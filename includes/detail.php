<?php
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../model.php';
require_once __DIR__ . '/../config.local.php';


function render_detail_view($context = array(), $id) {
	global $context; #must keep this because php 

	#$today = date("Y-m-d");

	$template = 'detail';
	
	$object = select_record_by_id(DSN, 'articles', DB_USER, DB_PASSWORD, $id);
	
	$context['pk'] = $id; # de primaree ki :D
	$context['title'] = $object['title'];
	
	if (empty($object['description'])){
		$context['description'] = "Aucune description";
	} else {
		$context['description'] = $object['description'];
	}
	
	$context['full_text'] = $object['full_text'];
	$context['pub_date'] = $object['pub_date'];
	# FIXME: this needs to be improved!
	$context['category'] = select_record_by_id(DSN, 'categories', DB_USER, 
		DB_PASSWORD, $object['category_id'])['name'];

	$output = render_html_template($template, $context);
	return $output;
}
