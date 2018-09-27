<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$roleId = KS_Filter::inputSanitize($_GET['roleId']);

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
	
	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';

?>
	<div class="container">
    <?php
	include_once '../navbar_top.php';
	?>
    
   <?php
	include_once 'breadcrumb.php';
	?>
        
<p>Use this form to modify this role.</p>

<form id="formRoleModify" name="formRoleModify" method="post" action="rolemodifyhandler.php">
  <table class="table table-bordered table-hover table-striped">
    <tbody>
    <tr>
      <th align="right" valign="top">Role ID :</th>
      <td valign="top"><?=$roleId;?>&nbsp;</td>
    </tr>
    <tr>
      <th width="30%" align="right" valign="top">Role Name :</th>
      <td valign="top"><input name="role_name" type="text" id="role_name"
			size="30" maxlength="64" value="<?=$objAclRole->getName();?>" class="form-control ks-form-control validate[required,length[1,30]] "/>
          <span class="font-error">*</span></td>
    </tr>
    <tr>
      <th align="right" valign="top">Description :</th>
      <td valign="top"><textarea class="form-control ks-form-control" name="role_desc" cols="30" rows="2"
			id="role_desc"><?=$objAclRole->getDesc();?></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="role_id" type="hidden" id="role_id" value="<?=$roleId;?>">
        <input name="btnSubmit" type="submit" value="Save" class="btn btn-primary"/>
or <a href="roledisplay.php?roleId=<?=$roleId;?>">Cancel</a></td>
    </tr>
    </tbody>
  </table></form>
  
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
