<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$mid = ( int ) $_POST ['menu_id'];
$item_id = ( int ) $_POST ['item_id'];

$objMenuitem = new KS_Menuitem ( );
$objMenuitem->setId ( $item_id );
$objMenuitem->delete ();

$redirect = "display.php?id=$mid&tabId=1&msg=deleted";

header ( "Location: $redirect" );