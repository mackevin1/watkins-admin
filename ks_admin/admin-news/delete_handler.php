<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$nid = ( int ) $_POST ['nid'];

$objNews = new KS_News();
$objNews->setId ( $nid );
$objNews->delete ();

$sql1 = "DELETE FROM ks_news WHERE ns_id='$nid'";
$stmt1 = $ks_db->query ( $sql1 );

$redirect = "list.php?&e=deleted";

header ( "Location: $redirect" );