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
	$id = $_SESSION [$ks_session_group] ['USR_ID'];
}

if (! strlen ( $id )) {
	header ( "Location: login.php?msg=notlogin" );
	exit ();
}

//Check ACL.. if user's role is not granted privilege, redirect to error page
$ks_session = CUSTOM_User::getSessionData ();
$usr_role = $ks_session ['USR_ROLE'];

$objAcl = new KS_Acl ( );
$allowed__change_password = $objAcl->isAllowed ( $usr_role, 'user', 'change_password' );
if($allowed__change_password) {
	//do something if allowed
} else {
	//do something else
	header("Location: ../ks_builtin/error.php?msg=noprivilege");
	exit;
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header("Location: login.php?msg=notlogin");
	exit ();
}
$objUser->select ();

// minimum password length
$user_password_minlength = ( int ) KS_Option::getOptionValue ( 'user_password_minlength' );
if (! $user_password_minlength) {
	$user_password_minlength = 8;
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

switch ($msg) {
	
	case 'wrong_current' :
		$msg_desc = 'Wrong Current Password! Enter your current password.';
		$showMessageBoxType = 'alert alert-danger';
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

	try {
		$("#formChangepassword").validationEngine();
		$("#currentPwd").focus();

	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});
</script>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-unlink"></i> <?php echo $ks_translate->_('Change Password'); ?></li>
</ul>

<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>

<form action="changepassword_handler.php" method="post"
	name="formChangepassword" id="formChangepassword">
	<table width="90%" align="center"
		class="table table-bordered table-hover" cellpadding="4"
		cellspacing="1" border="0">
		<tbody>
			<tr>
				<th width="30%">Current Password :</th>
				<td width="70%"><input name="currentPwd" type="password"
					id="currentPwd" size="30" maxlength="30"
					class="form-control ks-form-control validate[required]" /></td>
			</tr>
			<tr>
				<th>New Password :</th>
				<td><input name="newPwd" type="password" id="newPwd" size="30"
					maxlength="30"
					class="form-control ks-form-control validate[required,minSize[<?php echo $user_password_minlength;?>],maxSize[32]]" /></td>
			</tr>
			<tr>
				<th>New Password (again):</th>
				<td><input name="newPwd2" type="password" id="newPwd2" size="30"
					maxlength="30"
					class="form-control ks-form-control validate[required,equals[newPwd],minSize[<?php echo $user_password_minlength;?>],maxSize[32]]" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" name="btnsubmit" value="Save"
					class="btn btn-primary" /> or <a href="javascript:history.back();">Cancel</a></td>
			</tr>
		</tbody>
	</table>
</form>
</div>

<?php
include ('../layout_footer.php');
?>