<?php

/**
 * This method can be used, but is currently not being used.
 * Still have to work out how to overwriteit.
 * 
 * Example use:
 * @example include_once 'library.php';
 * require_once 'KS/Exception.php';
 * set_exception_handler('KS_Exception');
 * $ks_db->getConnection ();
 * if(!$value) {
 * 	throw new KS_Exception('something xyz');
 * }
 *
 */

//require_once 'Zend/Exception.php';

class KS_Exception extends Zend_Exception {
	
	public static function defaultHandler($exception) {
		
		$error_page = KSCONFIG_ABSPATH . 'ks_builtin/error.php';
		$error_page_url = KSCONFIG_URL . 'ks_builtin/error.php';
		$exceptionMessage = "Handled by default exception handler" . $exception;
		
		//check if error page exists.
		if ( file_exists($error_page)) {
			
			$_SESSION ['exception'] = $exception;
			header ( "Location: $error_page_url" );
			exit ();
		} else {
			//otherwise, simply display it
			echo $exceptionMessage;
		}
	}
	
}

