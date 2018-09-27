<?php

include_once '../library.php';

$userid = '';
if (isset ( $_GET ['userid'] )) {
	$userid = KS_Filter::inputSanitize ( $_GET ['id'] );
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

//we produce secret keys
$key = md5( time() . KSCONFIG_DB_NAME);
$value = sha1 ($key . date ("Y-m-d"));

$showMessageBoxType = 'alert alert-info';
switch ($msg) {

	case 'userid_empty' :
		$msg_desc = "User ID must be entered.";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;

	case 'userid_notfound' :
		$msg_desc = "User ID not found.";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;

	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

$pageTitle = "Retrieve lost password";

include_once '../layout_header.php';
?>
<script>
$(document).ready(function(){
	try {
	 	$("#userid").focus();
		$("#formLostPassword").validationEngine();

	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});
</script>

<ul class="breadcrumb">
	<li class="active"><?php echo $ks_translate->_('Lost Password Retrieval'); ?></li>
</ul>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $msg_desc;?>
		</div>

		<form action="lostpassword1.php" method="post"
			name="formLostPassword" id="formLostPassword">
			<table width="90%" align="center" class="table table-bordered table-hover">
				<tbody>
					<tr>
						<th width="30%">User ID :</th>
						<td width="70%"><input type="text" name="userid" id="userid" value="<?php echo $userid;?>"
							placeholder="User ID"
							class="form-control ks-form-control validate[required,custom[onlyLetterNumber]]" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td align="left"><button type="submit" class="btn btn-primary">Get Password</button>
					<input type="hidden" name="key" value="<?php echo $key;?>" />
					<input type="hidden" name="value" value="<?php echo $value;?>" /></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
</div>
<?php
include_once '../layout_footer.php';
?>