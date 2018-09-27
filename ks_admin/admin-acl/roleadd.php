<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
$role_id = KS_Filter::inputSanitize ( $_GET ['role_id'] );
$role_name = KS_Filter::inputSanitize ( $_GET ['role_name'] );

$showMessageBoxType = 'info';
switch ($msg) {
	
	case 'role_exists' :
		$msg_desc = "Role with ID '$role_id' exists. Please use different ID.";
		$showMessageBoxType = 'error';
		$showMessageBox = 1;
		break;
	
	case 'rolename_empty' :
		$msg_desc = 'Role Name is required.';
		$showMessageBoxType = 'error';
		$showMessageBox = 1;
		break;
	
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
<?php
if(!$parentId){
?>
	<div class="container">
	<?php
	include_once '../navbar_top.php';
	?>
    
    	<ul class="breadcrumb">
			<li><a href="list.php"><i class="glyphicon glyphicon-wrench"></i> <?=$ks_translate->_('Access Control List '); ?></a>
			</li>
			<li class="active">Add Role</li>
		</ul>

     <?php
	}
	?>
    
           <div class="media">
              <div class="media-body">Use this form to add Role.
               </div>
            </div>
    
<div class="<?=$showMessageBoxType;?>" style="display: <?=($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?=$msg_desc;?></div>
                                    
       
     <form name="formAddrole" id="formAddrole" method="post" action="roleaddhandler.php">
  <table class="table table-bordered table-hover table-striped">
    <tbody>
      <tr>
        <th width="31%" align="right">Role ID :</th>
        <td width="69%"><input name="role_id" type="text" class="form-control ks-form-control validate[required,length[1,30]] " id="role_id" value="<?=$role_id; ?>" size="10" maxlength="30"
        onChange="this.value=this.value.toUpperCase();" onkeydown="this.value=this.value.toUpperCase();"/>
          <span class="font-small"> e.g: USER, SUPER_USER, ADMIN. No spaces or symbols.</span> </td>
      </tr>
      <tr>
        <th width="31%" align="right">Role name :</th>
        <td width="69%"><input name="role_name" type="text"  class="form-control ks-form-control validate[required,length[1,30]] " 
        id="role_name" value="<?=$role_name; ?>" size="30" maxlength="30"/>
          <span class="font-small"> e.g: Standard User, Super User, System Administrator. </span> </td>
      </tr>
    <tr>
      <th align="right" valign="top">Description :</th>
      <td valign="top"><textarea class="form-control ks-form-control" name="role_desc" cols="30" rows="2"
			id="role_desc"></textarea></td>
    </tr>
      <tr>
        <td align="right" >&nbsp;</td>
        <td><input name="btnSubmit" type="submit" value="Save" class="btn btn-primary"/>
          or <a href="list.php">Cancel</a></td>
      </tr>
    </tbody>
  </table>
</form>
    
    <?php        
if(!$parentId){
?>
  </div>
<?php } ?>

<script>
$(document).ready(function(){

	try {
	 	$("#role_id").focus();
		$("#formAddrole").validationEngine();

	} catch(error) {
		var msg = "Fatal Error: " + error.description;
		alert(msg);
	}
});

</script>


