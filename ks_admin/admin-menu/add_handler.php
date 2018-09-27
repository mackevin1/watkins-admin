<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

// check form token to avoid form hijacking / CSRF
$ks_tokenpost = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_token']);
$ks_scriptname = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_scriptname']);
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = $_SESSION [$ks_tokenid];
echo $ks_token;

if ($ks_tokenpost != $ks_token) {
	$redirect = "$ks_scriptname.php?msg=csrf_invalid&ks_tokenpost=$ks_tokenpost";
	//header ( "Location:$redirect" );
	exit ();
}

$name = KS_Filter::inputSanitize ( $_POST ['name'] );
$menuo_layout = KS_Filter::inputSanitize ( $_POST ['menuo_layout'] );

//serialize input layout
$arroption = array();
$arroption['menuo_layout'] = $menuo_layout;
$optionlayout = serialize($arroption);

$objMenu = new KS_Menu ( );
$objMenu->setName ( $name );
$objMenu->setOption($optionlayout);
$objMenu->insert ();

$redirect = "display.php?mid={$objMenu->getId()}&tabId=1&msg=menu_added";

header ( "Location: $redirect" );