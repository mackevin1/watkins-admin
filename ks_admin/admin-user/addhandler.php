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

$usr_id = (string) KS_Filter::inputSanitize ( $_POST ['usr_id'] );
$usr_id = preg_replace("/[^a-zA-Z0-9]+/", "", $usr_id);

$usr_email = KS_Filter::inputSanitize ( $_POST ['usr_email'] );
$usr_email = preg_replace("/[^a-zA-Z0-9.@_]+/", "", $usr_email);

$usr_enabled = (int) $_POST ['usr_enabled'];

$usr_name = (string) KS_Filter::inputSanitize ( $_POST ['usr_name'] );
$usr_name = preg_replace("/[^a-zA-Z0-9_ ]+/", "", $usr_name);

$usr_password = (string) KS_Filter::inputSanitize ( $_POST ['usr_password'] );

$usr_phone_mobile = (string) KS_Filter::inputSanitize ( $_POST ['usr_phone_mobile'] );
$usr_phone_mobile = preg_replace('/[^a-zA-Z0-9\(\)._ ]+/', '', $usr_phone_mobile);

$usr_phone_office = (string) KS_Filter::inputSanitize ( $_POST ['usr_phone_office'] );
$usr_phone_office = preg_replace('/[^a-zA-Z0-9\(\)._ ]+/', '', $usr_phone_office);

$usr_role = (string) KS_Filter::inputSanitize ( $_POST ['usr_role'] );
$usr_role = preg_replace('/[^a-zA-Z0-9\(\)._ ]+/', '', $usr_role);

$queryStringExtra = addslashes( trim ( "usr_phone_mobile=$usr_phone_mobile&usr_phone_office=$usr_phone_office" ));

//invalid user id
if (! preg_match ( "/^[a-zA-Z0-9_]+$/", $usr_id )) {
	header ( "Location: list.php?tabId=1&msg=userid_invalid&usr_id=$usr_id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&$queryStringExtra" );
	exit ();
}

//invalid password
$minLength = (int) KS_Option::getOptionValue('user_password_minlength');
if(!$minLength) {
	$minLength = 8;
}
if (strlen($usr_password) < $minLength) {
	header ( "Location: list.php?tabId=1&msg=password_min&usr_id=$usr_id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&$queryStringExtra" );
	exit ();
}

//is email valid?
$validator = new Zend_Validate_EmailAddress();
if ( ! $validator->isValid( $usr_email )) {
	header ( "Location: list.php?tabId=1&msg=email_invalid&usr_id=$usr_id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&$queryStringExtra" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $usr_id );
//check if user id is taken
if ($objUser->exists ()) {
	header ( "Location: list.php?tabId=1&msg=userid_taken&usr_id=$usr_id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&$queryStringExtra" );
	exit ();
}

//is email taken?
$userid_emailTaken = CUSTOM_User::emailTaken($usr_email);
if($userid_emailTaken) {
	header ( "Location: list.php?tabId=1&msg=email_taken&usr_id=$usr_id&usr_email=$usr_email&usr_role=$usr_role&usr_name=$usr_name&usr_enabled=$usr_enabled&userid_emailTaken=$userid_emailTaken&$queryStringExtra" );
	exit ();
}

$salt = substr ( md5 ( time () ), 0, 6 );

$objUser->setDateCreated ( date("Y-m-d H:i:s") );
$objUser->setEmail ( $usr_email );
$objUser->setEnabled ( $usr_enabled );
$objUser->setName ( $usr_name );

$objUser->setPassword ( md5 ( $usr_password . $salt ) );
$objUser->setSalt ( $salt );

$objUser->setPhoneMobile ( $usr_phone_mobile );
$objUser->setPhoneOffice ( $usr_phone_office );
$objUser->setRole ( $usr_role );

$objUser->insert ();

$id = $objUser->getId();
header ( "Location: display.php?id=$id&msg=added" );

