<?php
ini_set ( 'max_execution_time', 0 );
include_once 'installer_library.php';

if (file_exists ( $config_file )) {
	header ( "Location: error.php?msg=config_exists" );
	exit ();
}

$test = 0;
if (isset ($_POST ['test'])) {
	$test = ( int ) $_POST ['test'];
}

$system_name = inputSanitize ( $_POST ['system_name'] );
//$system_template = inputSanitize ( ( int ) $_POST ['system_template'] );

$DBhostname = '';
if (isset ($_POST ['DBhostname'])) {
	$DBhostname = inputSanitize ( $_POST ['DBhostname'] );
}

$DBuserName = '';
if (isset ($_POST ['DBuserName'])) {
	$DBuserName = inputSanitize ( $_POST ['DBuserName'] );
}

$DBpassword = '';
if (isset ($_POST ['DBpassword'])) {
	$DBpassword = inputSanitize ( $_POST ['DBpassword'] );
}

$DBname = '';
if (isset ($_POST ['DBname'])) {
	$DBname = strtolower( inputSanitize ( $_POST ['DBname'] ) );
}

$DBtype = '';
if (isset ($_POST ['DBtype'])) {
	$DBtype = inputSanitize ( $_POST ['DBtype'] );
}

$DBport = '';
if (isset ($_POST ['DBport'])) {
	$DBport = inputSanitize ( $_POST ['DBport'] );
}

$success = 0;
$msg = 'enter_info';
$config_new_content = '';

$errors = array ();

if ($test == 1) {
	// check database connection
	if ($DBtype == "mysql") {
		$ext_loaded_pdo_mysql = extension_loaded ( 'pdo_mysql' );
		
		if ($ext_loaded_pdo_mysql) {
			
			if (! $DBport) {
				$DBport = 3306;
			}
			
			// ini_set ( 'error_reporting', E_ALL & ~ E_NOTICE & ~ E_WARNING );
			ini_set ( 'error_reporting', E_ALL & ~ E_NOTICE & ~E_WARNING);
			
			$KSCONFIG_CLASS_PATH = realpath ( __DIR__ . '/../..' ) . DIRECTORY_SEPARATOR . "ks_library/";
			if (substr ( PHP_OS, 0, 3 ) == 'WIN') {
				set_include_path ( ";" . $KSCONFIG_CLASS_PATH );
			} else {
				set_include_path ( ":" . $KSCONFIG_CLASS_PATH );
			}

			include_once $KSCONFIG_CLASS_PATH . 'Zend/Loader/Autoloader.php';
			$autoloader = Zend_Loader_Autoloader::getInstance ();
			$autoloader->registerNamespace ( 'Zend_' );
			
			// dummy connect to 'mysql' dbname
			$configArray = array (
					'database' => array (
							'host' => $DBhostname,
							'username' => $DBuserName,
							'password' => $DBpassword,
							'dbname' => 'information_schema',
							'port' => $DBport 
					) 
			);
			
			$ks_config = new Zend_Config ( $configArray );
			$dbParams = $ks_config->database->toArray ();
			$ks_db1 = new Zend_Db_Adapter_Pdo_Mysql ( $dbParams );
			
			try {
				
				if ($ks_db1->getConnection ()) {
					
					// everything looks ok, create database
					$msg = 'ok';
					$success = 1;
					
					// make the database..
					$sql = "CREATE DATABASE IF NOT EXISTS `$DBname` ";
					$ks_db1->query ( $sql );
					
					// we specify the actual database
					$configArray ['database'] ['dbname'] = $DBname;
					
					$ks_config = new Zend_Config ( $configArray );
					$dbParams = $ks_config->database->toArray ();
					$ks_db = new Zend_Db_Adapter_Pdo_Mysql ( $dbParams );
					
					if ($ks_db->getConnection ()) {
						
						// populate from SQL file..
						populate_mysql_db ( $ks_db );
						
						// create config file..
						$upOne = realpath ( __DIR__ . '/../..' );
						$config_file = $upOne . DIRECTORY_SEPARATOR . "config.php";
						
						$arrReturn = create_config_file ();
						$fail2CreateConfig = $arrReturn [0];
						$config_new_content = $arrReturn [1];
						
						if (session_id () == '') {
							session_start ();
						}
						// store the config in a session.. just in case they didnt
						// download, we want to show in installer3.php or
						// installer2a.php what to save..
						$_SESSION ['ks_config_new_content'] = $config_new_content;
						$_SESSION ['ks_config_file'] = $config_file;
						
						if ($fail2CreateConfig == 1) {
							$msg = 'config_failed';
							
							$next = 'installer2a.php';
						} else {
						}
					}
				}
			} catch ( Exception $e ) {
				$msg = 'wrong_auth';
				$pdo_error = $e->getMessage ();
			}
		} else {
			$msg = "ext_notloaded";
		}
	} else {
		$msg = "dbtype_notmysql";
	}
}
function create_config_file() {
	global $DBtype;
	global $DBhostname;
	global $DBuserName;
	global $DBpassword;
	global $DBname;
	global $DBport;
	global $system_name;
	//global $system_template;
	global $version;
	global $config_file;
	
	$server_name = $_SERVER ['SERVER_NAME'];
	$port = $_SERVER ['SERVER_PORT'];
	if ($port != 80) {
		$rooturl = "//$server_name:$port";
	} else {
		$rooturl = "//$server_name";
	}
	$rooturl = $rooturl . $_SERVER ['PHP_SELF'];
	$rooturl = str_replace ( "ks_admin/admin-install/", '', $rooturl );
	$rooturl = str_replace ( "installer2.php", '', $rooturl );
	// append trailing slash if none
	if (! preg_match ( '|/$|', $rooturl )) {
		$rooturl .= '/';
	}
	$config_new_content = "<?";
$config_new_content .="php\n";

$config_new_content .="/*base url to the installation folder.. ensure trailing slash */\n";
$config_new_content .="define ( 'KSCONFIG_URL', '$rooturl');\n\n";

$config_new_content .="/*system name, change as required */\n";
$config_new_content .="define ( 'KSCONFIG_SYSTEM_NAME', '$system_name');\n\n";

$config_new_content .="/*database type */\n";
$config_new_content .="define ( 'KSCONFIG_DB_TYPE', '$DBtype');\n\n";

$config_new_content .="/*database server hostname, domain or IP */\n";
$config_new_content .="define ( 'KSCONFIG_DB_HOST', '$DBhostname');\n\n";

$config_new_content .="/*database name / schema */\n";
$config_new_content .="define ( 'KSCONFIG_DB_NAME', '$DBname');\n\n";

$config_new_content .="/*database user name  */\n";
$config_new_content .="define ( 'KSCONFIG_DB_USER', '$DBuserName');\n\n";

$config_new_content .="/*database password for above user name */\n";
$config_new_content .="define ( 'KSCONFIG_DB_PASSWORD', '$DBpassword');\n\n";

$config_new_content .="/*port number to connect to the database..3306 is default port for mysql */\n";
$config_new_content .="define ( 'KSCONFIG_DB_PORT', $DBport);\n\n";

$config_new_content .="/*base path to the installation folder */\n";
$config_new_content .="define ( 'KSCONFIG_ABSPATH', dirname (__FILE__) . DIRECTORY_SEPARATOR);\n\n";

$config_new_content .="/*path to control panel.. if the folder is renamed, change it here */\n";
$config_new_content .="define ( 'KSCONFIG_CONTROLPANEL_PATH', KSCONFIG_ABSPATH . 'ks_admin/' );\n\n";

$config_new_content .="/*class path where all CUSTOM classes and Zend Framework are located*/\n";
$config_new_content .="define ( 'KSCONFIG_CLASS_PATH', KSCONFIG_ABSPATH . 'ks_library/');\n\n";

$config_new_content .="/*log all ks errors into this file */\n";
$config_new_content .="define ( 'KSCONFIG_ERROR_LOG', KSCONFIG_ABSPATH. 'error.txt');\n\n";

$config_new_content .="/*ksp version */\n";
$config_new_content .="define ( 'KSCONFIG_VERSION', '$version');\n\n";
	
	// now write the file
	$fail2CreateConfig = 0;
	
	if (! $fp = @fopen ( $config_file, 'w' )) {
		$fail2CreateConfig = 1;
	}
	
	if (! @fwrite ( $fp, $config_new_content )) {
		$fail2CreateConfig = 1;
	} else {
		fclose ( $fp );
	}
	
	return array (
			$fail2CreateConfig,
			$config_new_content 
	);
}
function populate_mysql_db($dbh, $sqlfile = 'mysql_install.sql') {
	try {
		
		global $errors;
		
		$query = fread ( fopen ( $sqlfile, "r" ), filesize ( $sqlfile ) );
		$pieces = split_sql ( $query );
		
		for($i = 0; $i < count ( $pieces ); $i ++) {
			$pieces [$i] = trim ( $pieces [$i] );
			
			if (! empty ( $pieces [$i] ) && $pieces [$i] != "#") {
				
				$pieces [$i] = str_replace ( "#__", '', $pieces [$i] );
				if (! $result = $dbh->query ( $pieces [$i] )) {
					$errors [] = array (
							$pieces [$i] 
					);
					$result;
				}
			}
		}
	} catch ( Exception $e ) {
		// echo 'Fatal Error: ' . __METHOD__ . $e->getMessage ();
	}
}
function split_sql($sql) {
	$sql = trim ( $sql );
	$sql = preg_replace ( "/\n#[^\n]*\n/", "\n", $sql );
	
	$buffer = array ();
	$ret = array ();
	$in_string = false;
	
	for($i = 0; $i < strlen ( $sql ) - 1; $i ++) {
		if ($sql [$i] == ";" && ! $in_string) {
			$ret [] = substr ( $sql, 0, $i );
			$sql = substr ( $sql, $i + 1 );
			$i = 0;
		}
		
		if ($in_string && ($sql [$i] == $in_string) && $buffer [1] != "\\") {
			$in_string = false;
		} elseif (! $in_string && ($sql [$i] == '"' || $sql [$i] == "'") && (! isset ( $buffer [0] ) || $buffer [0] != "\\")) {
			$in_string = $sql [$i];
		}
		if (isset ( $buffer [1] )) {
			$buffer [0] = $buffer [1];
		}
		$buffer [1] = $sql [$i];
	}
	
	if (! empty ( $sql )) {
		$ret [] = $sql;
	}
	return ($ret);
}

$showMessageBoxType = 'alert alert-info';
if (strlen ( $msg )) {
	switch ($msg) {
		
		case 'enter_info' :
			$msg_desc = "Enter the following and click 'Setup Database' button.";
			$showMessageBoxType = 'alert alert-info';
			$showMessageBox = 1;
			break;
		
		case 'ext_notloaded' :
			$msg_desc = "PDO_mysql extension is loaded. Please enable this in your PHP.";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
		case 'wrong_db' :
			$msg_desc = "The database details provided are incorrect and/or empty. Please try again.";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
		case 'wrong_auth' :
			$msg_desc = "Invalid information entered. Please try again.";
			$msg_desc .= "<br/><br/>MySQL error description: " . $pdo_error;
			
			if(preg_match('/SQLSTATE\[HY000\] \[2002\]/', $pdo_error)) {
				$msg_desc .= "<br/><br/>Is the hostname and port entered correctly?";
			}
			if(preg_match('/SQLSTATE\[28000\] \[1045\]/', $pdo_error)) {
				$msg_desc .= "<br/><br/>Is the username and password entered correctly?";
			}
			
			
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
		case 'dbtype_notmysql' :
			$msg_desc = "Please select a database type. Only MySQL is supported.";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			break;
		
		case 'config_failed' :
			$msg_desc = "Failed to create <strong>config.php</strong>. You have to download and save it into: <br/>'$config_file'";
			$showMessageBoxType = 'alert alert-danger';
			$showMessageBox = 1;
			$success = 1;
			break;
		
		case 'ok' :
			$msg_desc = 'Database setup successful and config.php has been created.';
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

// print_r($errors);

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
							<p>Step 2: Database Configuration</p>
							<?php
							if ($showMessageBox) {
								?>
							<div class="<?php echo $showMessageBoxType;?>">
								<?php echo $msg_desc;?>
							</div>
							<?php
							}
							
							if ($success != 1) {
								?>
							<form id="formInstaller2" name="formInstaller2" method="post"
								action="installer2.php">
								<table class="table table-bordered table-hover table-striped">
									<tbody>
										<tr>
											<th width="40%" align="right">Type</th>
											<td><label><input type="radio" checked="" value="mysql"
													name="DBtype"> MySQL</label></td>
										</tr>
										<tr>
											<th align="right">Hostname / IP Number</th>
											<td align="left"><input type="text" name="DBhostname"
												id="DBhostname"
												class="form-control ks-form-control validate[required,length[0,255]]"
												value="<?php echo $DBhostname?$DBhostname:'localhost';?>"> <font
												color="#ff0000">*</font></td>
										</tr>
										<tr>
											<th align="right">DB Name / schema</th>
											<td align="left"><input type="text" name="DBname" id="DBname"
												class="form-control ks-form-control validate[required,custom[ksdbname]]"
												value="<?php echo $DBname?$DBname:'kspanel1';?>" /> <font
												color="#ff0000">*</font><br/>
												<span class="label label-danger">Existing data will be deleted! Backup first before proceed.</span></td>
										</tr>
										<tr>
											<th align="right">User Name</th>
											<td align="left"><input type="text" name="DBuserName"
												id="DBuserName"
												class="form-control ks-form-control validate[required,custom[onlyLetterNumber]]"
												value="<?php echo $DBuserName?$DBuserName:'root';?>"> <font
												color="#ff0000">*</font></td>
										</tr>
										<tr>
											<th align="right">Password</th>
											<td align="left"><input type="password"
												class="form-control ks-form-control validate[optional]"
												name="DBpassword" id="DBpassword" value="" /></td>
										</tr>
										<tr>
											<th align="right">Port</th>
											<td align="left"><input type="text"
												class="form-control ks-form-control validate[required]"
												name="DBport" id="DBport"
												value="<?php echo $DBport?$DBport:'3306';?>" /> <font
												color="#ff0000">*</font></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td><input type="submit" value="Setup Database"
												id="btnSubmit" class="btn btn-primary" name="btnSubmit" 
												onclick="this.value='Setting up database..';this.disabled='disabled'; this.form.submit();"/> <input
												type="hidden" name="test" value="1" /> <input type="hidden"
												name="system_name" value="<?php echo $system_name;?>" /></td>
										</tr>
									</tbody>
								</table>
							</form>
							<?php
							} else {
								if ($fail2CreateConfig == 1) {
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
							<?php
								} else {
									echo '<p><label class="label label-info">Tips</label> You can change the database setting by editing the file: ' . $config_file . '</p>';
								}
							}
							?>
						</div>
						<?php
						if ($success == 1) {
							$url_next = 'installer3.php';
							
							if ($next) {
								$url_next = $next;
							}
							?>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary"
								<?php echo ($success)?"":"disabled";?><?php echo ($success)?"onclick=\"location.href='$url_next';\"":''; ?>">Next</button>
						</div>
						<?php
						}
						?>
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

		$("#formInstaller2").validationEngine();

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
