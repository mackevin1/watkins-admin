<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$menuitem_id = (int)KS_Filter::inputSanitize ( $_GET ['menuitem_id'] );

//list Roles from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	$arrRole [] = $rowRole['role_id'];
}

$objMenuitem = new KS_Menuitem ();
$objMenuitem->setId ( $menuitem_id );
if(!$objMenuitem->exists()) {
	header("Location: list.php?e=menuitem_notfound&id=$menuitem_id");
}
$objMenuitem->select ();

//unserialize input icon
$optionicon = unserialize($objMenuitem->getOption());
$menuicon = $optionicon['mio_icon'];

$submenu = $objMenuitem->getParentid();

if ($submenu != 0) {
	$objMenuItemParent = new KS_Menuitem ();
	$objMenuItemParent->setId ( $submenu );
	$objMenuItemParent->select ();
}

$objMenu = new KS_Menu();
$objMenu->setId($objMenuitem->getMenuid());
if(!$objMenu->exists()) {
	header("Location: list.php?e=menu_notfound&id={$objMenuitem->getMenuid()}");
	exit;
}
$objMenu->select();

include_once '../header_bootstrap.php';

?>

<script>
$(document).ready(function(){
 	$("#label").focus();
 	showUrl("<?php echo $objMenuitem->getUrltype ();?>");
 	hidetr("<?php echo $objMenuitem->getUrltype ();?>");
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
			        $("#urlinternal").focus();
			        alert('File does not exist.');
			    },
			    success: function()
			    {
			    	$("#formAdd").submit();
			    }
			});
		}	
	}else{
		$("#formAdd").submit();
	}
}
</script>

	<div class="container">
	<?php
	include_once '../navbar_top.php';

	include_once 'breadcrumb.php';
	?>

<form action="item_modify_handler.php" method="post" name="formAdd"
	id="formAdd" enctype='multipart/form-data'>
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<?php
	if ($submenu) {
		?>
		<tr>
			<th align="right" valign="top">Parent Menu Item :</th>
			<td class="lead"><?php echo $objMenuItemParent->getLabel ();?></td>
		</tr>
		<?php
	}
	?>
		<tr>
			<th width="30%" align="right" valign="top">Label :</th>
			<td>
			<input class="form-control ks-form-control validate[required,length[0,255]]" type="text" name="label" id="label" size="50"
				value="<?php echo $objMenuitem->getLabel ();?>"></td>
		</tr>
		<tr>
			<th align="right" valign="top">URL :</th>
			<td><div class="radio"><label><input type="radio" name="url" value="internal"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "internal") ? 'checked' : "";?>> Internal
			URL</label></div>
			<div class="radio"><label><input type="radio" name="url" value="external"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "external") ? 'checked' : "";?>> External
			URL</label></div>
			<div class="radio"><label><input type="radio" name="url" value="blank"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "blank") ? 'checked' : "";?>> Blank
			(Parent Menu Item)</label></div>
			<?php if ($submenu) { ?>
			<div class="radio"><label><input type="radio" name="url" value="separator"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "separator") ? 'checked' : "";?>> Separator</label></div>
			<?php  } ?>
			<div id="divinternalurl"><label class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo KSCONFIG_URL;?></label>
			<input class="form-control ks-form-control" type="text"
				name="urlinternal" id="urlinternal" size="50"
				value="<?php echo $objMenuitem->getUrl ();?>"></div>
			<div id="divexternalurl"><input class="form-control ks-form-control" type="text" name="urlexternal"
				id="urlexternal" size="50"
				value="<?php echo ($objMenuitem->getUrl ()) ? $objMenuitem->getUrl () : "http://";?>"></div>
			</td>
		</tr>
		<tr id="tricon">
			<th width="30%" align="right" valign="top">Icon :</th>
			<td><input class="form-control ks-form-control" type="text" name="mio_icon" id="mio_icon" size="30" value="<?php echo $menuicon;?>">
			  <a href="http://getbootstrap.com/components/" target="_blank">Example: </a><small>glyphicon glyphicon-home</small></td>
		</tr>
		<tr id="trtooltip">
			<th align="right" valign="top">Tooltip :</th>
			<td>
			<input class="form-control ks-form-control" type="text" name="tooltip" id="tooltip" size="50"
				value="<?php echo $objMenuitem->getTooltip ();?>">
			</td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Roles :</th>
			<td><?php
			
			$strRoles = $objMenuitem->getRoles ();
			if ($strRoles) {
				$arrRolesSelected = explode ( ";", $strRoles );
			}
			
			if (count ( $arrRole ) > 0) {
				foreach ( $arrRole as $curRole ) {
					if (count ( $arrRolesSelected ) > 0) {
						$strSelected = (in_array ( $curRole, $arrRolesSelected )) ? "checked" : "";
					}
					echo "<div class=\"checkbox\"><label><input type=\"checkbox\" id=\"role_" . $curRole . "\" name=\"role_" 
					. $curRole . "\" value=\"" . $curRole . "\" $strSelected> " . $curRole . "</label></div>";
				}
			}
			?></td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Non-login Access :</th>
			<td><label><input type="checkbox" name="notlogin" value="1"
				<?php echo ($objMenuitem->getNotlogin () == 1) ? "checked" : "";?>> Yes, this menu item will be publicly available without
			the need to login.</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="menu_id" type="hidden" id="menu_id"
				value="<?php echo $objMenuitem->getMenuid ();?>" /><input name="menuitem_id"
				type="hidden" id="menuitem_id" value="<?php echo $menuitem_id;?>"/><input
				name="btnSubmit" type="submit" class="btn btn-primary" id="btnSubmit"
				value="Save" /> or <a href="item_display.php?menuitem_id=<?php echo $menuitem_id;?>">Cancel</a></td>
		</tr>
	</tbody>
</table>
</form>
</div>
<?php
include_once '../footer.php';
?>
