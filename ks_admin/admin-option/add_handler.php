<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

// check form token to avoid form hijacking / CSRF
$ks_tokenpost = preg_replace ( '/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_token'] );
$ks_scriptname = preg_replace ( '/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_scriptname'] );
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = $_SESSION [$ks_tokenid];

if ($ks_tokenpost != $ks_token) {
	$redirect = "$ks_scriptname.php?msg=csrf_invalid";
	header ( "Location:$redirect" );
	exit ();
}

$option_group = KS_Filter::inputSanitize ( $_POST ['option_group'] );
$option_code = KS_Filter::inputSanitize ( $_POST ['option_code'] );
$option_desc = KS_Filter::inputSanitize ( $_POST ['option_desc'] );
$option_value = trim ( $_POST ['option_value'] );

$objOption = new KS_Option ();
$objOption->setGroup ( $option_group );
$objOption->setCode ( $option_code );
$objOption->setDesc ( $option_desc );
$objOption->setValue ( $option_value );
$objOption->insert ();

// get all group in array list
$arrOption = KS_Option::getGroupList ();

$tabId = 0;

foreach ( $arrOption as $curOption => $u ) {
	
	if (($u [$option_group]) == $option_group) {
		$tabId = $curOption;
	}
}

$redirect = "list.php?tabId=$tabId&msg=option_added";

header ( "Location: $redirect" );