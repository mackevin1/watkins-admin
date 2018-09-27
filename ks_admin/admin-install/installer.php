<?php

include_once 'installer_library.php';

if (file_exists ( $config_file )) {
	header ( "Location: error.php?msg=config_exists" );
	exit ();
}

$version = file_get_contents ( '../version.txt' );

function ks_microtime_diff($a, $b) {
	list ( $a_dec, $a_sec ) = explode ( " ", $a );
	list ( $b_dec, $b_sec ) = explode ( " ", $b );
	return $b_sec - $a_sec + $b_dec - $a_dec;
}

$ks_starttime = microtime ();

$gotError = false;

//check if PHP version matches requirement
$php_min_version = '5.2.0';
if (version_compare(PHP_VERSION, $php_min_version) >= 0) {
	$php_version_met = 1;
	$strPhpVersion = "\n<span><label class=\"label label-success\">PHP " . PHP_VERSION . "</label> Minimum PHP $php_min_version required.</span>";
} else {
	$gotError = 1;
	$php_version_met = 0;
	$strPhpVersion = "\n<span><label class=\"label label-danger\">PHP " . PHP_VERSION . "</label> Minimum PHP $php_min_version required. Please upgrade your PHP.</span>";
}

// check if required extensions are loaded
$ext_loaded_pdo_mysql = extension_loaded ( 'pdo_mysql' );
$ext_loaded_session = extension_loaded ( 'session' );

$msg = '';
if (! $ext_loaded_pdo_mysql || ! $ext_loaded_session) {
	$gotError = 1;
	$msg = 'ext_notloaded';
}

$showMessageBox = 0;
$showMessageBoxType = 'alert alert-info';
if (strlen ( $msg )) {
	switch ($msg) {

		case 'ext_notloaded' :
			$msg_desc = "PDO_mysql extension is not loaded. Please enable this in your PHP.ini.";
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
							<p>Checking Server Readiness</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							?>
							<p>The following are required. </p>
							<p><?php echo $strPhpVersion;?></p>
							<p><span class="label <?php echo $ext_loaded_pdo_mysql?'label-success':'label-danger';?>"><?php echo $ext_loaded_pdo_mysql?'Loaded':'Unloaded';?></span> PHP Extensions: PDO_Mysql</p>
						</div>
						<div class="modal-footer">
							<?php
							if ($gotError) {
								?>
							<button type="button" class="btn btn-primary" id="btnRetry" name="btnRetry"
								onclick="location.href='installer.php'">Retry</button>
							<?php 
							}
							?>
							<button type="button" class="btn btn-primary"
								<?php echo ($gotError)?"disabled":"";?>
								<?php echo ($gotError)?'':"onclick=\"location.href='installer1.php';\""; ?>>Next</button>
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
