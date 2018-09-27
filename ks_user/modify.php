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

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header ( "Location: login.php?msg=notlogin" );
	exit ();
}
$objUser->select ();

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

switch ($msg) {
	
	case 'updated' :
		$msg_desc = 'User information has been updated successfully.';
		$showMessageBoxType = 'success';
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
	 	$("#userid").focus();
		$("#formUpdate").validationEngine();

	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});
</script>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-edit"></i> <?php echo $ks_translate->_('Modify Profile'); ?></li>
</ul>

<div style="width: 60%; margin: 0 auto;">
	<div class="alert <?php echo $showMessageBoxType;?> alert-block" style="display: <?php echo ($showMessageBox) ? '' : 'none';?>">
		<?php echo $msg_desc;?>
	</div>
</div>

<form id="formUpdate" name="formUpdate" method="post"
	action="modify_handler.php">
	<table width="90%" cellpadding="4" border="0" align="center"
		cellspacing="1" class="table table-bordered table-hover">
		<tbody>
			<tr>
				<th width="30%">Id:</th>
				<td width="70%"><?php echo $usr_id;?></td>
			</tr>
			<tr>
				<th>Name:</th>
				<td><input type="text" ksrequirederror="This field is required."
					value="<?php echo $objUser->getName();?>"
					class="form-control ks-form-control validate[optional,minSize[0],maxSize[255] ]  word_count"
					size="50" maxlength="255" id="usr_name" name="usr_name"></td>
			</tr>
			<tr>
				<th>Email:</th>
				<td><input type="text" value="<?php echo $objUser->getEmail ();?>"
					class="form-control ks-form-control validate[custom[ksemail],optional,minSize[0],maxSize[50]] "
					size="30" maxlength="50" id="usr_email" name="usr_email"></td>
			</tr>
			<tr>
				<th>Phone Mobile:</th>
				<td><input type="text" value="<?php echo $objUser->getPhoneMobile ();?>"
					class="form-control ks-form-control validate[optional,minSize[0],maxSize[20] ]  word_count"
					size="30" maxlength="20" id="usr_phone_mobile"
					name="usr_phone_mobile"></td>
			</tr>
			<tr>
				<th>Phone Office:</th>
				<td><input type="text" value="<?php echo $objUser->getPhoneOffice ();?>"
					class="form-control ks-form-control validate[optional,minSize[0],maxSize[20] ]  word_count"
					size="30" maxlength="20" id="usr_phone_office"
					name="usr_phone_office"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align="left"><input type="submit" class="btn btn-primary"
					value="Save" name="btnsubmit"> or <a
					href="javascript:history.back();">Cancel</a></td>
			</tr>
		</tbody>
	</table>
</form>
</div>
<?php
include('../layout_footer.php');
?>