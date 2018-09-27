<?php
include_once '../library.php';

$userid = '';
if (isset ( $_GET ['userid'] )) {
	$userid = KS_Filter::inputSanitize ( $_GET ['userid'] );
}

$msg = '';
if (isset ( $_GET ['msg'] )) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$red = '';
if (isset ( $_GET ['red'] )) {
	$red = KS_Filter::inputSanitize ( $_GET ['red'] );
	if (is_string ( $red )) {
		$red = urlencode ( $red ); // we're expecting URL
	}
}

// we produce secret keys
$key = md5 ( time () . KSCONFIG_DB_NAME );
$value = sha1 ( $key . date ( "Y-m-d" ) );

$showMessageBoxType = 'alert-info';
switch ($msg) {
	
	case 'userid_empty' :
		$msg_desc = "User ID must be entered.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'password_empty' :
		$msg_desc = "Password must be entered.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'user_notfound' :
		$msg_desc = "<strong>Error</strong>. User ID not found. Please login again.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'user_disabled' :
		$msg_desc = "User has been disabled. Contact Administrator to re-enable.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'password_wrong' :
		$msg_desc = "User ID / Password is wrong.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'logout' :
		$msg_desc = "You have been logged out due to session timeout.";
		$showMessageBoxType = 'alert-info';
		$showMessageBox = 1;
		break;
	
	case 'notlogin' :
	case 'session_timeout' :
		$msg_desc = "Please login. Your session may have expired.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'password_reset' :
		$msg_desc = "You new password has been set. Please login again.";
		$showMessageBoxType = 'alert-success';
		$showMessageBox = 1;
		break;
	
	case 'referrer' :
		$msg_desc = "Referrer error, you're try to login not from specified server (" . KSCONFIG_URL . ")";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../layout_header.php';

?>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-link"></i> <?php echo $ks_translate->_('Login'); ?></li>
</ul>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="alert <?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="glyphicon glyphicon-exclamation-sign"></i>
		<?php echo $msg_desc;?></div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Login</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" action="login_handler.php"
					method="post" name="formLogin" id="formLogin">
					<div class="form-group">
						<label for="userid" class="col-lg-3 control-label">User ID : </label>
						<div class="col-lg-9">
							<input type="text"
								class="ks-form-control form-control validate[required,custom[onlyLetterNumber]]"
								name="userid" id="userid" placeholder="User ID"
								value="<?php echo $userid;?>">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-lg-3 control-label">Password :</label>
						<div class="col-lg-9">
							<input type="password"
								class="ks-form-control form-control validate[required]"
								id="password" name="password" placeholder="Password"
								autocomplete="off">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-9">
							<button type="submit" class="btn btn-primary">
								<i class="glyphicon glyphicon-link"></i> Login
							</button>
							or <a href="lostpassword.php">Retrieve lost password</a> <input
								type="hidden" name="key" value="<?php echo $key;?>" /> <input
								type="hidden" name="value" value="<?php echo $value;?>" /> <input
								type="hidden" name="red" value="<?php echo $red;?>" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	try {
	 	$("#userid").focus();
		$("#formLogin").validationEngine();
	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});
</script>
</div>
<?php
include_once '../layout_footer.php';
?>