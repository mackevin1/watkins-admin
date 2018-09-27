<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$roleId = KS_Filter::inputSanitize ( $_POST ['roleId'] );
$chkAccess = $_POST ['chkAccess'] ; //checked is_array below

//firstly, clear all this role's data
$sql = "DELETE FROM ks_acl_access WHERE acc_roleid=?";
$ks_db->query ( $sql, $roleId );

if (is_array ( $chkAccess )) {
	foreach ( $chkAccess as $curAccess ) {
		//curAccess is Resource Id

		if (is_array ( $curAccess )) {
			foreach ( $curAccess as $curPrivilege ) {
				//$curPrivilege is privileges
				$sql = "INSERT INTO ks_acl_access (acc_roleid, acc_resid, acc_privilegeid, acc_allow) ";
				$sql .= " VALUES (?,?,?,?)";

				$arrBindings = array ();
				$arrBindings [] = $roleId;
				$arrBindings [] = key ( $chkAccess );
				$arrBindings [] = key ( $curAccess );
				$arrBindings [] = 1;

				$ks_db->query ( $sql, $arrBindings );
				next ( $curAccess );
					
			}
		}
		next ( $chkAccess );
	}
}

header("Location: roledisplay.php?tabId=1&roleId=$roleId&msg=acl_updated" );