<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = (int) $_POST ['menu_id'];
$parentid = ( int ) $_POST ['menuitemid'];
$dashboard_id = KS_Filter::inputSanitize ( $_POST ['dashboard_id'] );
$menutype = KS_Filter::inputSanitize ( $_POST ['menutype'] );
$label = KS_Filter::inputSanitize ( $_POST ['label'] );

$notlogin = 0;
if (isset ($_POST ['notlogin'])) {
	$notlogin = ( int ) $_POST ['notlogin'];
}

$urltype = "internal";
$url = KS_Filter::inputSanitize ( preg_replace ( '/^\//', "", $_POST ['url'] ) );
$tooltip = KS_Filter::inputSanitize ( $_POST ['tooltip'] );

$roles = '';
foreach ( $_POST as $k => $v ) {
	if (preg_match ( "/^role_/", $k )) {
		$roles .= $v . ";";
	}
}

//get max order
$sql1 = "SELECT MAX(mi_order) as max_order FROM ks_menuitem WHERE mi_menuid='$mid'";
$stmt1 = $ks_db->query ( $sql1 );
while ( true == ($row1 = $stmt1->fetch ()) ) {
	$order = $row1 ['max_order'] + 1;
}

$objMenuitem = new KS_Menuitem ();
$objMenuitem->setMenuid ( $mid );
$objMenuitem->setNotlogin ( $notlogin );
$objMenuitem->setLabel ( $label );
$objMenuitem->setUrltype ( "internal" );
$objMenuitem->setUrl ( $url );
$objMenuitem->setTooltip ( $tooltip );
$objMenuitem->setRoles ( $roles );
$objMenuitem->setOrder ( $order );

//set parent if menutype is sub menu
if ($menutype == 'sub') {
	$objMenuitem->setParentid ( $parentid );
} else {
	$objMenuitem->setParentid ( 0 );
}

$objMenuitem->insert ();

$redirect = "display.php?did=$dashboard_id&tabId=3&msg=menu_added";

header ( "Location: $redirect" );