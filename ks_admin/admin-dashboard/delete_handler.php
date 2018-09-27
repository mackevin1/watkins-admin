<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$id = ( int ) $_POST ['id'];

$objDashboard = new KS_Dashboard ();
$objDashboard->setId ( $id );
if (! $objDashboard->exists ()) {
	header("Location: list.php?msg=notexist&did=$id");
}
$objDashboard->delete ();

/*ks_user(usr_option)*/

$objUser = new CUSTOM_User();
$objUser->setId($usr_id);
if (! $objUser->exists ()) {
	echo "The user with id ($usr_id) does not exist.";
	exit ();
}
$objUser->select();
$arrportletsuser = unserialize($objUser->getOption());
if(count($arrportletsuser[$id])> 0){
	
	unset($arrportletsuser[$id]);//removed portlet

	$objUser = new CUSTOM_User();
	$objUser->setId($usr_id);
	if (! $objUser->exists ()) {
		echo "The user with id ($id) does not exist.";
		exit ();
	}
	$objUser->setOption(serialize($arrportletsuser));
	$objUser->update();
}

$redirect = "list.php?msg=deleted";

header ( "Location: $redirect" );