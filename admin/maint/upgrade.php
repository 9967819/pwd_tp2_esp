<?php
# Admin functions for installing the site.

require_once(__DIR__ . "/../config.local.php");

try {
	$dbh = new PDO(DSN, $db_user, $db_password) ;
} catch (PDOException $e) {
	echo("Holy shit. Seems like you did something bad bro.\n");
	if (DEBUG==true) {
		die($e . "\n");
	}	

}

function create_database($db_name, $dbh, $options = array()) {
	# Creates the db and setup default user without using phpmyadmin. >:)
	$sql = "CREATE DATABASE $db_name DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
	$dbh->query($sql);
	#$dbh->commit();

	return false;
}

function create_admin_user($db_name, $dbh, $db_user, $db_password, $options = array()){
	$sql = "CREATE USER '?'@'%' IDENTIFIED BY '?';";
	$dbh->prepare($sql);
	$dbh->execute([$db_user, $db_password]);
	#$dbh->commit();
	return false;
}

function install_schema($dbh, $sql, $options = array()){
	# do the installation of schema.sql (creates tables, users, etc)
	# @param $dbh : the database connection instance.
	# @param $sql : the sql query for creating the tables.
	# @param $options : Optional arguments to pass to PDO.
	
	$dbh->prepare($sql);
	$dbh->execute($#options);
	$dbh->commit();
	
	e#cho "Schema installation completed.\n";

	return false;
}

function install_initial_data($dbh, $sql, $options = array()){
	# perform the installation of initial data for development.
	# @param $dbh : the db connection instance.
	# @param $sql : the sql query ("INSERT...")  
	# @param $options: Extra arguments/parameters for SELECT. (hash) 
	return false;
}

#install_schema($dbh, $sql) 
create_database($db_name, $dbh);

