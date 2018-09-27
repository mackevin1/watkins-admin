<?php

@include_once '../library.php';
@include_once '../../library.php';

$controlPanelUrl = KSCONFIG_URL . 'ks_admin/';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo KSCONFIG_SYSTEM_NAME;?> Control Panel</title>

<!--To ensure proper rendering and touch zooming-->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="x-ua-compatible" content="IE=10">

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo KSCONFIG_URL;?>ks_styles/smoothness/jquery-ui-1.10.4.custom.min.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/bootstrap.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="<?php echo KSCONFIG_URL;?>ks_styles/kspanel-1.0.css" />
</head>

<body>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-1.8.3.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/bootstrap-3.1.0.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/jquery-ui-1.10.4.custom.min.js"></script>
<script src="<?php echo KSCONFIG_URL;?>ks_scripts/kspanel-1.0.js"></script>

<script>
$(document).ready(function () {
	  jQuery('.submenu').hover(function () {
	        jQuery(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
	    }, function () {
	        jQuery(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
	    }).find("a:first").append(" &raquo; ");

    $("[rel=tooltip]").tooltip();
});
</script>