<?php

include_once '../library.php';

// check if really coming from login.php
$loginUrl = KSCONFIG_URL . "ks_user/login.php";

$serverReferer = explode ( "?", $_SERVER ['HTTP_REFERER'] );

if (! preg_match ( "|$loginUrl|", $serverReferer [0] )) {
	header ( "Location: login.php?msg=referrer" );
	exit ();
}

// next, check for secret key set in login.php,
$key = KS_Filter::inputSanitize ( $_POST ['key'] );
$value = KS_Filter::inputSanitize ( $_POST ['value'] );
$red = KS_Filter::inputSanitize ( $_POST ['red'] );
if ((strlen ( $key ) == 0) || (strlen ( $value ) == 0)) {
	header ( "Location: login.php?msg=missingkey" );
	exit ();
}

//this is the secretkey combination.. also used in loginhandler.php
//so if changed here, must change there as well..
//otherwise, the login will always fail..
$strSecret = $key . date ( "Y-m-d" );
if ($value != sha1 ( $strSecret )) {
	header ( "Location: login.php?msg=missingvalue" );
	exit ();
}

// filter login input
$userid = KS_Filter::inputSanitize ( $_POST ['userid'] );
$password = KS_Filter::inputSanitize ( $_POST ['password'] );

if (strlen ( $userid ) == 0) {
	header ( "Location: login.php?msg=userid_empty&userid=$userid" );
	exit ();
}
if (strlen ( $password ) == 0) {
	header ( "Location: login.php?msg=password_empty&userid=$userid" );
	exit ();
}

// check auth
$objUser = new CUSTOM_User ();
$objUser->setId ( $userid );
$objUser->setPassword ( $password );
// perform authentication and set SESSIONS if auth successful..
$objUser->authenticate ();

// is user found?
if ($objUser->getAuthNotfound ()) {
	header ( "Location: login.php?msg=user_notfound&userid=$userid" );
	exit ();
}

// is wrong password entered?
if ($objUser->getAuthWrongpassword ()) {
	header ( "Location: login.php?msg=password_wrong&userid=$userid" );
	exit ();
}

// is user disabled?
if ($objUser->getAuthDisabled ()) {
	header ( "Location: login.php?msg=user_disabled&userid=$userid" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $userid );
$objUser->setLastlogin ( date ( "Y-m-d H:i:s" ) );
$objUser->update ();

// HERE user already authorized and SESSION is set in
// CONFIG_USER::authenticate() in CUSTOM/User.php
if(!strlen ($red)) {
	$dest = "../home.php";
} else {
	$dest = urldecode( $red );
}
header ( "Location: $dest" );