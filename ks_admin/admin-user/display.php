<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

if (! class_exists ( 'CUSTOM_User' )) {
	echo "Class User doesn't exist! Use Class Generator to create it. Otherwise the forms will not work.";
	exit ();
}

$id = KS_Filter::inputSanitize ( $_GET ['id'] );

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	echo "not exist";
	exit ();
}
$objUser->select ();

//get user's role name from ks_acl_role
$sqlRole = "SELECT role_id, role_name FROM ks_acl_role WHERE role_id = ?";
$stmtRole = $ks_db->query($sqlRole, $objUser->getRole());
while (true == ($rowRole = $stmtRole->fetch())) {
	$role = $rowRole['role_id'] . ' - ' . $rowRole['role_name'];
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBox = 0;
$showMessageBoxType = '';
$msg_desc = '';

switch ($msg) {
	case 'added' :
		$msg_desc = "User '$id' has been added.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	
	case 'updated' :
		$msg_desc = 'User information has been updated successfully.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	
	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';

?>

<div class="container">
    <?php
	include_once '../navbar_top.php';
	?>
	<ul class="breadcrumb">
		<li><a href="list.php"><i class="glyphicon glyphicon-user"></i> <?php echo $ks_translate->_('User'); ?></a></li>
		<li class="active">Display User</li>
	</ul>

	<p>User details page.</p>

	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $msg_desc;?>
	</div>
	<table width="98%" cellpadding="4" border="0" align="center"
		cellspacing="1" class="table table-bordered table-hover">
		<tbody>
			<tr>
				<th width="30%">Id:</th>
				<td width="70%"><strong><?php echo $objUser->getId();?></strong></td>
			</tr>
			<tr>
				<th>Name:</th>
				<td><?php echo $objUser->getName ();?></td>
			</tr>
			<tr>
				<th>Email:</th>
				<td><?php echo $objUser->getEmail ();?></td>
			</tr>
			<tr>
				<th>Role:</th>
				<td><?php echo $role;?></td>
			</tr>
			<tr>
				<th align="right">Enabled:</th>
				<td><?php echo ($objUser->getEnabled () == 1) ? '<span class="label label-info">Enabled</span>' : '<span class="label label-danger">Disabled</span>';?>&nbsp;</td>
			</tr>
			<tr>
				<th>Last Login:</th>
				<td><?php echo ($objUser->getLastlogin () != '0000-00-00 00:00:00')?KS_Date::toDD_MM_YYYY( $objUser->getLastlogin () ):'-';?>  </td>
			</tr>
			<tr>
				<th>Phone Mobile:</th>
				<td><?php echo $objUser->getPhoneMobile ();?></td>
			</tr>
			<tr>
				<th>Phone Office:</th>
				<td><?php echo $objUser->getPhoneOffice ();?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" name="btnsubmit"
					value="<?php echo $ks_translate->_('Modify'); ?>"
					onclick="location.href='modify.php?id=<?php echo $id;?>'"
					class="btn btn-primary" /> <input type="button" id="btnDelete"
					onclick="doDelete('<?php echo addslashes ( $id );?>');" value="Delete"
					class="btn btn-danger"></td>
			</tr>
		</tbody>
	</table>
</div>
<script>
function doDelete(userId) {
	if(confirm("Delete user '" + userId + "'?")) {
		location.href= "delete.php?id=" + userId;
	}
}
</script>
<?php
include_once '../footer.php';
?>