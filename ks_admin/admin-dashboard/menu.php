<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

//get menuitem linked to this form
$urlBuiltin = "ks_builtin/dashboard.php?did=$did";

$objMenuItem = new KS_Menuitem ();
$objMenuItem->setSearchSqlWhere ( " mi_url='$urlBuiltin' " );
$arrMenuItem = $objMenuItem->search ();
$totMenuItem = 0;
$totMenuItem = count ( $arrMenuItem );

//get menu preview
$totMenuAdded = 0;
if ($totMenuItem > 0) {

	//get menuid
	$arrMenuAdded = array ();
	$curMenuItem = new KS_Menuitem ();
	foreach ( $arrMenuItem as $curMenuItem ) {
		$arrMenuAdded [] = $curMenuItem->getMenuid ();
	}
	$totMenuAdded = count ( $arrMenuAdded );
}

//check remaining menu available..cannot add to menu if no menu available
$objMenu = new KS_Menu ();
$arrMenu = $objMenu->search ();
$totMenu = 0;
$totMenu = count ( $arrMenu );

if ($totMenu > 0) {
	$arrAllMenu = array ();
	$curMenu = new KS_Menu ();
	foreach ( $arrMenu as $curMenu ) {
		$arrAllMenu [] = $curMenu->getId ();
	}
}

if ($totMenu > 0 && $totMenuAdded > 0) {
	$result = array_diff ( $arrAllMenu, $arrMenuAdded );
	$canAddMenu = (count ( $result ) > 0) ? "" : "disabled";
}

include_once '../header_bootstrap.php';
?>
<p>
<?php
if ($totMenuItem > 0) {
	?>
This dashboard appear in <?php echo $totMenuAdded;?> menu :
</p>
			<table class="table table-bordered table-hover table-striped" cellpadding="4" cellspacing="1">	
				<thead>
				<tr align="center">
					<th>#.</th>
					<th>Menu</th>
					<th>Action</th>
				</tr>
				</thead><tbody>
	<?php
	$counter = 0;
	foreach ( $arrMenuAdded as $curMenu ) {

		$objMenu = new KS_Menu ();
		$objMenu->setId ( $curMenu );
		$objMenu->select ();
		?>
			<tr valign="top">
				<td align="center"><?php echo ++ $counter;?>.</td>
					<td align="left">
					<a href="../admin-menu/display.php?tabId=1&mid=<?php echo $curMenu;?>"><?php echo $objMenu->getName ();?></a>&nbsp;
					</td>
					<td align="left">
					<input class="btn btn-default" type="button" value="View"
					onclick="location.href='../admin-menu/display.php?tabId=1&mid=<?php echo $curMenu;?>'" />&nbsp;
					</td>
		<?php
	}
	?>
	</tbody>
	</table>
	<?php
} else {
	?>
<div class="alert alert-info" align="center">
This dashboard does not appear in any menu.
</div>
	<?php
}
?>
<p align="center"><input class="btn btn-primary" type="button"
	value="Add to Menu"
	onclick="location.href='menu_add.php?did=<?php echo $did;?>'"></p>
