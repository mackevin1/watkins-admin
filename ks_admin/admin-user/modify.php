<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

if (! class_exists ( 'CUSTOM_User' )) {
	echo "Class User doesn't exist! Use Class Generator to create it. Otherwise the forms will not work.";
	exit ();
}
 
//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime() );
$ks_tokenid = 'token_' .$ks_scriptname;
$_SESSION [$ks_tokenid] =  $ks_token;

$id = '';
if ( isset ( $_GET ['id'])) {
	$id = KS_Filter::inputSanitize ( $_GET ['id'] );
}

$usr_email = '';
if ( isset ( $_GET ['usr_email'])) {
	$usr_email = KS_Filter::inputSanitize ( $_GET ['usr_email'] );
}

$usr_name = '';
if ( isset ( $_GET ['usr_name'])) {
	$usr_name = KS_Filter::inputSanitize ( $_GET ['usr_name'] );
}

$usr_role = '';
if ( isset ( $_GET ['usr_role'])) {
	$usr_role = KS_Filter::inputSanitize ( $_GET ['usr_role'] );
}

$usr_phone_mobile = '';
if ( isset ( $_GET ['usr_phone_mobile'])) {
	$usr_phone_mobile = KS_Filter::inputSanitize ( $_GET ['usr_phone_mobile'] );
}

$usr_phone_office = '';
if ( isset ( $_GET ['usr_phone_office'])) {
	$usr_phone_office = KS_Filter::inputSanitize ( $_GET ['usr_phone_office'] );
}

$st = '';
if ( isset ( $_GET ['st'])) {
	$st = KS_Filter::inputSanitize ( $_GET ['st'] ); // status, either e(enabled) or d(disabled)
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	echo "not exist";
	exit ();
}
$objUser->select ();

// minimum password length
$user_password_minlength = ( int ) KS_Option::getOptionValue ( 'user_password_minlength' );
if (! $user_password_minlength) {
	$user_password_minlength = 8;
}

//build Role dropdown from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	//if matches $_GET['usr_role'], make it selected
	if($objUser->getRole() == $rowRole['role_id']) {
		$selected = 'selected';
	} else {
		$selected = '';
	}
	$optionRole .= "<option value=\"" . $rowRole['role_id'] . "\" $selected>" . $rowRole['role_id'] . " - " . $rowRole['role_name'] . "</option>\n";
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBox = 0;
$showMessageBoxType = '';
$msg_desc = '';

switch ($msg) {
	
	case 'email_invalid' :
		$msg_desc = 'Email is invalid. Please use a valid email address.';
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'userid_taken' :
		$msg_desc = "User ID '$id' is taken. Please use different User ID.";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'email_taken' :
		$userid_emailTaken = KS_Filter::inputSanitize ( $_GET ['userid_emailTaken'] );
		$msg_desc = "Email '$usr_email' is taken by user <a href=\"display.php?id=$userid_emailTaken\"><strong>'$userid_emailTaken'</strong></a>. Please use different email.";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'password_min' :
		$msg_desc = "Invalid password, must be at least $user_password_minlength characters.";
		$showMessageBoxType = 'alert alert-danger';
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
		<li class="active">Modify User</li>
	</ul>

	<p>User details page.</p>
	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $msg_desc;?>
			</div>
	<form action="modifyhandler.php" id="formModify" method="post"
		name="formModify">
		<table class="table table-bordered table-hover table-striped">
			<tbody>
				<tr>
					<th width="30%" align="right">Id:</th>
					<td width="70%"><?php echo $objUser->getId ();?></td>
				</tr>
				<tr>
					<th align="right">Name:</th>
					<td><input type="text" ksrequirederror="This field is required."
						value="<?php echo $objUser->getName();?>"
						class="form-control ks-form-control validate[required,minSize[0],maxSize[255] ]  word_count"
						size="50" maxlength="255" id="usr_name" name="usr_name"> <font
						color="#FF0000">*</font></td>
				</tr>
				<tr>
					<th align="right">Email:</th>
					<td><input type="text" value="<?php echo $objUser->getEmail ();?>"
						class="form-control ks-form-control validate[custom[ksemail],required,minSize[0],maxSize[50]] "
						size="30" maxlength="50" id="usr_email" name="usr_email"> <font
						color="#FF0000">*</font></td>
				</tr>
				<tr>
					<th align="right">Password:</th>
					<td><input type="password"
						ksrequirederror="This field is required." value="" autocomplete="off"
						class="form-control ks-form-control validate[optional,minSize[<?php echo $user_password_minlength;?>],maxSize[32] ]  word_count"
						size="30" maxlength="32" id="usr_password" name="usr_password">
						*Enter new password only. Leave blank to use existing password.</td>
				</tr>
				<tr>
					<th align="right">Role:</th>
					<td><select ksrequirederror="This field is required."
						class="form-control ks-form-control validate[required] "
						id="usr_role" name="usr_role">
							<?php echo $optionRole; ?>
					</select> <font color="#FF0000">*</font></td>
				</tr>
				<tr>
					<th align="right">Enabled:</th>
					<td><select ksrequirederror="This field is required."
						class="form-control ks-form-control validate[optional] "
						id="usr_enabled" name="usr_enabled">
							<option value="0" <?php echo ($objUser->getEnabled()==0)?'selected':'';?>>No</option>
							<option value="1" <?php echo ($objUser->getEnabled()==1)?'selected':'';?>>Yes</option>
					</select></td>
				</tr>
				<tr>
					<th align="right">Phone Mobile:</th>
					<td><input type="text" value="<?php echo $objUser->getPhoneMobile ();?>"
						class="form-control ks-form-control validate[optional,minSize[0],maxSize[20] ]  word_count"
						size="30" maxlength="20" id="usr_phone_mobile"
						name="usr_phone_mobile"></td>
				</tr>
				<tr>
					<th align="right">Phone Office:</th>
					<td><input type="text" value="<?php echo $objUser->getPhoneOffice ();?>"
						class="form-control ks-form-control validate[optional,minSize[0],maxSize[20] ]  word_count"
						size="30" maxlength="20" id="usr_phone_office"
						name="usr_phone_office"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left"><input type="submit" value="Save" name="btnsubmit"
						class="btn btn-primary"> or <a href="javascript:history.back();">Cancel</a>
						<input type="hidden" value="<?php echo $id;?>" id="id" name="id">
						<input type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
						<input type="hidden" name="ks_scriptname" value="<?php echo $ks_scriptname;?>" />
						</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>

<script>
$(document).ready(function(){
	$("#formModify").validationEngine();
	$("#usr_id").select();
});	
</script>
<?php
include_once '../footer.php';
?>