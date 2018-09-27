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

$mid = ( int ) $_POST ['mid'];
$name = KS_Filter::inputSanitize ( $_POST ['name'] );
$menuo_layout = KS_Filter::inputSanitize ( $_POST ['menuo_layout'] );

//serialize input layout
$arroption = array();
$arroption['menuo_layout'] = $menuo_layout;
$optionlayout = serialize($arroption);

$objMenu = new KS_Menu ( );
$objMenu->setId ( $mid );
$objMenu->setName ( $name );
$objMenu->setOption($optionlayout);
$objMenu->update ();

$redirect = "display.php?mid=$mid&tabId=0&msg=menu_updated";

header ( "Location: $redirect" );