<?php

if (! is_file ( 'config.php' )) {
	header ( "Location: ks_admin/admin-install/index.php" );
	exit ();
} else {
	include_once 'library.php';
	// check authentication
	$isAuth = CUSTOM_User::checkAuthentication ();
	
	if ($isAuth) {
		header ( "Location: home.php" );
		exit ();
	} else {
		header ( "Location: ks_user/login.php" );
		exit ();
	}
}