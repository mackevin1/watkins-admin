<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = 0;
if (isset ($_GET ['menu_id'])) {
	$mid = ( int ) $_GET ['menu_id'];
}

if($mid == 0){
	$mid = (int) $_GET ['mid'];
}

$submenu = 0;
if (isset ($_GET ['submenu'])) {
	$submenu = ( int ) $_GET ['submenu'];
}

//list Roles from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	$arrRole [] = $rowRole['role_id'];
}

$objMenu = new KS_Menu();
$objMenu->setId($mid);
if(!$objMenu->exists()) {
	//header("Location: list.php?e=menu_notfound&id=$menu_id");
	exit;
}
$objMenu->select();

if ($submenu != 0) {
	$menuitem_id = ( int ) $_GET ['menuitem_id'];
	
	$objMenuItem = new KS_Menuitem ();
	$objMenuItem->setId ( $menuitem_id );
	$objMenuItem->select ();
}else{
	$menuitem_id = 0;
}

//build Role checkboxes from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	$optionRole .= "<div class=\"checkbox\"><label><input type=\"checkbox\" id=\"role_" . $rowRole['role_id'] . "\" name=\"role_" . $rowRole['role_id']
					 . "\" value=\"" . $rowRole['role_id'] . "\" checked> &nbsp;" . $rowRole['role_id'] . " - " . $rowRole['role_name'] . "</label></div>";
}
include_once '../header_bootstrap.php';

?>
<script>
$(document).ready(function(){
 	$("#label").focus();
 	showUrl("internal");
	$("#formAdd").validationEngine();
});	

function showUrl(type){
	if (type == "internal") {
 		$("#divinternalurl").show();
 		$("#divexternalurl").hide();
	}else if (type == "external"){
 		$("#divinternalurl").hide();
 		$("#divexternalurl").show();
	}else{
 		$("#divinternalurl").hide();
 		$("#divexternalurl").hide();
	}
}

function hidetr(type){
	if (type == "separator") {
 		$("#trtooltip").hide();
 		$("#tricon").hide();
	}else{
 		$("#trtooltip").show();
 		$("#tricon").show();
	}
}


function validate(){

	var type = $("input:radio:checked").val();
	if(type == "internal"){
		var urlinternal = $("#urlinternal").val();
		if(urlinternal.length < 1){
			alert('Please insert URL.');
		}else{
			$.ajax({
			    url:'<?php echo KSCONFIG_URL;?>' + urlinternal,
			    type:'HEAD',
			    error: function()
			    {
			        //file not exists
			        $("#urlinternal").focus();
			        alert('File "<?php echo KSCONFIG_URL;?>' + urlinternal + '" does not exist.');
			    },
			    success: function()
			    {
			        //file exists
			    	$("#formAdd").submit();
			    }
			});
		}	
	}else{
		$("#formAdd").submit();
	}
}
</script>
<p>Create new Menu Item.</p>

<form action="item_add_handler.php" method="post" name="formAdd"
	id="formAdd" enctype='multipart/form-data'>
<table class="table table-bordered table-hover table-striped">
	<tbody>
	<?php
	if ($submenu) {
		?>
		<tr>
			<th align="right" valign="top">Parent Menu Item :</th>
			<td class="lead"><?php echo $objMenuItem->getLabel ();?></td>
		</tr>
		<?php
	}
	?>
		<tr>
			<th width="30%" align="right" valign="top">Label :</th>
			<td><input class="form-control ks-form-control validate[required,length[0,255]]" type="text" 
			name="label" id="label" size="50"/></td>
		</tr>
		<tr>
			<th align="right" valign="top">URL :</th>
			<td>
				<div class="radio"><label>
				<input type="radio" name="url" value="internal" checked
				onclick="showUrl(this.value);hidetr(this.value);"> Internal URL </label></div>
				<div class="radio"><label><input type="radio"
				name="url" value="external" onclick="showUrl(this.value);hidetr(this.value);"> External URL </label></div>
				<div class="radio"><label><input type="radio" name="url" value="blank"
				onclick="showUrl(this.value);hidetr(this.value);"> Blank (Parent Menu Item)</label></div>
			<?php if ($submenu) { ?>
			<div class="radio"><label><input type="radio" name="url" value="separator" onclick="showUrl(this.value);hidetr(this.value);"> Separator</label></div>
			<?php  } ?>
			<div id="divinternalurl"><?php echo KSCONFIG_URL;?> <input class="form-control ks-form-control" type="text"
				name="urlinternal" id="urlinternal" size="50"></div>
			<div id="divexternalurl"><input class="form-control ks-form-control" type="text" name="urlexternal"
				id="urlexternal" size="50" value="http://"></div>
			</td>
		</tr>
		<tr id="tricon">
			<th width="30%" align="right" valign="top">Icon :</th>
			<td><input class="form-control ks-form-control" type="text" name="mio_icon" id="mio_icon" size="30">
			 <a href="http://getbootstrap.com/components/" target="_blank">Example:</a>
			<label class="label label-info">glyphicon glyphicon-home</label> or <label class="label label-info">fa fa-home</label></td>
		</tr>
		<tr id="trtooltip">
			<th align="right" valign="top">Tooltip :</th>
			<td>
			<input class="form-control ks-form-control" type="text" name="tooltip" id="tooltip" size="50">
			</td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Roles :
			<i class="glyphicon glyphicon-exclamation-sign" title="This menu item will only visible to selected roles."></i></th>
			<td><?php echo $optionRole; ?></td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Non-login Access :</th>
			<td><label><input type="checkbox" name="notlogin" value="1"> Yes, this menu item will be publicly available without
			the need to login.</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="menu_id" type="hidden" id="menu_id"
				value="<?php echo $mid;?>" /><input name="submenu" type="hidden"
				id="submenu" value="<?php echo $submenu;?>" /><input name="menuitem_id"
				type="hidden" id="menuitem_id" value="<?php echo $menuitem_id;?>" /><input
				name="btnSubmit" type="button" class="btn btn-primary" id="btnSubmit"
				value="Save" onclick="validate();" /> or <a
				href="display.php?id=<?php echo $mid;?>">Cancel</a></td>
		</tr>
	</tbody>
</table>
</form>
