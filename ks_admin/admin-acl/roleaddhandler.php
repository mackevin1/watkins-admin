<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$role_id = KS_Filter::inputSanitize ( $_POST ['role_id'] );
$role_name = KS_Filter::inputSanitize ( $_POST ['role_name'] );
$role_desc = KS_Filter::inputSanitize ( $_POST ['role_desc'] );

if (strlen ( $role_id ) == 0) {
	header ( "Location: roleadd.php?role_id=$role_id&msg=roleid_empty" );
	exit ();
}

if (strlen ( $role_name ) == 0) {
	header ( "Location: roleadd.php?role_name=$role_name&msg=rolename_empty" );
	exit ();
}

$objRole = new KS_Acl_Role ();
$objRole->setId ( $role_id );
if ($objRole->exists ()) {
	header ( "Location: roleadd.php?role_id=$role_id&role_name=$role_name&msg=role_exists" );
	exit ();
}

$objRole->setName ( $role_name );
$objRole->setDesc ( $role_desc );
$objRole->insert ();

header ( "Location: list.php?tabId={$objRole->getId()}&msg=added" );