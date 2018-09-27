<?php

include_once 'installer_library.php';

//supposedly config.php exists.. if installer failed, they must put config.php
//otherwise, login etc will fail...
if (! file_exists ( $config_file )) {
	header ( "Location: installer2a.php" );
	exit ();
}

$admin_password = '';
if (isset ($_POST ['admin_password'])) {
	$admin_password = inputSanitize ( $_POST ['admin_password'] );
}

$admin_username = '';
if (isset ($_POST ['admin_username'])) {
	$admin_username = inputSanitize ( $_POST ['admin_username'] );
}

$admin_email = '';
if (isset ($_POST ['admin_email'])) {
	$admin_email = inputSanitize ( $_POST ['admin_email'] );
}

$showMessageBox = 0;
$showMessageBoxType = 'alert alert-info';

$msg = '';
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

if (strlen ( $msg )) {
	switch ($msg) {
		case 'enter_info' :
			$msg_desc = "Enter the following and click 'Test Connection' button.";
			$showMessageBoxType = 'alert alert-info';
			$showMessageBox = 1;
			break;
		
		case 'dbtype_notmysql' :
			$msg_desc = "Please select a database type. Only MySQL is supported.";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
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
							<p>Step 3: Administrator Login Info</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							?>
							<form id="formInstaller3" name="formInstaller3" method="post"
								action="installer4.php">
								<table class="table table-bordered table-hover table-striped">
									<tbody>
										<tr>
											<th align="right">Admin Username</th>
											<td align="left"><input type="text" name="admin_username"
												id="admin_username"
												class="form-control ks-form-control validate[required,custom[onlyLetterNumber]]"
												value="<?php echo $admin_username?$admin_username:'admin';?>"> <font
												color="#ff0000">*</font></td>
										</tr>
										<tr>
											<th align="right">Password, twice</th>
											<td align="left"><input type="password"
												class="form-control ks-form-control validate[required]"
												name="admin_password" id="admin_password" value="" autocomplete="off" />
												<font color="#ff0000">*</font> <br /><br/> <input type="password"
												class="form-control ks-form-control validate[required,equals[admin_password]]"
												name="admin_password2" id="admin_password2" value="" autocomplete="off"/> <font
												color="#ff0000">*</font></td>
										</tr>
										<tr>
											<th align="right">Admin Email</th>
											<td align="left"><input type="text" name="admin_email"
												id="admin_email" size="30"
												class="form-control ks-form-control validate[custom[email],required,minSize[0],maxSize[50]]"
												value="<?php echo $admin_email?$admin_email:'';?>"
												ksRequiredError="This field is required."> <font
												color="#ff0000">*</font>
												<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;"/></td>
										</tr>
									</tbody>
								</table>
							</form>
							<p><label class="label label-info">Tips</label> Use secure Username and strong password to avoid hacking.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="$('#formInstaller3').submit();">Next</button>
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
		$("#formInstaller3").validationEngine();
		$("#admin_password").focus();
		
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
