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

$today = date ( "Y-m-d" );

$objNews = new KS_News ();
$objNews->setTitle ( $nwtitle );
$objNews->setDesc ( $nwDesc );
$objNews->setStartDate ( $nwstartDate );
$objNews->setEndDate ( $nwendDate );
$objNews->setPublic ( $nwpublic );
$objNews->setPrivate ( $nwprivate );
$objNews->setSender ( $usr_id );
$objNews->setStatus ( $nwstatus );
$objNews->setCreatedBy ( $usr_id );
$objNews->setCreatedDate ( $today );
$objNews->insert ();

$redirect = "list.php?msg=added";

header ( "Location: $redirect" );