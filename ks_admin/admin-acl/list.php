<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = KS_Filter::inputSanitize($_GET ['tabId']);

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
$i = 0;
$j = 0;
foreach ( $arrMenuItem as $curMenuItem ) {
	$urlChild = $curMenuItem->getUrl ();
	$menuid = $curMenuItem->getId ();
	$labelChild = $curMenuItem->getLabel ();

	$urltabex = explode($urlparent,$urlChild);
	$urltab = $urltabex[1];
	
	?>
	<li class="<?=((int)$tabId===$i++)?'active':'';?>"><a
		href="#section<?=$j++;?>" data-toggle="tab"><i
		class="glyphicon glyphicon-chevron-right"></i> <?=$labelChild;?></a></li>
		<?php
}
?>
</ul>
<div class="tab-content">
<div class="tab-pane <?=($tabId==0)?'active':'';?>" id="section0">

<div class="media">
<div class="media-body">Below are Roles registered in the system.</div>
</div>
<div class="btn-group pull-right">
<button class="btn btn-primary"
	onClick="location.href='list.php?tabId=1';">Add Role</button>
<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span></button>
<ul class="dropdown-menu">
	<li><a onClick="location.href='list.php?tabId=1';">Add Role</a></li>
</ul>
</div>

<?php
if($totRoles > 0) {
	?>
	<br/><br/>
	<div class="table-responsive">  
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
			<td><a href="roledisplay.php?roleId=<?=$curRole->getId ()?>&tabId=1" class="lead"><?=$curRole->getId ();?></a></td>
			<td align="left"><?=$curRole->getName ();?><br>
			<?=$curRole->getDesc ();?></td>
			<td align="center"><input type="button" id="btnModify"
				name="btnModify" value="Properties"
				onclick="location.href='roledisplay.php?roleId=<?=$curRole->getId ()?>'"
				class="btn btn-primary" /> <input type="button" id="btnDelete"
				name="btnDelete" value="Delete"
				onclick="location.href='roledisplay.php?roleId=<?=$curRole->getId ()?>&mode=delete&tabId=3'"
				class="btn btn-danger" /></td>
		</tr>
		<?php
	}
		?>
	</tbody>
</table>
</div>
		<?php
}
?>
</div>
<?php
$l = 0;
$m = 0;
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
?>
</div>
</div></div>
<?php
include_once '../footer.php';
?>

