<?php

include_once 'installer_library.php';

if (file_exists ( $config_file )) {
	header ( "Location: error.php?msg=config_exists" );
	exit ();
}

$system_name = '';
if (isset ($_GET ['system_name'])) {
	$system_name = inputSanitize ( $_GET ['system_name'] );
}

$gotError = 0;

$msg = '';
if (isset ($_GET ['msg'])) {
	$msg = inputSanitize ( $_GET ['msg'] );
}

$showMessageBox = 0;
$showMessageBoxType = 'alert alert-info';
if (strlen ( $msg )) {
	switch ($msg) {

		case 'ext_notloaded' :
			$msg_desc = "PDO_mysql extension is loaded. Please enable this in your PHP.";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
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
						<p>Step 1: System Information</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							?>
							<form id="formInstaller1" name="formInstaller1" method="post"
								action="installer2.php">
								<table class="table table-bordered table-hover table-striped">
									<tbody>
										<tr>
											<th width="40%" align="right">System Name</th>
											<td><input type="text" maxlength="100" size="30"
												value="<?php echo ($system_name)?$system_name:'Web Application';?>" id="system_name"
												class="form-control ks-form-control validate[required]"
												name="system_name"> <font color="#ff0000">*</font></td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary"
								<?php echo ($gotError)?"disabled":"";?>
								<?php echo ($gotError)?'':"onclick=\"$('#formInstaller1').submit();\""; ?>>Next</button>
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
		$("#formInstaller1").validationEngine();
		
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
