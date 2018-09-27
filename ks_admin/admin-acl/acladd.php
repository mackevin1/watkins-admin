<?php

//get existing resources
$objAclResource = new KS_Acl_Resource ( );
$objAclResource->setSearchSortField ( 'res_id' );
$objAclResource->setSearchRecordsPerPage ( 1000000 );
$arrAclResource = $objAclResource->search ();

if (is_array ( $arrAclResource )) {
	$curAclResource = new KS_Acl_Resource ( );
	
	//we keep a list of used resource, since we want unique resources
	$arrUsedResource = array ();
	foreach ( $arrAclResource as $curAclResource ) {
		$resId = $curAclResource->getId ();
		if (! in_array ( $resId, $arrUsedResource )) {
			$strOptionResource .= "\n<option value=\"$resId\">$resId</option>";
			
			$arrUsedResource [] = $resId;
		}
	}
}

//next we get list of roles
$objAclRole = new KS_Acl_Role ( );
$objAclRole->setSearchSortField ( 'role_name' );
$objAclRole->setSearchRecordsPerPage ( 1000 );
$arrAclRole = $objAclRole->search ();

?>
<script>
function openAddPriv<?=$roleId;?>() {
	if($("#divAddPriv<?=$roleId;?>").is(':visible')) {
		$("#divAddPriv<?=$roleId;?>").hide();
		$("#listprivilege<?=$roleId;?>").show();
	} else {
		$("#divAddPriv<?=$roleId;?>").show();
		$("#listprivilege<?=$roleId;?>").hide();
	}
}

$(document).ready(function(){
	var doOpenAddPriv<?=$roleId;?> = <?=( int ) $_GET ['doOpenAddPriv'.$roleId];?>;
	
	if(doOpenAddPriv<?=$roleId;?> == 1) {
		$("#divAddPriv<?=$roleId;?>").show();
		$("#listprivilege<?=$roleId;?>").hide();
	} else {
		$("#divAddPriv<?=$roleId;?>").hide();
		$("#listprivilege<?=$roleId;?>").show();
	}
});

</script>
    					
<p>Use this form to add new Privilege.</p>
<form id="form1" name="form1" method="post" action="acladdhandler.php">
 <table class="table table-bordered table-hover table-striped">
	<tr>
		<th width="30%" align="right" valign="top">Resource ID:</th>
		<td valign="top">
		 <div class="radio"><label class="pull-left"><input name="resource" type="radio" id="resource1" value="E"
			checked="checked" /> Use existing: </label>&nbsp; <select class="form-control ks-form-control" name="resource_existing"
			id="resource_existing"><?=$strOptionResource;?>
          </select></div><b>or</b> 
	`<div class="radio"><label class="pull-left"><input type="radio" name="resource" id="resource2" value="N" />
			Create new: </label>&nbsp; <input class="form-control ks-form-control" name="resource_new" type="text" id="resource_new"
			size="20" maxlength="32" /></td>
	</tr>
	<tr>
		<th align="right" valign="top">Privilege ID:</th>
		<td valign="top"><input class="form-control ks-form-control" name="privilege_id" type="text"
			id="privilege_id" size="30" maxlength="64" /></td>
	</tr>
	<tr>
		<th align="right" valign="top">Description (optional):</th>
		<td valign="top"><textarea class="form-control ks-form-control" name="privilege_desc" cols="30" rows="2"
			id="privilege_desc"></textarea></td>
	</tr>
	<tr valign="top">
		<th align="right">Grant to Roles:</th>
		<td><?php foreach ( $arrAclRole as $curAclRole ) {
			$curRoleId = $curAclRole->getId ();
			$curRoleName = $curAclRole->getName ();
			?>
        <div class="checkbox"><label><input name="roles[]" type="checkbox" id="roles[]" value="<?=$curRoleId;?>" />  <?=$curRoleName;?></label></div>
        	<?php } ?></td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><input type="hidden" name="curRole" value="<?=$roleId;?>"> <input
			type="submit" name="button3" id="button3" value="Save" class="btn btn-primary"/></td>
	</tr>
</table>
</form>