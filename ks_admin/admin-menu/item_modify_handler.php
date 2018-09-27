<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$menuitem_id = KS_Filter::inputSanitize ( $_POST ['menuitem_id'] );
$mid = KS_Filter::inputSanitize ( $_POST ['menu_id'] );
$notlogin = ( int ) $_POST ['notlogin'];
$label = KS_Filter::inputSanitize ( $_POST ['label'] );
$urltype = KS_Filter::inputSanitize( $_POST ['url'] );
$urlinternal = KS_Filter::inputSanitize ( preg_replace ( '/^\//', "", $_POST ['urlinternal'] ) );
$urlexternal = KS_Filter::inputSanitize ( preg_replace ( '/^\//', "", $_POST ['urlexternal'] ) );
$tooltip = KS_Filter::inputSanitize ( $_POST ['tooltip'] );
$mio_icon = KS_Filter::inputSanitize ( $_POST ['mio_icon'] );

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

$objMenuitem = new KS_Menuitem ();
$objMenuitem->setId ( $menuitem_id );
$objMenuitem->setMenuid ( $mid );
$objMenuitem->setNotlogin ( $notlogin );
$objMenuitem->setLabel ( $label );
$objMenuitem->setUrltype ( $urltype );
$objMenuitem->setUrl ( $url );
$objMenuitem->setTooltip ( $tooltip );
$objMenuitem->setRoles ( $roles );
$objMenuitem->setOption($optionicon);
$objMenuitem->update ();

$redirect = "item_display.php?menuitem_id=$menuitem_id&msg=menuitem_updated";

header ( "Location: $redirect" );