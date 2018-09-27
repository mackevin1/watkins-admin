<?php

include_once '../library.php';

if (! class_exists ( 'CUSTOM_User' )) {
	echo "Class User doesn't exist! Use Class Generator to create it. Otherwise the forms will not work.";
	exit ();
}

if (session_id () == '') {
	session_start ();
}

$ks_session_group = KSCONFIG_DB_NAME;
if (isset ( $_SESSION [$ks_session_group] )) {
	$usr_id = $_SESSION [$ks_session_group] ['USR_ID'];
}

if (! strlen ( $usr_id )) {
	header ( "Location: login.php?msg=notlogin" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $usr_id );
if (! $objUser->exists ()) {
	header ( "Location: login.php?msg=user_notfound" );
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

switch ($msg) {

	case 'updated' :
		$msg_desc = 'User Profile has been updated successfully.';
		$showMessageBoxType = 'alert-success';
		$showMessageBox = 1;
		break;

	case 'updated_password' :
		$msg_desc = 'Password has been changed successfully.';
		$showMessageBoxType = 'alert-success';
		$showMessageBox = 1;
		break;

	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../layout_header.php';

?>
<script>
$(document).ready(function(){
});
</script>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-user"></i> <?php echo $ks_translate->_('User Profile'); ?></li>
</ul>

<div style="width: 60%; margin: 0 auto;">
	<div class="alert <?php echo $showMessageBoxType;?> alert-block" style="display: <?php echo ($showMessageBox) ? '' : 'none';?>">
		<?php echo $msg_desc;?>
	</div>
</div>

<table width="98%" cellpadding="4" border="0" align="center" cellspacing="1" class="table table-bordered table-hover">
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
			<th>Last Login:</th>
			<td><?php echo KS_Date::toDD_MM_YYYY( $objUser->getLastlogin () );?>  </td>
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
			<td align="left"><input type="submit" onclick="location.href='modify.php'" class="btn btn-primary" value="Update Profile" name="btnsubmit"> <input type="submit" onclick="location.href='changepassword.php';" class="btn btn-info" value="Change Password" id="btnChange" name="btnChange"></td>
		</tr>
	</tbody>
</table>
</div>
<?php
include ('../layout_footer.php');
?>