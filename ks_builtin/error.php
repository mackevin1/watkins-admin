<?php

include_once '../library.php';

$error = ''; 
if (isset ($_GET ['error'])) {
	$error = KS_Filter::inputSanitize ( $_GET ['error'] );
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$red = ''; 
if (isset ($_GET ['red'])) {
	$red = KS_Filter::inputSanitize ( $_GET ['red'] );
	if (is_string($red)) {
		$red = urlencode($red); //we're expecting URL
	}
}

$showMessageBox = 1;
$showMessageBoxType = 'error';

$loginUrl = KSCONFIG_URL . 'ks_user/login.php';
$loginUrl .= "?red=$red";

switch ($msg) {
	
	case 'notadmin' :
		$msg_desc = "Your User ID is  not an authorized Administrator to perform this task.
				<p><input type=button class=\"btn btn-danger\" value=\"Login\" onclick=\"location.href='$loginUrl';\"></p>";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'noprivilege' :
		$msg_desc = "Your User ID and Role is not authorized to perform this task.
				<p><input type=button class=\"btn btn-danger\" value=\"Login\" onclick=\"location.href='$loginUrl';\"></p>";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'listnotfound' :
		$lid = ( int ) $_GET ['lid'];
		
		$msg_desc = "The List specified is not found (\$lid = $lid). Perhaps it has been removed?";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'ddnotfound' :
		$did = ( int ) $_GET ['did'];
		
		$msg_desc = "The Data Dictionary specified is not found (\$did = $did). Perhaps it has been removed?";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'dashboard_notexist' :
		$did = ( int ) $_GET ['did'];
		
		$msg_desc = "Dashboard with ID ($did) is not found. Perhaps it has been deleted?";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
	
	case 'tablenotfound' :
		$tn = KS_Filter::inputSanitize( $_GET ['tn'] );
		
		$msg_desc = "The Table specified is not found (\$tn = $tn). Perhaps it has been removed?";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;

	case 'UnknownHost' :
		$msg_desc = "Invalid HTTP_REFERER domain.The domain does not match the allowed domain names of this form.";
		$showMessageBoxType = 'alert-danger';
		$showMessageBox = 1;
		break;
}


include_once '../layout_header.php';
?>
<ul class="breadcrumb">
	<li><a href="../home.php"><i class="glyphicon glyphicon-home"></i> <?php echo $ks_translate->_('Home'); ?></a>
		</li>
	<li class="active">

 <?php echo $ks_translate->_('Error'); ?></li></ul>
<p><?php echo $ks_translate->_('The following error has occured'); ?>.</p>

<div style="width: 60%; margin: 0 auto;">
	<div class="alert alert-danger text-center">
		<strong><?php echo $ks_translate->_('Error'); ?></strong>. <?php echo $msg_desc;?>
	</div>
</div>

</div>
<script>
if (top.location != self.location) {
	top.location = self.location;
}
</script>
<?php
include_once '../layout_footer.php';
?>
