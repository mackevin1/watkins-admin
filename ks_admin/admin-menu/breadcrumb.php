<?php
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );

$arrMenuBc= array ();
$sSortField = 'menu_name';
$sSortOrder = 'ASC';
$sStart = 0;
$arrMenuBc= KS_Menu::listMenu ( $sSortField, $sSortOrder, $sStart, 100 );
$totalRecords = $arrMenuBc['total'];

$strTables = '';
foreach ( $arrMenuBc['data'] as $curMenuBc ) {
	$objMenuB = new KS_Menu ();
	$objMenuB->setId($curMenuBc['id']);
	$objMenuB->select ();

	if($objMenu->getId() == $objMenuB->getId ()){
		$highlightli = "style=\"background-color:#CCC\"";
	}else{
		$highlightli = "style=\"\"";
	}

	$redirecturl = "display.php?mid=";
	$strTables .= '<li><a '.$highlightli.' href="' . $redirecturl . $objMenuB->getId () . '">' . $objMenuB->getName () . '</a></li>';
}

// get menu item
$arrItemBc = array ();
$sSortOrder = 'ASC';
$sStart = 0;
$set_value= 'BI';
$arrItemBc = KS_Menu::listItemsByMenu ( $objMenu->getId () , $set_value , $sSortOrder, $sStart, 1000 );
$totalRecords = $arrItemBc ['total'];

$strFields = '';
if( !isset ($menuitem_id)) {
	$menuitem_id = 0;
}
foreach ( $arrItemBc ['data'] as $curItemBc ) {
	$arrFoundFields [] = $curItemBc ['itemid'];
	$objFieldX = new KS_Menu ();
	$objFieldX->setId($curItemBc ['itemid']);
	if($objFieldX->exists()){
		$objFieldX->select ();
	}

	$action = '?menuitem_id='.$curItemBc ['itemid'];
	if($menuitem_id == $curItemBc ['itemid'] ){
		$highlightfn = "style=\"background-color:#CCC\"";
	}else{
		$highlightfn = "style=\"\"";
	}

	$strFields .= '<li><a '.$highlightfn.' href="' . $action . '" title="Menu item"><i class="glyphicon glyphicon-play-circle"></i> ' . $curItemBc ['tablename']. '</a></li>';
}
?>
<ul class="breadcrumb">
	<li><a href="list.php"><i class="glyphicon glyphicon-th-list"></i> <?php echo $ks_translate->_('Menu'); ?></a>
	</li>
	<li class="dropdown">Menu: <a class="dropdown-toggle" id="ks_table"
		data-toggle="dropdown"><?php echo $objMenu->getName ();?> <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="list.php">All Menu</a></li>
		<li><a href="list.php?tabId=1">Add Menu</a></li>
		<li class="divider"><a href="#"></a></li>
		<?php echo $strTables;?>
	</ul>
	</li>
	<?php if($ks_scriptname == 'item_display'){?>
	<li class="dropdown"><i class="glyphicon glyphicon-play-circle"></i>
	Properties Menu Item : <a class="dropdown-toggle" id="ks_field"
		data-toggle="dropdown" href="#"><strong><?php echo $objMenuitem->getLabel ();?></strong>
	<b class="caret"></b></a> in <a
		href="display.php?mid=<?php echo $objMenu->getId();?>&tabId=1"><strong><?php echo $objMenu->getName ();?></strong></a>
	<ul class="dropdown-menu">
		<li><a href="display.php?mid=<?php echo $objMenu->getId();?>&tabId=1">All menu
		item in <strong><?php echo $objMenu->getName ();?></strong></a></li>
		<li class="divider"><a href="#"></a></li>
		<?php echo $strFields;?>
	</ul>
	</li>
	<?php } elseif($ks_scriptname == 'item_modify'){?>
	<li class="dropdown"><i class="glyphicon glyphicon-play-circle"></i>
	Modify Menu Item : <a class="dropdown-toggle" id="ks_field"
		data-toggle="dropdown" href="#"><strong><?php echo $objMenuitem->getLabel ();?></strong>
	<b class="caret"></b></a> in <a
		href="display.php?mid=<?php echo $objMenu->getId();?>&tabId=1"><strong><?php echo $objMenu->getName ();?></strong></a>
	<ul class="dropdown-menu">
		<li><a href="display.php?mid=<?php echo $objMenu->getId();?>&tabId=1">All menu
		item in <strong><?php echo $objMenu->getName ();?></strong></a></li>
		<li class="divider"><a href="#"></a></li>
		<?php echo $strFields;?>
	</ul>
	</li>

	<?php } elseif($ks_scriptname == 'display') {?>
	<li class="active">Modify Properties in <a
		href="display.php?mid=<?php echo $mid;?>"><?php echo $objMenu->getName ();?></a></li>
		<?php } else {?>
	<li class="active">Properties</li>
	<?php }?>
</ul>
