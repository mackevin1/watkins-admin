<?php

include_once '../library.php';

session_start();
$usr_id = trim ( $_SESSION ['LOSTPASSWORD_USERID'] );

if (strlen ( $usr_id ) == 0) {
	header ( "Location: login.php?msg=session_timeout" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $usr_id );
if (! $objUser->exists ()) {
	header ( "Location: login.php?msg=user_notfound&id=$usr_id" );
	exit ();
}
$objUser->select ();

$password = KS_Filter::inputSanitize ( $_POST ['password'] );

//set new password.. with salt
$objUser->setPassword ( md5 ( $password . $objUser->getSalt() ) );
$objUser->update();

header("Location: login.php?msg=password_reset");
