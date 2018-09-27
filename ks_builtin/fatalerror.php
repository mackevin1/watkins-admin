<?php

$error = '';
if (isset ($_GET ['error'])) {
	$error = addslashes ( $_GET ['error'] );
}

$msg = '';
if (isset ($_GET ['msg'])) {
	$msg = addslashes ( $_GET ['msg'] );
}

session_start();
$emsg = '';
if ( isset ($_SESSION['ks_error'])) {
	$emsg = $_SESSION['ks_error'];
}

$showMessageBox = 1;
$showMessageBoxType = 'error';

switch ($msg) {
	
	case 'config_notfound' :
		$configFile = realpath ( __DIR__ . '/..' ) . DIRECTORY_SEPARATOR . "config.php";
		
		$installUrl = '../ks_admin/admin-install/';
		
		$msg_desc = "<p>Configuration file <strong>'config.php'</strong> is not found in <br/><strong>'$configFile'</strong>.</p>
		<p>Make sure it exists or readable (chmod 644 on Linux).</p>
		
		<p>The file may be deleted / renamed / removed to another folder. Please contact Administrator, or run the Installer again. Or you can use config_sample.php and rename it as config.php </p>
		<p><input type=button class=\"btn btn-danger\" value=\"Run Installer &gt;&gt;\" onclick=\"location.href='$installUrl';\"></p>";
		break;
	
	case 'log_error' :
		include_once '../config.php';
		$msg_desc = "<p>Failed to create / open log file <strong>'" . KSCONFIG_ERROR_LOG . "'</strong> due to permission error.</p>
				<p>Make sure it exists and readable (chmod 666 on Linux).</p>
		
				<p>The file may be deleted / renamed / removed to another folder. Please contact Administrator to create and make it writable.</p>
				
				<p><input type=button class=\"btn btn-danger\" value=\"Go Back\" onclick=\"location.href='../';\"></p>";
		break;
	
	case 'db_error' :
		$msg_desc = "Database connection failed. Ensure your configuration is correct and have permission to connect.";
		$msg_desc .= "<p>$emsg</p>";
		$msg_desc .= "<p>Edit 'config.php' to specify the correct parameters. 
				<p><input type=button class=\"btn btn-danger\" value=\"Go Back\" onclick=\"location.href='../';\"></p>";
		break;
}

?>

<style type="text/css">
.info,.error,.notice,.success {
	border: 1px solid #BBBBBB;
	margin-bottom: 20px;
}

.message_box_content {
	border: 1px solid #FFFFFF;
	padding: 10px;
}

.notice {
	color: #736B4C;
	text-align: center;
}

.error {
	background: none repeat scroll 0 0 #FDE9EA;
	border-color: #FDCED0;
	color: #A14A40;
	margin: 0 auto;
	text-align: center;
	width: 50%;
}

body {
	font-family: Verdana, Calibri;
}

.btn-primary {
	background-color: #428BCA;
	border-color: #357EBD;
	color: #FFFFFF;
}

.btn-danger {
	color: #ffffff;
	background-color: #d9534f;
	border-color: #d43f3a;
}

.btn {
	-moz-user-select: none;
	background-image: none;
	border: 1px solid rgba(0, 0, 0, 0);
	border-radius: 4px;
	cursor: pointer;
	display: inline-block;
	font-size: 14px;
	font-weight: normal;
	line-height: 1.42857;
	margin-bottom: 0;
	padding: 6px 12px;
	text-align: center;
	vertical-align: middle;
	white-space: nowrap;
	font-family: inherit;
}
</style>

<div class="col-lg-12 colborder" id="content">

	<h2 class="loud" align="center">Fatal Error</h2>
	<p align="center">The system encountered the following error and cannot
		run properly.</p>
	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox) ? '' : 'none';?>">
		<div class="message_box_content">
    	<?php echo $msg_desc;?>
    </div>
		<div class="clearboth"></div>
	</div>
</div>
<script>
if (top.location != self.location) {
	top.location = self.location;
}
</script>