<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$roleId = KS_Filter::inputSanitize($_GET['roleId']);
$tabId = KS_Filter::inputSanitize($_GET['tabId']);

$objAclRole = new KS_Acl_Role ( );
$objAclRole->setId($roleId);

if(!$objAclRole->exists()) {
	header("Location: list.php?err=role_notfound");
	exit();
}
$objAclRole->select();

$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );

$showMessageBoxType = 'info';
switch ($msg) {

	case 'roleid_empty' :
		$msg_desc = 'Role ID is required.';
		$showMessageBoxType = 'error';
		$showMessageBox = 1;
		break;

	case 'updated' :
		$msg_desc = 'Role has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
		
	case 'acl_updated' :
		$msg_desc = 'Privilege has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
		
	case 'acl_added' :
		$msg_desc = 'Privilege has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';

?>
<div class="container"><?php
include_once '../navbar_top.php';

include_once 'breadcrumb.php';

$rolename = $objAclRole->getName();
?>
<div class="<?=$showMessageBoxType;?>" style="display: <?=($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?=$msg_desc;?></div>
<p>Property page for role <strong><?=$objAclRole->getName();?></strong>.</p>

<div class="tabbable tabs-left">
<ul class="nav nav-tabs">

	<li class="<?=($tabId==0)?'active':'';?>"><a href="#section0"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Properties&nbsp;</a></li>
	<li class="<?=($tabId==1)?'active':'';?>"><a href="#section1"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Access
	Privileges&nbsp;</a></li>
	<li class="<?=($tabId==2)?'active':'';?>"><a href="#section2"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Add
	Privilege&nbsp;</a></li>
	<li class="<?=($tabId==3)?'active':'';?>"><a href="#section3"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> List of
	User&nbsp;</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane <?=($tabId==0)?'active':'';?>" id="section0">

<form id="formRoleModify" name="formRoleModify" method="post"
	action="rolemodifyhandler.php">
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
			<th align="right" valign="top">Role ID :</th>
			<td valign="top"><?=$roleId;?>&nbsp;</td>
		</tr>
		<tr>
			<th width="30%" align="right" valign="top">Role Name :</th>
			<td valign="top"><?=$objAclRole->getName();?></td>
		</tr>
		<tr>
			<th align="right" valign="top">Description :</th>
			<td valign="top"><?=$objAclRole->getDesc();?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="role_id" type="hidden" id="role_id"
				value="<?=$roleId;?>"> <input type="button" id="btnModify"
				name="btnModify" value="Modify"
				onclick="location.href='rolemodify.php?roleId=<?=$roleId;?>'"
				class="btn btn-primary" /> <input type="button" id="btnDelete" name="btnDelete"
				value="Delete"
				onclick="location.href='roledisplay.php?roleId=<?=$roleId;?>&mode=delete&tabId=3'"
				class="btn btn-danger" /></td>
		</tr>
	</tbody>
</table>
</form>
</div>
<div class="tab-pane <?=($tabId==1)?'active':'';?>" id="section1"><?php include_once 'display.php';?>
</div>
<div class="tab-pane <?=($tabId==2)?'active':'';?>" id="section2"><?php include_once 'acladd.php';?>
</div>
<div class="tab-pane <?=($tabId==3)?'active':'';?>" id="section3"><?php include_once 'listuser.php';?>
</div>
</div>
</div>

</div>


<script>
$(document).ready(function(){

	try {
	 	$("#role_name").focus();
		$("#formRoleModify").validationEngine();

	} catch(error) {
		var msg = "Fatal Error: " + error.description;
		alert(msg);
	}
});

</script>

<?php
include_once '../footer.php';
?>

