<?php

$configFile = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'config.php';

// we want to check if config.php exists as some cases where the file is
// purposedly deleted or renamed
if (! file_exists ( $configFile )) {
	if (file_exists ( 'ks_builtin' )) {
		header ( "Location: ks_builtin/fatalerror.php?msg=config_notfound" );
	} else if (file_exists ( '../ks_builtin' )) {
		header ( "Location: ../ks_builtin/fatalerror.php?msg=config_notfound" );
	} else if (file_exists ( '../../ks_builtin' )) {
		header ( "Location: ../../ks_builtin/fatalerror.php?msg=config_notfound" );
	}
	exit ();
}

include_once ('config.php');

if (! defined('KSCONFIG_CONTROLPANEL_PATH')) {
	define ('KSCONFIG_CONTROLPANEL_PATH', KSCONFIG_ABSPATH . 'ks_admin/' );
}

// identify the library folder and add into php include_path
if (substr ( PHP_OS, 0, 3 ) == 'WIN') {
	set_include_path ( ";" . KSCONFIG_CLASS_PATH . ";" . KSCONFIG_CONTROLPANEL_PATH . 'ks_library/' );
} else {
	set_include_path ( ":" . KSCONFIG_CLASS_PATH . ":" . KSCONFIG_CONTROLPANEL_PATH . 'ks_library/' );
}

/**
 * autoloading KS classes *
 */
// uncomment this if Zend is not configured in PHP.ini include path
include_once KSCONFIG_CLASS_PATH . 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance ();

$autoloader->registerNamespace ( 'KS_' );
$autoloader->registerNamespace ( 'Zend_' );
$autoloader->registerNamespace ( 'CUSTOM_' );

// check if KSCONFIG_URL doesnt have trailing slash
if (! preg_match ( '|/$|', KSCONFIG_URL )) {
	// then only add /
	define ( 'KSCONFIG_URL', KSCONFIG_URL . '/' );
}

if (! defined ( KSCONFIG_DB_PORT )) {
	define ( KSCONFIG_DB_PORT, '3306' ); // 3306 is default port for mysql
}

// retrieve array from config.php
$configArray = array (
		'database' => array (
				'host' => KSCONFIG_DB_HOST,
				'username' => KSCONFIG_DB_USER,
				'password' => KSCONFIG_DB_PASSWORD,
				'dbname' => KSCONFIG_DB_NAME,
				'port' => KSCONFIG_DB_PORT 
		) 
);

$ks_config = new Zend_Config ( $configArray );

$dbParams = $ks_config->database->toArray ();

$ks_db = new Zend_Db_Adapter_Pdo_Mysql ( $dbParams );

//try connection.. if failed, display fatalerror 
try {
	$ks_db->getConnection();
	
} catch (Exception $e) {
	//store error in session as error msg contains special chars
	session_start();
	$_SESSION['ks_error'] = $e->getMessage();
	header("Location: " . KSCONFIG_URL . "ks_builtin/fatalerror.php?msg=db_error" );
}
	
// create log file.. if failed, display fatalerror
if (is_writable ( KSCONFIG_ERROR_LOG )) {
	$ks_logstream = @fopen ( KSCONFIG_ERROR_LOG, 'a+', false );
	
	$ks_logwriter = new Zend_Log_Writer_Stream ( $ks_logstream );
	$ks_log = new Zend_Log ( $ks_logwriter );
} else {
	$ks_log_unwritable = 1;
	//we create a dummy class to emulate Zend_Log, without triggering error
	class ksLog {
		public function info($a) {
		}
	}
	$ks_log = new ksLog();
}

// we first create a dummy class.. to support translation in the future
class ksTranslate {
	public function _($a) {
		return $a;
	}
}
$ks_translate = new ksTranslate ();
