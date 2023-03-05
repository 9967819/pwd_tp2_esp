<?php
error_reporting(E_ALL);

require_once __DIR__ . '/includes/template.php';
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/config.local.php';
require_once __DIR__ . '/admin/post.php';
require_once __DIR__ . '/admin/edit.php';
require_once __DIR__ . '/admin/delete.php';

$charset = 'utf8';
# Template context defaults.  
$context = array(
	'header' => '',
	'footer' => ''
);
global $context;

$output = '';
$query_param  = 'id'; # the http request param used for controlling the script/controller output.
$request_is_authenticated = $debug ? true : false;
$endpoints = array(
	#'login' 	   => 'admin/login.php',
	'render_form_view'   => 'admin/post.php',
	'render_detail_view' => 'includes/detail.php',
	'render_edit_view'   => 'admin/edit.php',
	'render_delete_view' => 'admin/delete.php'
	#'install' 	   => 'admin/install.php'
	);

### Controller response handling start
if ( $_SERVER['REQUEST_METHOD'] != 'POST' && 
	isset($query_param, $_GET) && 
	! empty($_GET[$query_param])){
	$endpoint = $endpoints[$_GET[$query_param]];
	# if the view endpoint exists in admin, just use
	# that to render the specified page.
	if (file_exists(ABSPATH . $endpoint)){
		require_once ABSPATH . $endpoint;
		$cb = $_GET[$query_param];
		# check here for pk id...
		if ($cb == 'render_detail_view' && ! empty($_GET['pk'])){
			$id = $_GET['pk'];
			$output .= call_user_func($cb, $context, $id);
		} else{ 	
			$output .= call_user_func($cb, $context);
		}
	} else {
		# error no view module found :(
		http_response_code(400);
		$output = "Fatal error: No such module found ($endpoint)";
	}	
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if ( array_key_exists('callback', $_POST) ) {
		$cb = $_POST['callback']; # the post view : ie: process_form_data
		unset($_POST['callback']);
	} else {
		http_response_code(400) ;
		echo "Form data ìs missing callback token!";
	}
	$output = call_user_func($cb, $context, $_POST);
} else {
	# Default landing page for non-authenticated users.
	$template = 'index';
	
	# db query goes here. 
	# Note: pub_date is date to complete a task
	$options = ['order_by' => 'articles.pub_date',
		    'group_by' => 'title'];
	$articles = select_articles(DSN, 'articles', $db_user, $db_password, $options);
	#var_dump($articles);

	$block = "<ul>";
	foreach($articles as $row){
		#FIXED
		$url_to = sprintf("index.php?id=render_detail_view&pk=%s", $row['id'] );
		$block .= "<li><a href=" . $url_to . ">" . $row['pub_date'] . ": " . $row['title'] ."</a></li>";
	}
	$block .= "</ul>";

	if ($request_is_authenticated) {
		# navigation menu block (Note: debug mode is implied!!) 
		$menu = "<p><a href=index.php?id=render_form_view>Ajouter une tâche</a></p>";
	} else {
		# XXX Unauthenticated users must login first. >:)
		$menu = "<p><a href=index.php?id=login&next=$l>Login</a></p>";
	} 

	$context['header'] = $menu;
	$context['todolist'] = $block;

	$output .= render_html_template($template, $charset, 'r', $context) ;

}

echo $output;
