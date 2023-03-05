<?php
global $db_user, $db_name, $db_host, $db_password, $template_dir, $allowed_hosts;

$db_user = 'smart'; # NEVER SET THIS to "root" here unless you accept the security risk !
$db_name = 'pwd_er_tp2';
$db_host = 'localhost';
$db_password = '12345';
$allowed_hosts = array('174.95.127.176', '127.0.0.1');
$template_dir = __DIR__ . "/templates/";
$debug = 2;

include_once __DIR__ . '/config.php';

