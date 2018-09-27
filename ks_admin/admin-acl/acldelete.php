<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$roleIdDeleted = KS_Filter::inputSanitize ( $_POST ['roleId'] );
$totaluser = (int)KS_Filter::inputSanitize ( $_POST ['totaluser'] );

//change user role
if($totaluser != 0){
	//assign all user to other role
	for($i = 1; $i <= $totaluser; $i ++) {
		$roleId = KS_Filter::inputSanitize ( $_POST['roleId' . $i]);
		$userid = KS_Filter::inputSanitize ( $_POST['userid' . $i]);

		if($roleId != ''){
			$objUser = new CUSTOM_User ();
			$objUser->setId ( $userid );
			if (! $objUser->exists ()) {
				header ( "Location: list.php?msg=userid_notfound&usr_id=$id" );
				exit ();
			}
			$objUser->setRole ( $roleId );
			$objUser->update ();
		}
	}
}

//delete in mi_roles(ks_menuitem)
$objMenuitem = new KS_Menuitem ();
$objMenuitem->setSearchRecordsPerPage ( 1000 );
$arrMenuitem = $objMenuitem->search ();
if((count($arrMenuitem)) > 0){
	
	foreach ( $arrMenuitem as $curMenuitem ) {
	$newroles = "";
	$menuroles = preg_replace ( "/;$/", "", $curMenuitem->getRoles () );
		if ($menuroles) {
			$arrRoles = explode ( ";", $menuroles );
			if (in_array($roleIdDeleted, $arrRoles)) {
					$menuid = $curMenuitem->getId();
				if (($key = array_search($roleIdDeleted, $arrRoles)) !== false) {
    					unset($arrRoles[$key]);
    					$newarrRoles = $arrRoles;
				}
				//echo '<PRE>';
				//print_r($newarrRoles);
				foreach ( $newarrRoles as $curnewarrRoles ) {
					$newroles .= $curnewarrRoles . ";";
				}
				//echo $newroles;
				//echo "<br/>";
				$objMenuitem = new KS_Menuitem ();
				$objMenuitem->setId ( $menuid );
				$objMenuitem->setRoles ( $newroles );
				$objMenuitem->update ();
			}
		}
	}
}

//delete in ks_acl_access
$objAclAccess = new KS_Acl_Access();
$objAclAccess->setRoleid($roleIdDeleted);
if ($objAclAccess->exists ()) {
	$objAclAccess->delete();
}

$objAclRole = new KS_Acl_Role();
$objAclRole->setId($roleIdDeleted);
if (! $objAclRole->exists ()) {
	header ( "Location: list.php?msg=deletefailed_roleexist&roleId=$roleIdDeleted" );
	exit ();
}
$objAclRole->delete ();

header ( "Location: list.php?msg=deleted" );
