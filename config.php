<?php
error_reporting(E_ALL|E_STRICT);

# Global configuration options.
if (! defined('ABSPATH')){
	define('ABSPATH', __DIR__ . '/' );
}

### Please don't touch anything below this line! >:)
#define('PACKAGE_VERSION', '0.1');
#define('PACKAGE_NAME', 'pwd_tp2');
#define('PACKAGE_URL', 'https://github.com/JackBortoneLab/pwd_tp2');
#define('AUTHOR', 'Jack Bortone');
#define('AUTHOR_ALIAS_DEPRECATED', 'Etienne Robillard');
#define('AUTHOR_EMAIL', '9967819@cstj.qc.ca');

# Security
define('CRYPTO_SALT', 'pleaseusepasswordhashnexttime');
define('CRYPTO_FUNCTION', 'sha256');
#define('ENABLE_XSRF', true);

# Theme/UI options
define('ENABLE_BOOTSTRAP', false);

# Database configuration setup
define('DB_HOST', $db_host);
define('DB_NAME', $db_name);
define('DB_USER', $db_user);
define('DB_PASSWORD', $db_password);
define('DB_CHARSET', 'utf8mb4');


### Experimental options. For wizards only. >:)
define('ENABLE_PDO', 1);
define('ENABLE_MYSQLI', 0);
define('ENABLE_REDIS', 0);
define('ENABLE_CADDY', 0);
#define('ENABLE_HTTP2', 1); # 2=force HTTP2 mode; 1=try using HTTP2 (default); 0=disabled

# Template backend options.
define('TEMPLATE_DIR', $template_dir);
define('TEMPLATE_SUFFIX', 'html');
define('DSN', sprintf("mysql:host=%s;dbname=%s;charset=%s", DB_HOST, DB_NAME, DB_CHARSET));
define('DEBUG', (int)($debug==2) ? 1 : 0);
define('ERROR_LOG', ABSPATH . 'error.log');
# never enable this unless you know what you are doing. :P
define('ENABLE_WIZARD_MODE', (bool)(DEBUG ? true : false));
define('ENABLE_HACKER_MODE', (bool)(ENABLE_WIZARD_MODE)); # For maintainers. 
define('ENABLE_DEVELOPER_MODE', ENABLE_HACKER_MODE); # yep this is true
define('SECURE_LEVEL', 2);
if (SECURE_LEVEL > 1 && ENABLE_HACKER_MODE == true) {
	define('ALLOWED_IPS', $allowed_hosts );
} else {
	define('ALLOWED_IPS', array('127.0.0.1'));	
} 
### End of config. 
### Have fun!
