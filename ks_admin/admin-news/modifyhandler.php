<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

// check form token to avoid form hijacking / CSRF
$ks_tokenpost = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_token']);
$ks_scriptname = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_scriptname']);
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = $_SESSION [$ks_tokenid];

if ($ks_tokenpost != $ks_token) {
	$redirect = "$ks_scriptname.php?msg=csrf_invalid";
	header ( "Location:$redirect" );
	exit ();
}

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$nid = KS_Filter::inputSanitize ( $_POST ['nid'] );
$nwtitle = KS_Filter::inputSanitize ( $_POST ['nwtitle'] );
$nwDesc = KS_Filter::inputSanitize ( $_POST ['nwDesc'] );
$nwstartDate = KS_Date::toYYYY_MM_DD ( $_POST ['nwstartDate'] );
$nwendDate = KS_Date::toYYYY_MM_DD ( $_POST ['nwendDate'] );
$nwprivate = (int)KS_Filter::inputSanitize ( $_POST ['nwprivate'] );
$nwstatus = (int)KS_Filter::inputSanitize ( $_POST ['nwstatus'] );

$nwpublic = 0;
if (isset ($_POST ['nwpublic'])) {
	$nwpublic = ( int ) $_POST ['nwpublic'];
}

$objNews = new KS_News ();
$objNews->setId ( $nid );
if (! $objNews->exists ()) {
	header ( "Location: list.php?e=notfound&nid=$nid" );
	exit ();
}

$objNews->setTitle ( $nwtitle );
$objNews->setDesc ( $nwDesc );
$objNews->setStartDate ( $nwstartDate );
$objNews->setEndDate ( $nwendDate );
$objNews->setPublic ( $nwpublic );
$objNews->setPrivate ( $nwprivate );
$objNews->setSender ( $usr_id );
$objNews->setStatus ( $nwstatus );
$objNews->setModifiedBy ( $usr_id );
$objNews->setModifiedDate ( date ( "Y-m-d" ) );
$objNews->update ();

$redirect = "display.php?nid=$nid&msg=updated";

header ( "Location: $redirect" );