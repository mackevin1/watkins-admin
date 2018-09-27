<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = KS_Filter::inputSanitize($_GET ['tabId']);
if(!$tabId){
	$tabId = 1;
}

$parentId = KS_Cpmenu::MENU_ACL;

$sql = "SELECT cpm_url FROM ks_controlpanel_menu WHERE cpm_id = '$parentId'";
$urlparent = $ks_db->fetchOne ( $sql );
if (!$urlparent){
	$urlparent = "admin-acl\"";
}

$objMenuItem = new KS_Cpmenu ( );
$objMenuItem->setSearchSQL ( "SELECT * FROM ks_controlpanel_menu WHERE cpm_parentid = '$parentId' AND cpm_status = 1 " );
$objMenuItem->setSearchSortOrder ( 'ASC' );
$objMenuItem->setSearchSortField ( 'cpm_order' );
$objMenuItem->setSearchRecordsPerPage ( 300 );
$arrMenuItem = $objMenuItem->search ();

$roleId = KS_Filter::inputSanitize ( $_GET ['roleId'] );

$objRole = new KS_Acl_Role ( );
$objRole->setSearchRecordsPerPage ( 1000 );
$arrRoles = $objRole->search ();
$totRoles = count($arrRoles);

foreach ( $arrRoles as $curRole ) {

	//if no roleId specified as $_GET, pick the first one
	if (! $roleId) {
		$roleId = $curRole->getId ();
	}

	$strSelected = ($curRole->getId () == $roleId) ? 'selected' : '';
	$strOptionRoles .= "\n<option value=\"{$curRole->getId()}\" $strSelected>{$curRole->getId()}: {$curRole->getName()}</option>";

}

$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );

switch ($msg) {
	case 'added' :
		$msg_desc = 'Role has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'updated' :
		$msg_desc = 'Role has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'deleted' :
		$msg_desc = 'Role has been deleted.';
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
?>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-random"></i> <?=$ks_translate->_('Access Control List'); ?></li>
</ul>

<div class="<?=$showMessageBoxType;?>" style="display: <?=($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?=$msg_desc;?></div>


<div class="tabbable tabs-left">
<ul class="nav nav-tabs">
<?php
$i = 1;
$j = 1;
foreach ( $arrMenuItem as $curMenuItem ) {
	$urlChild = $curMenuItem->getUrl ();
	$menuid = $curMenuItem->getId ();
	$labelChild = $curMenuItem->getLabel ();

	$urltabex = explode($urlparent,$urlChild);
	$urltab = $urltabex[1];
	
	?>
	<li class="<?=((int)$tabId===$i++)?'active':'';?>"><a
		href="#section<?=$j++;?>" data-toggle="tab"><i
		class="glyphicon glyphicon-chevron-right"></i><?=$labelChild;?></a></li>
		<?php
}
if($totRoles > 0) {
	foreach ( $arrRoles as $curRole ) {
		?>
	<li class="<?=($tabId==$curRole->getId ())?'active':'';?>"><a
		href="#section<?=$curRole->getId ();?>" data-toggle="tab"><i
		class="glyphicon glyphicon-chevron-right"></i>Role: <?=$curRole->getName ();?>&nbsp;</a></li>
		<?php
	}
}
?>
</ul>
<div class="tab-content">
<div class="tab-pane <?=((int)$tabId===1)?'active':'';?>" id="section1">

<div class="media">
<div class="media-body">Below are Roles registered in the system.
</p>
<div class="btn-group pull-right">
<button class="btn btn-primary"
	onClick="location.href='list.php?tabId=2';">Add KS Role</button>
<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span></button>
<ul class="dropdown-menu">
	<li><a onClick="location.href='list.php?tabId=2';">Add KS Role</a></li>
</ul>
</div>

</div>
</div>

<?php
if($totRoles > 0) {
	?>
<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr>
			<th align="center">#</th>
			<th>Role ID</th>
			<th>Role Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 1;
	foreach ( $arrRoles as $curRole ) {
	?>
		<tr>
			<td align="center"><?=$k++;?>.</td>
			<td><a href="list.php?tabId=<?=$curRole->getId ()?>" class="lead"><?=$curRole->getId ();?></a></td>
			<td align="left"><?=$curRole->getName ();?><br>
			<?=$curRole->getDesc ();?></td>
			<td align="center"><input type="button" id="btnModify"
				name="btnModify" value="Properties"
				onclick="location.href='list.php?tabId=<?=$curRole->getId ()?>'"
				class="btn btn-primary" /> <input type="button" id="btnDelete"
				name="btnDelete" value="Delete"
				onclick="location.href='roledisplay.php?roleId=<?=$curRole->getId ()?>&mode=delete&tabId=1'"
				class="btn btn-danger" /></td>
		</tr>
		<?php
	}
		?>
	</tbody>
</table>
		<?php
}
?>
</div>
<?php
$l = 1;
$m = 1;
$arrMenuSection = array_shift($arrMenuItem);
foreach ( $arrMenuItem as $curMenuItem ) {
	$urlChild = $curMenuItem->getUrl ();
	$menuid = $curMenuItem->getId ();
	$labelChild = $curMenuItem->getLabel ();

	$urltabex = explode('admin-acl/',$urlChild);
	$urltab = $urltabex[1];
	?>
<div class="tab-pane <?=($tabId==++$l)?'active':'';?>"
	id="section<?=++$m;?>">
<?php
include_once $urltab;
?>
</div>
<?php
}
if($totRoles > 0) {
	foreach ( $arrRoles as $curRole ) {

		?>
<div class="tab-pane <?=($tabId==$curRole->getId ())?'active':'';?>"
	id="section<?=$curRole->getId ();?>">
<form id="form1" name="form1" method="post" action="">
<p>Choose a Role: <select name="roleId"
	class="lead" id="roleId"
	onchange="location.href='list.php?tabId=' + this.value;">

	<?php foreach ( $arrRoles as $curRoleOp ) {
		?>
	<option value="<?=$curRoleOp->getId();?>"
	<?php if($curRoleOp->getId()==$curRole->getId ()){?> selected
	<?php }?>><?=$curRoleOp->getId();?> : <?=$curRoleOp->getName()?></option>
	<?
	}
	?>
</select></p>
</form>
<div class="btn-group pull-right">
<button class="btn btn-primary"
	onClick="openAddPriv<?=$curRole->getId ();?>();">Add Privilege</button>
<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span></button>
<ul class="dropdown-menu">
	<li><a onClick="openAddPriv<?=$curRole->getId ();?>();">Add Privilege</a></li>
	<li><a
		onClick="location.href='roledisplay.php?roleId=<?=$curRole->getId ();?>';">Role
	Properties</a></li>
</ul>
</div>
	<?php
	include 'display.php';
	?>
</div>
	<?php
	}
}
?></div>

</div>
</div>
</body>

