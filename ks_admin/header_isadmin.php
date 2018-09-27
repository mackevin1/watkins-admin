<?php

//this file checks for valid admin..
//to be included in every single file in admin
$isAdmin = CUSTOM_User::isAdmin ();

if (! $isAdmin) {
	header ( "Location: " . KSCONFIG_URL . "ks_builtin/error.php?msg=notadmin&red=../ks_admin/" );
	exit ();
}