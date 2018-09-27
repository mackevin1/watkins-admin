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

$ocode = KS_Filter::inputSanitize ( $_POST ['ocode']);

$objOption = new KS_Option ();
$objOption->setCode($ocode);
$objOption->select();

$setting_readonly = $objOption->getReadonly();

$option_group = KS_Filter::inputSanitize ( $_POST ['option_group']);
$option_code = KS_Filter::inputSanitize ( $_POST ['option_code'] );
$option_desc = KS_Filter::inputSanitize ( $_POST ['option_desc'] );
$option_value = trim ( $_POST ['option_value'] );

//serialize input $option_value
/*$arroption = array();
 $arroption['menuo_layout'] = $menuo_layout;
 $optionlayout = serialize($arroption);*/

$objOption = new KS_Option();
if($setting_readonly == 1){
	$objOption->setCode($ocode);
}else{
	$objOption->setGroup($option_group);
	$objOption->setCode($option_code);
}
$objOption->setDesc($option_desc);
$objOption->setValue($option_value);

if($objOption->exists()){
	$objOption->update();
}else{
	$objOption->insert();
}

//get all group in array list
$arrOption = KS_Option::getGroupList();

$tabId = 0;

foreach ($arrOption AS $curOption => $u){
	if(($u [$option_group])== $option_group){
		$tabId = $curOption;
	}
}

if($setting_readonly == 1){
	$option_code = $ocode;
}

$redirect = "display.php?ocode=$option_code&$tabId=$tabId&msg=option_updated";

header ( "Location: $redirect" );