<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$roleId = KS_Filter::inputSanitize ( $_GET ['roleId'] );
$mode = KS_Filter::inputSanitize ( $_GET ['mode'] );
$maxRecords = 100000;

$objUser = new CUSTOM_User ();

if($roleId){
	$objUser->setSearchSqlWhere ( "usr_role='$roleId'" );
}

$objUser->setSearchRecordsPerPage ( $maxRecords );
$objUser->setSearchSortField ( 'usr_name' );

$arrUsers = $objUser->search ();

$totUsers = count ( $arrUsers );

if ($totUsers == 0) {
	$msg = 'users_notfound';
}

$showMessageBoxType = 'info';
switch ($msg) {
	
	case 'users_notfound' :
		$msg_desc = "No User ID found.";
		$showMessageBoxType = 'alert alert-info';
		$showMessageBox = 1;
		break;
}

?>
<?php
if ($totUsers <= 0) {
	?>
<div class="alert alert-info" align="center">
	No User found.
</div>
<?php if($mode == 'delete'){ ?>
<form action="acldelete.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="validateForm();return document.returnValue">
<p align="center"><input type="hidden" name="totaluser" value="<?=$counterP;?>" /><input type="hidden" name="roleId" value="<?=$roleId;?>" />
	<input type="submit" value="Continue Delete Role" name="btnNextDelete" class="btn btn-danger"/></p>
</form>
<?php }?>
<?php } ?>
<?php
if ($totUsers > 0) {
	?>
<p>List of user with role <?=$rolename;?>.</p>
<?php if($mode == 'delete'){ ?>
<div class="alert alert-info"">
<button type="button" class="close" data-dismiss="alert">&times;</button>
To delete role user with the role must be assign to another role.</div>

<form action="acldelete.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="validateForm();return document.returnValue">
<?php }?>
<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr align="center">
			<td class="span1">#</td>
			<td class="span2">User ID</td>
			<td class="span2">Name</td>
			<td class="span2">Email</td>
			<td class="span2">Role</td>
			<td class="span2">Status</td>
		</tr>
	</thead>
	<tbody>
<?php
	
	$counter = 1;
	foreach ( $arrUsers as $objUser ) {
		//$objList = new KS_Lists();
		$urlDisplay = "../admin-user/display.php?id=" . $objUser->getId ();
		$urlDelete = "../admin-user/delete.php?id=" . $objUser->getId ();
		
		$objAclRole = new KS_Acl_Role ();
		$objAclRole->setId ( $objUser->getRole () );
		if ($objAclRole->exists ()) {
			$objAclRole->select ();
			$strRole = $objAclRole->getName ();
			if($mode == 'delete'){
				 $strRole = "<select name=\"roleId".++$counterP."\"\>";
				 $strRole .= "<option value=\"\" >Please choose..</option>";
                     foreach ( $arrRoles as $curRoleOp ) {
                     	
                     	if ($curRoleOp->getId() == $objUser->getRole ()){
                     		
                     	}else{
                         $strRole .= "<option value=\"".$curRoleOp->getId()."\" > ". $curRoleOp->getName() ." </option>";
                     	}
                     }
                  $strRole .= "</select>";
                  $strRole .= "<input type=\"hidden\" name=\"userid".++$counterH."\" value=\"".$objUser->getId ()."\" />";
			}
		} else {
			$strRole = 'UNDEFINED';
		}
		
		?>
		<tr align="center">
			<td><?=$counter ++?>.</td>
			<td align="left"><a href="<?=$urlDisplay;?>" class="lead"><?=$objUser->getId ();?></a></td>
			<td align="left"><?=$objUser->getName ();?></td>
			<td align="left"><a href="mailto:<?=$objUser->getEmail ();?>"><?=$objUser->getEmail ();?></a></td>
			<td><?=$strRole;?></td>
			<td align="center" valign="top"><?=$objUser->getEnabled () ? '<span class="label label-info">Enabled</span>' : '<span class="label label-danger">Disabled</span>';?>&nbsp;</td>
		</tr>
<?php
	}
	?></tbody>
</table>
<?php if($mode == 'delete'){ ?>
<p align="center"><input type="hidden" name="totaluser" value="<?=$counterP;?>" /><input type="hidden" name="roleId" value="<?=$roleId;?>" />
	<input type="submit" value="Continue Delete Role" name="btnNextDelete" class="btn btn-danger"/></p>
<?php }?>
</form>



<?php
} //end if $totUser > 0
?>
<?php if($mode == 'delete'){ ?>
<script>
$(document).ready(function(){

});	

function validateForm(){

	try {

		$("[name^='roleId']").each(function() {	
				if($(this).val() == 0){	
						
						alert('Please make sure all user has been assign to another role.');	
						document.returnValue = false;
						$(this).focus();
						exit();
				}else{
					
					document.returnValue = true;
				}
		
		});
		
	
		
	}catch(e) {
		//alert("JS Fatal Error in validateForm(): " + e.message);
	}
}
</script>
<?php }?>