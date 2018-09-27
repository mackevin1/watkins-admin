<?php

// must always include library.php
include_once 'library.php';

// check authentication
$isAuth = CUSTOM_User::checkAuthentication ();

if (! $isAuth) {
	header ( "Location: ks_user/login.php?msg=notlogin" );
	exit ();
}

$msg_desc = '';
$showMessageBox = 0;
$showMessageBoxType = '';

// if admin, show button to Control Panel
$isAdmin = CUSTOM_User::isAdmin ();
if ($isAdmin) {
	$showMessageBoxType = 'alert-info';
	$showMessageBox = 1;
	$msg_desc = $ks_translate->_ ( 'System Administrator functions are available in the Control Panel' );
	$msg_desc .= '<p class="text-center"><button type="button" class="btn btn-default" onClick="location.href=\'ks_admin/\';">
					<i class="glyphicon glyphicon-cog"></i> ' . $ks_translate->_ ( 'Go to Control Panel' ) . ' </button></p>';
}

// include page header that contains js and css files
include_once 'layout_header.php';

?>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-home"></i> <?php echo $ks_translate->_('Home'); ?></li>
</ul>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="alert <?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
			<p class="text-center"><?php echo $msg_desc;?></p>
		</div>
	</div>
</div>
<?php
//display dashboard with ID 1
KS_Dashboard::display ( 1 );

// include page footer
include_once 'layout_footer.php';
?>