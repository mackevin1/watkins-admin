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

//check user authentication
$isAuth = CUSTOM_User::checkAuthentication ();
$ks_session = CUSTOM_User::getSessionData ();

$usr_id = $ks_session['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];
$usr_email = $ks_session ['USR_EMAIL'];
$usr_role = $ks_session ['USR_ROLE'];

//check if user is Administrator or not.. Role ID is defined in CUSTOM_User::ROLE_ADMIN
$isAdmin = CUSTOM_User::isAdmin();

//if user is authenticated
if ($isAuth) {
	$strMenu = " <a href=\"" . KSCONFIG_URL . "home.php\">Home</a>";
	$strMenu .= " | <a href=\"" . KSCONFIG_URL . "ks_user/profile.php\">$usr_name</a>";
	if($isAdmin == 1) {
		$strMenu .= " | <a href=\"" . KSCONFIG_URL . "admin/\">Control Panel</a>";
	}
	$strMenu .= " | <a href=\"" . KSCONFIG_URL . "ks_user/logout.php\">Logout</a>";

} else {
	$strMenu .= " <a href=\"" . KSCONFIG_URL . "ks_user/login.php\">Login</a>";
}

?><!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $pageTitle;?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/smoothness/jquery-ui-1.10.4.custom.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/bootstrap.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/kspanel-1.0.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/default.css" />
</head>

<body>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-1.8.3.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-ui-1.10.4.custom.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/bootstrap-3.1.0.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/kspanel-1.0.js"></script>

<style>
body {
	/*overwrites to white since we dont have <div class="container">*/
	background-color: #ffffff;
}
</style>