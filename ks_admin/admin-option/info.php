<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$showMessageBoxType = 'info';

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

switch ($msg) {
	case 'updated' :
		$msg_desc = "<b>System Setting has been updated.</b>";
		$showMessageBoxType = 'success';
		$showMessageBox = 1;
		break;
	default :
		$msg_desc = "<b>config.inc.php will be auto generated.</b>";
		$showMessageBoxType = 'notice';
		$showMessageBox = 1;
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';
?>
<div class="container">
	  <?php
			include_once '../navbar_top.php';
			?>
<ul class="breadcrumb">
		<li class="active"><i class="glyphicon glyphicon-check"></i> <?php echo $ks_translate->_('Option'); ?></li>
	</ul>
	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $msg_desc;?></div>
	<br />
	<table width="80%" border="0" cellpadding="4" cellspacing="1"
		class="table">
		<tbody>
			<tr>
				<th width="30%" align="right">System name :</th>
				<td><?php echo KSCONFIG_SYSTEM_NAME;?></td>
			</tr>
			<tr>
				<th width="30%" align="right">System Base URL :</th>
				<td><?php echo KSCONFIG_URL;?></td>
			</tr>
			<tr>
				<th width="30%" align="right">Database name :</th>
				<td><?php echo KSCONFIG_DB_NAME;?></td>
			</tr>
		</tbody>
	</table>
</div>
<?php
include_once '../footer.php';
?>