<?php
include_once '../library.php';
include_once 'header_isadmin.php';

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = 0; 
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

if (isset ($ks_log_unwritable)) {
	if($ks_log_unwritable == 1) {
		$msg = 'kslog_unwritable';
	}
}

$showMessageBox = 0;
$showMessageBoxType = '';
$msg_desc = '';
switch ($msg) {
	case 'kslog_unwritable' :
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		$msg_desc = "Error log <strong>'" . KSCONFIG_ERROR_LOG . "'</strong> is unwritable, no errors are logged.";
		$msg_desc .= " Error log is useful to identify errors especially Database errors.";
		if (substr ( PHP_OS, 0, 3 ) == 'WIN') {
			$msg_desc .= "<br/>On Windows, make sure this file is Writable (not Read-only).";
		} else {
			$msg_desc .= "<br/>On linux, run these:
					<br/>touch " . KSCONFIG_ERROR_LOG . "<br/>chmod 666 " . KSCONFIG_ERROR_LOG;
		}
}

include_once 'header_bootstrap.php';
?>

<div class="container"><?php
include_once 'navbar_top.php';
?>

<ul class="breadcrumb">
		<li class="active"><i class="glyphicon glyphicon-cog"></i> Control
			Panel</li>
	</ul>

	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $msg_desc;?>
	</div>

	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<li class="<?php echo ($tabId==0)?'active':'';?>"><a href="#section0"
				data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i>
					Modules</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
				<div class="section-content">
					<fieldset>
						<legend> Core Features</legend>
					</fieldset>

					<ul class="cpanel-icons list-unstyled">
						<?php
						if ($cp_dashboard_active) {
							?><li class="cpicon-green" onClick="location.href='admin-dashboard/'"><a
												href="admin-dashboard/"> <i class="glyphicon glyphicon-dashboard"></i><span>Dashboard</span></a></li>
						<?php
						}
						if ($cp_user_active) {
							?><li class="cpicon-blue" onClick="location.href='admin-user/'"><a
												href="admin-user/"><i class="glyphicon glyphicon-user"></i><span>User</span></a></li>
						<?php
						}
						if ($cp_menu_active) {
							?>	<li class="cpicon-red" onClick="location.href='admin-menu/'"><a
												href="admin-menu/"> <i class="glyphicon glyphicon-th-list"></i><span>Menu</span></a></li>
						<?php
						}
						if ($cp_option_active) {
							?>	<li class="cpicon-yellow" onClick="location.href='admin-option/'"><a
												href="admin-option/"><i class="glyphicon glyphicon-cog"></i><span>Option</span></a></li>
						<?php
						}
						if ($cp_news_active) {
							?><li class="cpicon-purple" onClick="location.href='admin-news/'"><a
												href="admin-news/"> <i class="glyphicon glyphicon-star"></i><span>News</span></a></li>
						<?php
						}
						if ($cp_acl_active) {
							?><li class="cpicon-black" onClick="location.href='admin-acl/'"><a
												href="admin-acl/"> <i class="glyphicon glyphicon-random"></i><span>ACL</span></a></li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include_once 'footer.php';
?>