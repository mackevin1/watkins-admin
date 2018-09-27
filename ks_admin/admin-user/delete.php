<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$id = KS_Filter::inputSanitize ( $_GET ['id'] );

$objUser = new CUSTOM_User ();
$objUser->setId ( $id );
if (! $objUser->exists ()) {
	header ( "Location: list.php?msg=userid_notfound&usr_id=$id" );
	exit ();
}
$objUser->delete ();

header ( "Location: list.php?msg=userid_deleted&usr_id=$id" );
