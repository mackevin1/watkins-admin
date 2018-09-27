<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$parentId = 1;
$mid = ( int ) $_GET ['mid'];

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

//list Roles from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	$arrRole [] = $rowRole['role_id'];
}

$objMenu = new KS_Menu ();
$objMenu->setId ( $mid );
if(!$objMenu->exists()) {
	header("Location: list.php?e=menu_notfound&mid=$mid");
	exit;
}
$objMenu->select();

//unserialize input layout
$optionlayout = unserialize($objMenu->getOption());
$menulayout = $optionlayout['menuo_layout'];

if ($menulayout == '1') { // 1 = horizontal
	$styleLYH = "checked='checked'";
	$mlayout = "Horizontal";
} else { // 2 = vertical
	$mlayout = "Vertical";
}

$objMenuitem = new KS_Menuitem ();
$objMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND (mi_parentid IS NULL OR mi_parentid='')" );
$objMenuitem->setSearchSortField ( "mi_order" );
$objMenuitem->setSearchSortOrder ( 'ASC' );
$objMenuitem->setSearchRecordsPerPage ( 1000 );
$arrMenuitem = $objMenuitem->search ();
$totMenuitem = count ( $arrMenuitem );

$btnReorder = ($totMenuitem > 1) ? "" : "style=\"display:none;\"";

$showMessageBoxType = 'error';
switch ($msg) {
	case 'menu_added' :
		$msg_desc = 'Menu has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'added' :
		$msg_desc = 'Menu Item has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'menu_updated' :
		$msg_desc = 'Menu has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'menuitem_updated' :
		$msg_desc = 'Menu Item has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'deleted' :
		$msg_desc = 'Menu Item has been deleted.';
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

<div class="container"><?php
include_once '../navbar_top.php';

include_once 'breadcrumb.php';
?>

<p>Property page for menu <strong><?php echo $objMenu->getName ();?></strong>.</p>

<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>

<div class="tabbable tabs-left">
<ul class="nav nav-tabs">

	<li class="<?php echo ($tabId==0)?'active':'';?>"><a href="#section0"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Properties</a></li>
	<li class="<?php echo ($tabId==1)?'active':'';?>"><a href="#section1"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Menu Item</a></li>
	<li class="<?php echo ($tabId==2)?'active':'';?>"><a href="#section2"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Add Menu Item</a></li>
	<li class="<?php echo ($tabId==3)?'active':'';?>"><a href="#section3"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Reorder</a></li>
	<li class="<?php echo ($tabId==4)?'active':'';?>"><a href="#section4"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Preview</a></li>
	<li class="<?php echo ($tabId==5)?'active':'';?>"><a href="#section5"
		data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Source Code</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">

<form action="modify_handler.php" method="post" name="formModify"
	id="formModify">
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
			<th align="right">Name :</th>
			<td><?php echo $objMenu->getName ();?></td>
		</tr>
		<tr>
			<th align="right">Layout :</th>
			<td><?php echo $mlayout;?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="button" value="Modify" onClick="location.href='modify.php?mid=<?php echo $mid;?>';" class="btn btn-primary">
			<input type="button" value="Delete" onClick="deleteMenu('<?php echo $mid;?>');" class="btn btn-danger"></td>
		</tr>
	</tbody>
</table>
</form>
</div>
<div class="tab-pane <?php echo ($tabId==1)?'active':'';?>" id="section1">

<p class="font-small">List of Menu Items.</p>

<div class="btn-group pull-right">
<button class="btn btn-primary"
	onClick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2';">Add
Menu Item</button>
<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span></button>
<ul class="dropdown-menu">
	<li><a
		onClick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2';">Add
	Menu Item</a></li>
	<li><a onClick="location.href='display.php?mid=<?php echo $mid;?>&tabId=3';"
	<?php echo $btnReorder;?>>Reorder</a></li>
</ul>
</div>

<?php if ($totMenuitem <= 0){
	$btnReorder = "style=\"display:none;\"";
	$btnGenerate = "style=\"display:none;\"";
	?>
	<br/><br/>
<div class="alert alert-info" align="center">No Menu Item found. Click
on 'Add Menu Item' button to add.</div>
	<?php
}
?>

	<?php
	if ($totMenuitem > 0) {

		?>
<br/><br/>
<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr align="center">
			<th width="2%">#.</th>
			<th colspan="5">Label</th>
			<th>URL</th>
			<th>Tooltip</th>
			<th>Roles</th>
			<th>Visible To Non-Login User</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$counter = 0;
	$curMenuitem = new KS_Menuitem ();
	foreach ( $arrMenuitem as $curMenuitem ) {

	$menuitem_id = $curMenuitem->getId ();

	$strRoles = '';
	$roles = preg_replace ( "/;$/", "", $curMenuitem->getRoles () );
	if ($roles) {
		$arrRoles = explode ( ";", $roles );
		foreach ( $arrRoles as $curRoles ) {
			$strRoles .= "<li>" . $curRoles . "<br>";
		}
	}

	$notlogin = $curMenuitem->getNotlogin ();
	$strImages = ($notlogin == 1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
	
	//unserialize input icon
	$optionicon = unserialize($curMenuitem->getOption());
	$menuicon = $optionicon['mio_icon'];
	$displayicon = "";
	if($menuicon){
		$displayicon = "<i class=\"".$menuicon."\"></i> ";
	}

	$totSubMenuitem = 0;
	$objSubMenuitem = new KS_Menuitem ();
	$objSubMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$menuitem_id' " );
	$objSubMenuitem->setSearchSortField ( "mi_order" );
	$objSubMenuitem->setSearchSortOrder ( 'ASC' );
	$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
	$arrSubMenuitem = $objSubMenuitem->search ();
	$totSubMenuitem = count ( $arrSubMenuitem );
	$menuurl = $curMenuitem->getUrl ();
	if($menuurl == 'http://'){
		$menuurl = '-';
	}
	
	?>
		<tr>
			<td align="center" width="2%"><?php echo ++ $counter;?>.</td>
			<td class="" colspan="5"><?php echo $displayicon;?><a
				href="item_display.php?menuitem_id=<?php echo $menuitem_id;?>"><?php echo $curMenuitem->getLabel ();?></a></td>
			<td><?php echo $menuurl;?></td>
			<td><?php echo $curMenuitem->getTooltip ();?></td>
			<td>
			<ul>
			<?php echo $strRoles;?>
			</ul>
			</td>
			<td align="center"><?php echo $strImages;?></td>
			<td align="center" nowrap><input type="button" value="Properties"
				onClick="location.href='item_display.php?menuitem_id=<?php echo $curMenuitem->getId ();?>';"
				class="btn btn-primary"> <input type="button"
				value="Add Sub Menu Item" class="btn btn-primary"
				onclick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2&submenu=1&menuitem_id=<?php echo $menuitem_id;?>'">
			<input type="button" value="Delete"
				onClick="deleleItem('<?php echo $mid;?>','<?php echo $menuitem_id;?>');"
				class="btn btn-danger"></td>
		</tr>
		<?php

		//submenu
		$counterItem = 0;
		if ($totSubMenuitem > 0) {
			$curSubMenuitem = new KS_Menuitem ();
			foreach ( $arrSubMenuitem as $curSubMenuitem ) {
			$submenuitem_id = $curSubMenuitem->getId ();

			$strSubRoles = '';
			$roles = preg_replace ( "/;$/", "", $curSubMenuitem->getRoles () );
			if ($roles) {
				$arrRoles = explode ( ";", $roles );
				foreach ( $arrRoles as $curRoles ) {
					$strSubRoles .= "<li>" . $curRoles . "<br>";
				}
			}

			$subnotlogin = $curSubMenuitem->getNotlogin ();
			$strSubImages = ($subnotlogin == 1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
			
			//unserialize input icon
			$optionicon = unserialize($curSubMenuitem->getOption());
			$menuicon = $optionicon['mio_icon'];
			$displayicon = "";
			if($menuicon){
				$displayicon = "<i class=\"".$menuicon."\"></i> ";
			}

			$totSubMenuitem1 = 0;
			$objSubMenuitem1 = new KS_Menuitem ();
			$objSubMenuitem1->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id' " );
			$objSubMenuitem1->setSearchSortField ( "mi_order" );
			$objSubMenuitem1->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem1->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem1 = $objSubMenuitem1->search ();
			$totSubMenuitem1 = count ( $arrSubMenuitem1 );
			$menusuburl = $curSubMenuitem->getUrl ();
			if($menusuburl == 'http://'){
				$menusuburl = '-';
			}
			?>

		<tr>
			<td align="center">&nbsp;</td>
			<td align="center" width="3%"><?php echo $counter;?>.<?php echo ++ $counterItem;?>.</td>
			<td colspan="4" class="lead"><?php echo $displayicon;?><a
				href="item_display.php?menuitem_id=<?php echo $submenuitem_id;?>"><?php echo $curSubMenuitem->getLabel ();?></a></td>
			<td><?php echo $menusuburl;?></td>
			<td><?php echo $curSubMenuitem->getTooltip ();?></td>
			<td>
			<ul>
			<?php echo $strSubRoles;?>
			</ul>
			</td>
			<td align="center"><?php echo $strSubImages;?></td>
			<td align="center" nowrap><input type="button" value="Properties"
				onClick="location.href='item_display.php?menuitem_id=<?php echo $curSubMenuitem->getId ();?>';"
				class="btn btn-primary"> <input type="button"
				value="Add Sub Menu Item" class="btn btn-primary"
				onclick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2&submenu=1&menuitem_id=<?php echo $submenuitem_id;?>'">
			<input type="button" value="Delete"
				onClick="deleleItem('<?php echo $mid;?>','<?php echo $submenuitem_id;?>');"
				class="btn btn-danger"></td>
		</tr>

		<?php

		//submenu
		$counterItem1 = 0;
		if ($totSubMenuitem1 > 0) {
			$curSubMenuitem1 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem1 as $curSubMenuitem1 ) {
			$submenuitem_id1 = $curSubMenuitem1->getId ();

			$strSubRoles = '';
			$roles = preg_replace ( "/;$/", "", $curSubMenuitem1->getRoles () );
			if ($roles) {
				$arrRoles = explode ( ";", $roles );
				foreach ( $arrRoles as $curRoles ) {
					$strSubRoles .= "<li>" . $curRoles . "<br>";
				}
			}

			$subnotlogin = $curSubMenuitem1->getNotlogin ();
			$strSubImages = ($subnotlogin == 1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
			
			//unserialize input icon
			$optionicon = unserialize($curSubMenuitem1->getOption());
			$menuicon = $optionicon['mio_icon'];
			$displayicon = "";
			if($menuicon){
				$displayicon = "<i class=\"".$menuicon."\"></i> ";
			}

			$totSubMenuitem2 = 0;
			$objSubMenuitem2 = new KS_Menuitem ();
			$objSubMenuitem2->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id1' " );
			$objSubMenuitem2->setSearchSortField ( "mi_order" );
			$objSubMenuitem2->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem2->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem2 = $objSubMenuitem2->search ();
			$totSubMenuitem2 = count ( $arrSubMenuitem2 );
			$menusuburl1 = $curSubMenuitem1->getUrl ();
			if($menusuburl1 == 'http://'){
				$menusuburl1 = '-';
			}
			?>

		<tr>
			<td align="center">&nbsp;</td>
			<td align="center" width="3%">&nbsp;</td>
			<td width="3%"><?php echo $counter;?>.<?php echo $counterItem;?>.<?php echo ++ $counterItem1;?>.</td>
			<td colspan="3" class="lead"><?php echo $displayicon;?><a
				href="item_display.php?menuitem_id=<?php echo $submenuitem_id1;?>"> <?php echo $curSubMenuitem1->getLabel ();?>
			</a></td>
			<td><?php echo $menusuburl1;?></td>
			<td><?php echo $curSubMenuitem1->getTooltip ();?></td>
			<td>
			<ul>
			<?php echo $strSubRoles;?>
			</ul>
			</td>
			<td align="center"><?php echo $strSubImages;?></td>
			<td align="center"><input type="button" value="Properties"
				onClick="location.href='item_display.php?menuitem_id=<?php echo $curSubMenuitem1->getId ();?>';"
				class="btn btn-primary"> <input type="button"
				value="Add Sub Menu Item" class="btn btn-primary"
				onclick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2&submenu=1&menuitem_id=<?php echo $submenuitem_id1;?>'">
			<input type="button" value="Delete"
				onClick="deleleItem('<?php echo $mid;?>','<?php echo $submenuitem_id1;?>');"
				class="btn btn-danger"></td>
		</tr>

		<?php

		//submenu
		$counterItem2 = 0;
		if ($totSubMenuitem2 > 0) {
			$curSubMenuitem2 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem2 as $curSubMenuitem2 ) {
			$submenuitem_id2 = $curSubMenuitem2->getId ();

			$strSubRoles = '';
			$roles = preg_replace ( "/;$/", "", $curSubMenuitem2->getRoles () );
			if ($roles) {
				$arrRoles = explode ( ";", $roles );
				foreach ( $arrRoles as $curRoles ) {
					$strSubRoles .= "<li>" . $curRoles . "<br>";
				}
			}

			$subnotlogin = $curSubMenuitem2->getNotlogin ();
			$strSubImages = ($subnotlogin == 1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
			
			//unserialize input icon
			$optionicon = unserialize($curSubMenuitem2->getOption());
			$menuicon = $optionicon['mio_icon'];
			$displayicon = "";
			if($menuicon){
				$displayicon = "<i class=\"".$menuicon."\"></i> ";
			}

			$totSubMenuitem3 = 0;
			$objSubMenuitem3 = new KS_Menuitem ();
			$objSubMenuitem3->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id2' " );
			$objSubMenuitem3->setSearchSortField ( "mi_order" );
			$objSubMenuitem3->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem3->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem3 = $objSubMenuitem3->search ();
			$totSubMenuitem3 = count ( $arrSubMenuitem3 );
			$menusubur2 = $curSubMenuitem2->getUrl ();
			if($menusubur2 == 'http://'){
				$menusubur2 = '-';
			}
			?>

		<tr>
			<td align="center">&nbsp;</td>
			<td align="center" width="3%">&nbsp;</td>
			<td width="3%">&nbsp;</td>
			<td width="3%"><?php echo $counter;?>.<?php echo $counterItem;?>.<?php echo $counterItem1;?>.<?php echo ++ $counterItem2;?>.</td>
			<td colspan="2" class="lead"><?php echo $displayicon;?><a
				href="item_display.php?menuitem_id=<?php echo $submenuitem_id2;?>"> <?php echo $curSubMenuitem2->getLabel ();?>
			</a></td>
			<td><?php echo $menusubur2;?></td>
			<td><?php echo $curSubMenuitem2->getTooltip ();?></td>
			<td>
			<ul>
			<?php echo $strSubRoles;?>
			</ul>
			</td>
			<td align="center"><?php echo $strSubImages;?></td>
			<td align="center"><input type="button" value="Properties"
				onClick="location.href='item_display.php?menuitem_id=<?php echo $curSubMenuitem2->getId ();?>';"
				class="btn btn-primary"> <input type="button"
				value="Add Sub Menu Item" class="btn btn-primary"
				onclick="location.href='display.php?menu_id=<?php echo $mid;?>&mid=<?php echo $mid;?>&tabId=2&submenu=1&menuitem_id=<?php echo $submenuitem_id2;?>'">
			<input type="button" value="Delete"
				onClick="deleleItem('<?php echo $mid;?>','<?php echo $submenuitem_id2;?>');"
				class="btn btn-danger"></td>
		</tr>

		<?php

		//submenu
		$counterItem3 = 0;
		if ($totSubMenuitem3 > 0) {
			$curSubMenuitem3 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem3 as $curSubMenuitem3 ) {
			$submenuitem_id3 = $curSubMenuitem3->getId ();

			$strSubRoles = '';
			$roles = preg_replace ( "/;$/", "", $curSubMenuitem3->getRoles () );
			if ($roles) {
				$arrRoles = explode ( ";", $roles );
				foreach ( $arrRoles as $curRoles ) {
					$strSubRoles .= "<li>" . $curRoles . "<br>";
				}
			}

			$subnotlogin = $curSubMenuitem3->getNotlogin ();
			$strSubImages = ($subnotlogin == 1) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
			
			//unserialize input icon
			$optionicon = unserialize($curSubMenuitem2->getOption());
			$menuicon = $optionicon['mio_icon'];
			$displayicon = "";
			if($menuicon){
				$displayicon = "<i class=\"".$menuicon."\"></i> ";
			}
			
		    $menusubur3 = $curSubMenuitem3->getUrl ();
			if($menusubur3 == 'http://'){
				$menusubur3 = '-';
			}
			?>

		<tr>
			<td align="center">&nbsp;</td>
			<td align="center" width="3%">&nbsp;</td>
			<td width="3%">&nbsp;</td>
			<td width="3%">&nbsp;</td>
			<td width="3%"><?php echo $counter;?>.<?php echo $counterItem;?>.<?php echo $counterItem1;?>.<?php echo $counterItem2;?>.<?php echo ++ $counterItem3;?>.</td>
			<td width="15%" class="lead"><?php echo $displayicon;?><a
				href="item_display.php?menuitem_id=<?php echo $submenuitem_id3;?>"> <?php echo $curSubMenuitem3->getLabel ();?>
			</a></td>
			<td><?php echo $menusubur3;?></td>
			<td><?php echo $curSubMenuitem3->getTooltip ();?></td>
			<td>
			<ul>
			<?php echo $strSubRoles;?>
			</ul>
			</td>
			<td align="center"><?php echo $strSubImages;?></td>
			<td align="center"><input type="button" value="Properties"
				onClick="location.href='item_display.php?menuitem_id=<?php echo $curSubMenuitem3->getId ();?>';"
				class="btn btn-primary"> <input type="button" value="Delete"
				onClick="deleleItem('<?php echo $mid;?>','<?php echo $submenuitem_id3;?>');"
				class="btn btn-danger"></td>
		</tr>

		<?php
			}
		}

			}
		}


			}
		}


			}
		}

	}
		?>
	</tbody>
</table>
		<?php
	} ?> <br/>
<br/>


</div>
<div class="tab-pane <?php echo ($tabId==2)?'active':'';?>" id="section2"><?php 
include_once 'item_add.php';?></div>
<div class="tab-pane <?php echo ($tabId==3)?'active':'';?>" id="section3"><?php include_once 'item_order.php';?>
</div>
<div class="tab-pane <?php echo ($tabId==4)?'active':'';?>" id="section4"><?php
if ($totMenuitem > 0) {
	include_once '../../ks_builtin/menu.php';
} else {
	?>
<div class="notice">
<div class="message_box_content">No Menu Item found. Please add Menu
Item.</div>
<div class="clearboth"></div>
</div>
	<?php
}
?>
<div id="divCode" title="Menu Generated Code">
<p align="center"><textarea id="textareaCode" cols="100" rows="8"
	onfocus="this.select();" wrap="off" style="display: none;"></textarea>
<input type="button" name="btnCopy2Clipboard" id="btnCopy2Clipboard"
	value="Copy to Clipboard" onClick="copytoClipBoard();" class /></p>
</div>
<br/>
</div>
<div class="tab-pane <?php echo ($tabId==5)?'active':'';?>" id="section5">
<p>Use this source code to generate menu function.</p>
<?php 

$menu_code = '$mid='.$mid.';';
$menu_code .= "\n";
$menu_code .= "include_once KSCONFIG_ABSPATH . 'ks_builtin/menu.php';";

?>
<textarea class="form-control ks-form-control"
 name="dsh_code" id="dsh_code" rows="3" cols="80"><?php echo $menu_code;?></textarea>
</div>
</div>

</div>
<script>
$(document).ready(function(){

	var $tab1 = $("#divTab").tabs();
	var tabId = <?php echo $tabId ? $tabId : 1;?>;
	$tab1.tabs('select', tabId);

	$("#formModify").validationEngine();

	$("#tblMenuItems tbody").sortable({
		helper: fixHelper
	}).disableSelection();

	$("div[id^='divCode']").hide();
	$("#btnCopy2Clipboard").hide();
	if (jQuery.browser.msie) {
		$("#btnCopy2Clipboard").show();
	}

});

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

function deleleItem(menuid,itemid){
	if (confirm('Are you sure to delete this menu item?')){
		$.post("item_delete_handler.php", {
			menu_id : menuid,
			item_id : itemid }, function(data) {
				window.location.href = 'display.php?msg=deleted&tabId=1&mid=' + menuid;
		});
	}
}

function deleteMenu(id){
	if (confirm('Are you sure to delete this Menu?')){
		$.post("delete_handler.php", {
			mid : id }, function(data) {
				window.location.href = 'list.php?msg=deleted';
		});
	}
}
</script>
</div>
<?php
include_once '../footer.php';
?>