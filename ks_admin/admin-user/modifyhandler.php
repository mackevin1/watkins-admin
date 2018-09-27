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

$id = KS_Filter::inputSanitize ( $_POST ['id'] );

$usr_email = KS_Filter::inputSanitize ( $_POST ['usr_email'] );
$usr_enabled = KS_Filter::inputSanitize ( $_POST ['usr_enabled'] );
$usr_name = KS_Filter::inputSanitize ( $_POST ['usr_name'] );
$usr_password = KS_Filter::inputSanitize ( $_POST ['usr_password'] );
$usr_phone_mobile = KS_Filter::inputSanitize ( $_POST ['usr_phone_mobile'] );
$usr_phone_office = KS_Filter::inputSanitize ( $_POST ['usr_phone_office'] );
$usr_role = KS_Filter::inputSanitize ( $_POST ['usr_role'] );

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header ( "Location: list.php?msg=userid_notfound&usr_id=$id" );
	exit ();
}

//is email valid?
$validator = new Zend_Validate_EmailAddress();
if ( ! $validator->isValid( $usr_email )) {
	header ( "Location: modify.php?msg=email_invalid&id=$id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled" );
	exit ();
}

//is email taken?
$userid_emailTaken = CUSTOM_User::emailTaken($usr_email, $id);
if($userid_emailTaken) {
	header ( "Location: modify.php?msg=email_taken&id=$id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&userid_emailTaken=$userid_emailTaken" );
	exit ();
}

$objUser->setEmail ( $usr_email );
$objUser->setEnabled ( $usr_enabled );
$objUser->setName ( $usr_name );
if (strlen ( $usr_password )) {
	$objUser->setPassword ( md5( $usr_password ) );
}
$objUser->setPhoneMobile ( $usr_phone_mobile );
$objUser->setPhoneOffice ( $usr_phone_office );
$objUser->setRole ( $usr_role );

$objUser->update ();

header ( "Location: display.php?msg=updated&id={$objUser->getId()} " );
