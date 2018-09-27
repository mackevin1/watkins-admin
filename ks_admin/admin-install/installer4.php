<?php
include_once '../../library.php';
include_once 'installer_library.php';

//supposedly config.php exists.. if installer failed, they must put config.php
//otherwise, login etc will fail...
if (! file_exists ( $config_file )) {
	header ( "Location: installer2a.php" );
	exit ();
}

$admin_password = KS_Filter::inputSanitize ( $_POST ['admin_password'] );
$admin_username = KS_Filter::inputSanitize ( $_POST ['admin_username'] );
$admin_email = KS_Filter::inputSanitize ( $_POST ['admin_email'] );

$objOption = new KS_Option ();
$objOption->setCode ( 'admin_email' );
$objOption->setGroup ( 'General' );
$objOption->setValue ( $admin_email );
if ($objOption->exists ()) {
	$objOption->update ();
} else {
	$objOption->insert ();
}

$salt = substr ( md5 ( time () ), 0, 6 );

// insert into t_user
$objUser = new CUSTOM_User ();
$objUser->setId ( $admin_username );
$objUser->setEmail ( $admin_email );
$objUser->setPassword ( md5 ( $admin_password . $salt ) );
$objUser->setSalt ( $salt );
$objUser->setRole ( 'ADMIN' );
$objUser->setEnabled ( 1 );
$objUser->setDateCreated ( date ( "Y-m-d H:i:s" ) );
$objUser->setName ( 'Administrator' );
$objUser->setUseridCreated ( $admin_username );
if($objUser->exists()) {
	$objUser->update ();
} else {
	$objUser->insert ();
}
$showMessageBoxType = 'alert alert-info';

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}
$showMessageBox = 0;

if (strlen ( $msg )) {
	switch ($msg) {
		case 'ok' :
			$msg_desc = "Setup successful. Click 'Next' button to proceed.";
			$showMessageBoxType = 'alert alert-success';
			$showMessageBox = 1;
			$success = 1;
			break;
		
		default :
			$msg_desc = 'Installation Error. ' . $msg;
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
	}
}

include_once 'installer_header.php';
?>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="modal fade" id="myModal">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title"><?php echo $installerTitle;?></h2>
						</div>
						<div class="modal-body">
							<p>Step 4: Setup Finished</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							?>
							<p>Congratulations! Your system is now ready.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary"
								onclick="location.href='../../';"><i class="glyphicon glyphicon-home"></i> Home</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->

		</div>
	</div>
</div>
<script>
$(document).ready(function(){

	try {
		$('#myModal').modal({
			backdrop: 'static'
		});
		
	} catch(error) {
		var msg = "Fatal Error: " + error.description + " in function $(document).ready().";
		alert(msg);
	}
});
</script>
<?php
?>
</body>
</html>
