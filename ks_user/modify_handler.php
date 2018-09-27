<?php

include_once '../library.php';

if (! class_exists ( 'CUSTOM_User' )) {
	echo "Class User doesn't exist! Use Class Generator to create it. Otherwise the forms will not work.";
	exit ();
}

if (session_id () == '') {
	session_start ();
}

$ks_session_group = KSCONFIG_DB_NAME;
if (isset ( $_SESSION [$ks_session_group] )) {
	$id = $_SESSION [$ks_session_group] ['USR_ID'];
}

if (! strlen ( $id )) {
	header ( "Location: login.php?msg=notlogin" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header("Location: login.php?msg=notlogin");
	exit ();
}

$usr_email = KS_Filter::inputSanitize ( $_POST ['usr_email'] );
$usr_name = KS_Filter::inputSanitize ( $_POST ['usr_name'] );
$usr_password = KS_Filter::inputSanitize ( $_POST ['usr_password'] );
$usr_phone_mobile = KS_Filter::inputSanitize ( $_POST ['usr_phone_mobile'] );
$usr_phone_office = KS_Filter::inputSanitize ( $_POST ['usr_phone_office'] );

$objUser->setEmail ( $usr_email );
$objUser->setName ( $usr_name );
if (strlen ( $usr_password )) {
	$objUser->setPassword ( md5( $usr_password ) );
}
$objUser->setPhoneMobile ( $usr_phone_mobile );
$objUser->setPhoneOffice ( $usr_phone_office );

$objUser->update ();

header ( "Location: profile.php?msg=updated&id={$objUser->getId()} " );
