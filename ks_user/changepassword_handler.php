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

$objUser = new CUSTOM_User_Base ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header("Location: login.php?msg=notlogin");
	exit ();
}
$objUser->select();

$currentPwd = KS_Filter::inputSanitize ( $_POST ['currentPwd'] );
$newPwd = KS_Filter::inputSanitize ( $_POST ['newPwd'] );

//check if password is the same
if (md5 ( $currentPwd  . $objUser->getSalt() ) != $objUser->getPassword ()) {
	header ( "Location: changepassword.php?msg=wrong_current" );
	exit ();
}

//now change it
$objUser->setPassword ( md5 ( $newPwd . $objUser->getSalt() ) );
$objUser->update ();

header ( "Location: profile.php?msg=updated_password&id={$objUser->getId()} " );
