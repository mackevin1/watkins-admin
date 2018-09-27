<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = ( int ) $_POST ['menu_id'];
$parent_id = ( int ) $_POST ['menuitem_id'];
$label = KS_Filter::inputSanitize ( $_POST ['label'] );
$urltype = KS_Filter::inputSanitize ( $_POST ['url'] );
$urlinternal = KS_Filter::inputSanitize ( preg_replace ( '/^\//', "", $_POST ['urlinternal'] ) );
$urlexternal = KS_Filter::inputSanitize ( preg_replace ( '/^\//', "", $_POST ['urlexternal'] ) );
$tooltip = KS_Filter::inputSanitize ( $_POST ['tooltip'] );
$mio_icon = KS_Filter::inputSanitize ( $_POST ['mio_icon'] );

$notlogin = 0; 
if (isset ($_POST ['notlogin'])) {
	$notlogin = ( int ) $_POST ['notlogin'];
}

//serialize input icon
$arroption = array();
$arroption['mio_icon'] = $mio_icon;
$optionicon = serialize($arroption);

$url = $urlinternal ? $urlinternal : $urlexternal;

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
$objMenuitem->setParentid ( $parent_id );
$objMenuitem->setNotlogin ( $notlogin );
$objMenuitem->setLabel ( $label );
$objMenuitem->setUrltype ( $urltype );
$objMenuitem->setUrl ( $url );
$objMenuitem->setTooltip ( $tooltip );
$objMenuitem->setRoles ( $roles );
$objMenuitem->setOrder ( $order );
$objMenuitem->setOption($optionicon);
$objMenuitem->insert ();

$redirect = "display.php?mid=$mid&tabId=1&msg=added";

header ( "Location: $redirect" );