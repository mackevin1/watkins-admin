<?php

include_once 'installer_library.php';

//has config.php created?
if (file_exists ( $config_file )) {
	header ( "Location: installer3.php" );
	exit ();
}

session_start();

$config_new_content = $_SESSION['ks_config_new_content'];
$config_file = $_SESSION['ks_config_file'];

$gotError = 0;

$msg = 'config_notcreated';

$showMessageBoxType = 'alert alert-info';
if (strlen ( $msg )) {
	switch ($msg) {
		
		case 'config_notcreated' :
			$msg_desc = "Configuration file '<strong>config.php</strong>' doesn't exist. You must upload it into: ";
			$msg_desc .= "<br/>" . $_SESSION['ks_config_file'];
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
		default :
			$msg_desc = 'Uknown Error. Please contact Administrator. Message Code: "Empty msg".';
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
							<p>Installation Error</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							?>
							<p>
								<input type="button" name="button" id="button"
									value="Download and Save Config.php" class="btn btn-primary"
									onClick="location.href='download.php?output=<?php echo urlencode($config_new_content);?>';">
							</p>
							<p>
								<textarea rows="10" cols="80" readonly="readonly"
									disabled="disabled"><?php echo $config_new_content;?></textarea>
							</p>
							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary"
								onclick="location.href='installer2a.php';">
								I've saved the file</button>
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

		$("#system_name").focus();
	} catch(error) {
		var msg = "Fatal Error: " + error.description;
		alert(msg);
	}
});

</script>
<?php
?>
</body>
</html>

