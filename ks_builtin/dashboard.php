<?php

include_once '../library.php';

session_start ();
$ks_session_group = KSCONFIG_DB_NAME;
$id = $_SESSION [$ks_session_group] ['USR_ID'];

if (! strlen ( $id )) {
	header ( "Location: ../ks_user/login.php?msg=notlogin" );
	exit ();
}

$did = ( int ) $_GET ['did'];

$objDashboard = new KS_Dashboard ();
$objDashboard->setId ( $did );
if (! $objDashboard->exists ()) {
	header("Location: error.php?msg=dashboard_notexist&did=$did");
	exit ();
}
$objDashboard->select ();

include_once '../layout_header.php';

?>

<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-dashboard"></i> <?php echo $ks_translate->_('Dashboard'); ?></li>
</ul>

<?php 
KS_Dashboard::display($did); 
    
include_once '../layout_footer.php';
