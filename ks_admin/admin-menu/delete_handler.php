<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$mid = ( int ) $_POST ['mid'];

$objMenu = new KS_Menu ( );
$objMenu->setId ( $mid );
$objMenu->delete ();

$sql1 = "DELETE FROM ks_menuitem WHERE mi_menuid='$mid'";
$stmt1 = $ks_db->query ( $sql1 );

$redirect = "list.php?&msg=deleted";

header ( "Location: $redirect" );