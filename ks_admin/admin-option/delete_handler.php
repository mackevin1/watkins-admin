<?php

include_once '../../library.php';
include_once '../header_isadmin.php';



$ocode = KS_Filter::inputSanitize ( $_POST ['ocode'] );

$objOption = new KS_Option();
$objOption->setCode($ocode);
$objOption->delete ();

$redirect = "list.php?&msg=deleted";

header ( "Location: $redirect" );