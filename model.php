<?php
# vim: set ts=4 sw=4 expandtab
# Create your models here. :)
error_reporting(E_ALL);

require_once(__DIR__ . "/config.local.php");



#var_dump($dsn);

function create_mysql_user($dsn, $user, $password = null, $options = array()){
	# Creates a standard user for managing the db. If $password is not set or null, 
	# generate a new password dynamically. 
	
	# set this to change password by default
	$update_password = true;

	if ($password == null) {
		# XXX: use password_hash here
		$real_password = substr(str_shuffle(hash(CRYPTO_FUNCTION, CRYPTO_SALT)), 0, 8);
		echo sprintf("New admin password created: [%s]\n", $real_password);
		echo "Please keep it in a safe place.\n";
	}
	# NB: this functions needs to be run as root, so we can create the user
	# in the db. 	
	# $dbh = new PDO($dsn, $user, $password, $options);
	# var_dump($dbh);
	# 1. must stop script unless $user == 0
	# 2. if the current user is root, attempt to create sql user with proper
	# permissions. 
	$posix_user = posix_getpwuid(posix_geteuid());
	if ($posix_user['uid'] != 0) {
		die("You're not root, Aborting.\n");
		return true;
	}
	$dbh = new PDO($dsn, $user, $password);
	# Update with new password
	if (isset($real_password) && $update_password == true){
		$sql = $dbh->prepare("ALTER USER `?`@`?` IDENTIFIED BY `?`");
		$sql->execute([$user, DB_HOST, $real_password]);
		# flush privileges
		$sql = $dbh->prepare("FLUSH PRIVILEGES");
		$sql->execute();
		# Then commit
		$sql->commit();
		echo "Password updated.";
	}	
	return false;

}
function select_articles($dsn, $table, $user, $password, $options = array())
{
	$fetch_mode = PDO::FETCH_ASSOC; 

	$month = date('Y-m-d');
	if (array_key_exists('order_by', $options)){
		$order_by = $options['order_by'];
	} else {
		$order_by = 'pub_date';
	}
	$group_by = $options['group_by'];
	$dbh = new PDO($dsn, $user, $password) or die("Fatal error connecting to db!\n");
	$sql = "SELECT articles.id,articles.title,articles.pub_date from $table LEFT JOIN categories ON articles.category_id=categories.id ORDER BY $order_by DESC";

	$stmt = $dbh->query($sql);
	#$stmt->execute();
	$articles = $stmt->fetchAll($fetch_mode);
	
	return $articles;
}

function select_categories($dsn, $table, $user, $password, $options = array())
{
	$dbh = new PDO($dsn, $user, $password) or die("boom!\n");
	$sql = "SELECT * from $table";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $categories;
}

function select_record_by_id($dsn, $table, $user, $password, $id, $options = array())
{
	$dbh = new PDO($dsn, $user, $password) or die("Connection error with db!\n");
	$sql = "select * from $table where id=$id";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch();
	return $result;
}


function insert_article($dsn, $table, $user, $password, $data = array(), $options = array())
{
	$dbh = new PDO($dsn, $user, $password) or die("Connection error with db!\n");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT INTO $table(title, description, full_text, pub_date, category_id) VALUES (:title, :description, :full_text, :pub_date, :category_id)";
	try {
		$stmt = $dbh->prepare($sql);
		$stmt->execute($data) ;
	} catch (PDOException $e) {
		echo "Insert error with pdo: " . $e->getMessage();
		return true;
	}

	return false;
}

function delete_article_by_id($dsn, $table, $user, $password, $data)
{
	$dbh = new PDO($dsn, $user, $password);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE from $table WHERE id=:id";
	try { 
		$stmt = $dbh->prepare($sql);
		$stmt->execute($data);
	} catch (PDOException $e) { 
		echo "Fatal error deleting row: " . $e->getMessage();
		return true;
	}
	return false;
}
function update_article($dsn, $table, $user, $password, $data = array(), $options = array())
{
	$dbh = new PDO($dsn, $user, $password) or die("snsfeoinwefe asdhns dsoawnd!!!\n");
	

	#KEEP THIS
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	
	$sql = "UPDATE $table SET title=:title, description=:description, full_text=:full_text, pub_date=:pub_date, category_id=:category_id WHERE id=:id";
	try {
		$stmt = $dbh->prepare($sql);
		$stmt->execute($data);
	} catch (PDOException $e) {
		echo "Fatal error updating record: " . $e->getMessage();
		return true;
	}
	return false;

}

global $categories;
$categories = select_categories(DSN, 'categories', DB_USER, DB_PASSWORD);

