<?php

include_once 'installer_library.php';

$system_name = inputSanitize ( $_GET ['system_name'] );

$gotError = 0;

$msg = inputSanitize ( $_GET ['msg'] );

$showMessageBoxType = 'alert alert-info';
if (strlen ( $msg )) {
	switch ($msg) {
		
		case 'config_exists' : 
			$msg_desc = "Dynamic Admin Panel installation has been done. File 'config.php' already exists. ";
			$msg_desc .= "<p>If you need to change settings, edit the file '$config_file'.</p>";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
			
		case 'ext_notloaded' :
			$msg_desc = "PDO_mysql extension is loaded. Please enable this in your PHP.";
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
							<h2 class="modal-title">Dynamic Admin Panel Installer</h2>
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
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary"
								onclick="location.href='../../ks_user/login.php';">
								<i class="glyphicon glyphicon-home"></i> Go to main page</button>
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

