<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0;
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$menuitem_id = 0;
if (isset ($_GET ['menuitem_id'])) {
	$menuitem_id = ( int ) $_GET ['menuitem_id'];
}

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

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBoxType = 'error';
switch ($msg) {

	case 'menuitem_updated' :
		$msg_desc = 'Menu Item has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	default :
		$msg_desc = '';
		$showMessageBox = 0;
		break;
}

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

function deleleItem(menuid,itemid){
	if (confirm('Are you sure to delete this menu item?')){
		$.post("item_delete_handler.php", {
			menu_id : menuid,
			item_id : itemid }, function(data) {
				window.location.href = 'display.php?msg=deleted&tabId=1&mid=' + menuid;
		});
	}
}
</script>

	<div class="container">
	<?php
	include_once '../navbar_top.php';

	include_once 'breadcrumb.php';
	?>
	
   <div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>

  <p>Property page for menu item <strong><?php echo $objMenuitem->getLabel ();?></strong>.</p>
          
          <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                        
                            <li class="<?php echo ($tabId==0)?'active':'';?>"><a href="#section0"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Basic</a></li>
                        </ul>
                        <div class="tab-content">
<div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
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
			<td><?php echo $objMenuitem->getLabel ();?></td>
		</tr>
		<tr>
			<th align="right" valign="top">URL :</th>
			<td><label><input type="radio" name="url" value="internal"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "internal") ? 'checked' : "";?> disabled="disabled"> Internal
			URL</label> 
			<label><input type="radio" name="url" value="external"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "external") ? 'checked' : "";?> disabled="disabled"> External
			URL</label> 
			<label><input type="radio" name="url" value="blank"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "blank") ? 'checked' : "";?> disabled="disabled"> Blank
			(Parent Menu Item)</label>
			<?php if ($submenu) { ?>
			<label><input type="radio" name="url" value="separator"
				onclick="showUrl(this.value);hidetr(this.value);"
				<?php echo ($objMenuitem->getUrltype () == "separator") ? 'checked' : "";?> disabled="disabled"> Separator</label>
			<?php  } ?>
			<div id="divinternalurl"><?php echo KSCONFIG_URL;?><?php echo $objMenuitem->getUrl ();?></div>
			<div id="divexternalurl"><?php echo ($objMenuitem->getUrl ()) ? $objMenuitem->getUrl () : "http://";?></div>
			</td>
		</tr>
		<tr id="tricon">
			<th width="30%" align="right" valign="top">Icon :</th>
			<td><?php echo $menuicon;?> </td>
		</tr>
		<tr id="trtooltip">
			<th align="right" valign="top">Tooltip :</th>
			<td><!--English<br>--><?php echo $objMenuitem->getTooltip ();?></td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Roles :
			<i class="glyphicon glyphicon-exclamation-sign" title="This menu item will only visible to selected roles."></i></th>
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
					echo "<label><input disabled=\"disabled\" type=\"checkbox\" id=\"role_" . $curRole . "\" name=\"role_" . $curRole 
						. "\" value=\"" . $curRole . "\" $strSelected> " . $curRole . "</label><br/>";
				}
			}
			?></td>
		</tr>
		<tr>
			<th align="right" valign="top">Available to Non-login Access :</th>
			<td><label><input disabled="disabled" type="checkbox" name="notlogin" value="1"
				<?php echo ($objMenuitem->getNotlogin () == 1) ? "checked" : "";?>> Yes, this menu item will be publicly available without
			the need to login.</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="button" name="Button2" value="Modify"
				onclick="location.href='item_modify.php?menuitem_id=<?php echo $menuitem_id;?>'"
				class="btn btn-primary"> <input type="button"
				onclick="deleleItem('<?php echo $objMenuitem->getMenuid ();?>','<?php echo $menuitem_id;?>');"
				value="Delete" class="btn btn-danger"></td>
		</tr>
	</tbody>
</table>
</form>
</div>
		</div>
        </div>

</div>
<?php
include_once '../footer.php';
?>
