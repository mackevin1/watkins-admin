<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = (int) $_POST ['menu_id'];
$arrmenuitem = $_POST ['arrmenuitem']; // array
if (! is_array ( $arrmenuitem )) {
	$arrmenuitem = array ();
}

$arrsubmenu = $_POST ['arrsubmenu']; // array
if (! is_array ( $arrsubmenu )) {
	$arrsubmenu = array ();
}

$order = 1;
$curParentid = 0;
/* echo "<pre>";
print_r($_POST);
echo "</pre>";
exit; */

$prevParentid = 0;
foreach ( $arrmenuitem as $k => $curMenuitem ) {

	$curMenuitem = (int) $curMenuitem;
	
	$objMenuitem = new KS_Menuitem ();
	$objMenuitem->setId ( $curMenuitem );
	$objMenuitem->select ();
	$parentid = $objMenuitem->getParentid ();

	if ($arrsubmenu [$k] == 0) {
		 $prevParentid = 0;
	}else{
		for($i=($k-1);$i>=0;$i--){

			if($arrsubmenu [$i]<$arrsubmenu [$k]){
				 $prevParentid = $arrmenuitem[$i];
				break;
			}
		}
	}

	$objMenuitem->setParentid ( $prevParentid );
	$prevParentid = $curParentid;
	$objMenuitem->setOrder ( $order ++ );
	$objMenuitem->update ();
}

$redirect = "display.php?mid=$mid&tabId=3&msg=menuitem_updated";

header ( "Location: $redirect" );