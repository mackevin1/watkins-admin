<?php

include_once 'library.php';

// if $pageTitle is not set, we use config..
// and we append the $pageTitle if specified in individual pages
if (strlen ( KSCONFIG_SYSTEM_NAME ) == 0) {
	$pageTitle = "Dynamic Admin Panel: " . $pageTitle;
} else {
	if (isset ($pageTitle)) {
		$pageTitle = KSCONFIG_SYSTEM_NAME . ": " . $pageTitle;
	} else {
		$pageTitle = KSCONFIG_SYSTEM_NAME;
	}
}

// check user authentication
$isAuth = CUSTOM_User::checkAuthentication ();
$ks_session = CUSTOM_User::getSessionData ();

$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];
$usr_email = $ks_session ['USR_EMAIL'];
$usr_role = $ks_session ['USR_ROLE'];

// check if user is Administrator or not.. Role ID is defined in
// CUSTOM_User::ROLE_ADMIN
$isAdmin = CUSTOM_User::isAdmin ();

$imageBannerPath = KSCONFIG_URL . "ks_images/header";

?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $pageTitle;?></title>

<!--To ensure proper rendering and touch zooming-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/smoothness/jquery-ui-1.10.4.custom.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/bootstrap.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/kspanel-1.0.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/default.css" />
</head>

<body>

<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-1.8.3.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-ui-1.10.4.custom.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/bootstrap-3.1.0.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/kspanel-1.0.js"></script>

<style>
.system-banner {
	background: url('<?php echo $imageBannerPath; ?>/blue_abstract.jpg'); /*Feel free to change this.. look into folder ks_images/header/ */
	font-size: 30px;
	font-weight: bold;
	padding: 20px; /* make the box appear bigger. safe to adjust. */
	margin-bottom: 10px; /*make spacing with navigation below */
	margin-top: 40px; /*how far the banner from top box? reducing this may overlap with the top navigation */
	text-shadow: 1px 1px #ffffff;
}
</style>
<div class="container">
<div class="pull-right">
<ul class="nav nav-pills">
<?php
//user is authenticated..
if($isAuth) {
	?>
	<li>
		<a href="<?php echo KSCONFIG_URL;?>home.php"><i class="glyphicon glyphicon-home"></i> <?php echo $ks_translate->_('Home'); ?></a>
	</li>
	<?php
	//if admin, we show link to control panel
	if($isAdmin == 1) {
		?>
	<li><a href="<?php echo KSCONFIG_URL;?>ks_admin" rel="tooltip" title="<?php echo $ks_translate->_('Control Panel'); ?>"
		data-placement="bottom"><i class="glyphicon glyphicon-cog"></i> <?php echo $ks_translate->_('Control Panel'); ?></a></li>
		<?php
	}
	?>
	<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" rel="tooltip" title="<?php echo $ks_translate->_('News'); ?>"
		data-placement="bottom" href="#"><i class="glyphicon glyphicon-star"></i> <?php echo $ks_translate->_('News'); ?> <span class="badge badge-important"
		id="ks_news_badge_unread"></span> <b class="caret"></b></a>
		<ul class="dropdown-menu" id="ks_news_dropdown">
			<li><a href="<?php echo KSCONFIG_URL;?>ks_builtin/newslist.php"><?php echo $ks_translate->_('Read all'); ?>..</a></li>
		</ul>
	</li>
	<li class="dropdown">
		<a href="#" data-toggle="dropdown" role="button"
			id="dropUser" class="dropdown-toggle"><i class="glyphicon glyphicon-user"></i> <?php echo $usr_name;?>
			<b class="caret"></b></a>
		<ul aria-labelledby="dropUser" role="menu" class="dropdown-menu" id="menu1">
			<li>
				<a href="<?php echo KSCONFIG_URL;?>ks_user/profile.php"><i class="glyphicon glyphicon-user"></i>
				<?php echo $ks_translate->_('Profile'); ?></a>
			</li>
			<li><a href="<?php echo KSCONFIG_URL;?>ks_user/changepassword.php">
				<i class="glyphicon glyphicon-edit"></i> <?php echo $ks_translate->_('Change Password'); ?></a>
			</li>
			<li class="divider"></li>
			<li><a href="<?php echo KSCONFIG_URL;?>ks_user/logout.php"><i class="glyphicon glyphicon-off"></i> <?php echo $ks_translate->_('Logout'); ?></a></li>
		</ul>
	</li>
	<?php
} else {
	//not authenticated..
	?>
	<li><a href="<?php echo KSCONFIG_URL;?>ks_user/login.php"><i class="glyphicon glyphicon-link"></i><?php echo $ks_translate->_('Login'); ?></a></li>
	<li><a href="<?php echo KSCONFIG_URL;?>ks_user/lostpassword.php"><i class="glyphicon glyphicon-off"></i>
	<?php echo $ks_translate->_('Lost Password'); ?></a></li>
	<?php
}
?>
</ul>
</div>
<div class="system-banner"><?php echo KSCONFIG_SYSTEM_NAME;?></div>

<?php 
//display the navigation menu that has the ID below
$mid= 1;
include KSCONFIG_ABSPATH . 'ks_builtin/menu.php';
?>