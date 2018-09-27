<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$resource = KS_Filter::inputSanitize ( $_POST ['resource'] );
$curRole = KS_Filter::inputSanitize ( $_POST ['curRole'] );
$resource_new = KS_Filter::inputSanitize ( $_POST ['resource_new'] );
$resource_existing = KS_Filter::inputSanitize ( $_POST ['resource_existing'] );
$privilege_id = KS_Filter::inputSanitize ( $_POST ['privilege_id'] );
$privilege_desc = KS_Filter::inputSanitize ( $_POST ['privilege_desc'] );
$roles = KS_Filter::inputSanitize ( $_POST ['roles'] );

//if create new resource
if ($resource == 'N') {
	if (strlen ( $resource_new ) == 0) {
		header ( "Location: list.php?tabId=1&roleId=$curRole&err=resourcenew_empty" );
		exit ();
	} else {
		$resourceId = $resource_new;
	}
} else {
	if (strlen ( $resource_existing ) == 0) {
		header("Location: list.php?tabId=1&roleId=$curRole&err=resourceexisting_empty" );
		exit ();
	} else {
		$resourceId = $resource_existing;
	}
}

if (strlen ( $privilege_id ) == 0) {
	header("Location: list.php?tabId=1&roleId=$curRole&err=privid_empty" );
	exit ();
}

//now insert into ks_acl_resource table
$objAclResource = new KS_Acl_Resource ();
$objAclResource->setId ( $resourceId );
$objAclResource->setPrivilegeid ( $privilege_id );
$objAclResource->setDesc ( $privilege_desc );
$objAclResource->setParentid ( NULL );
$objAclResource->insert ();

if (is_array ( $roles ) && (count ( $roles ) > 0)) {
	for($i = 0; $i < count ( $roles ); $i ++) {
		$role = $roles [$i];
		//insert into ks_acl_access
		$objAclAccess = new KS_Acl_Access ();
		$objAclAccess->setRoleid ( $role );
		$objAclAccess->setResid ( $resourceId );
		$objAclAccess->setPrivilegeid ( $privilege_id );
		$objAclAccess->setAllow ( 1 );
		$objAclAccess->insert ();
	}
}

header("Location: roledisplay.php?tabId=1&roleId=$curRole&msg=acl_added" );