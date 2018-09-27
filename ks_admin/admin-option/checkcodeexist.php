<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$option_code = KS_Filter::inputSanitize ( $_POST ['option_code'] );
$ocode = '';
if ( isset ($_POST['ocode'])) {
	$ocode = KS_Filter::inputSanitize ( $_POST ['ocode'] );
}

if($ocode == $option_code){
      $bFound = 0;
}else{
	$sql = "SELECT option_code FROM ks_option WHERE option_code  = ?";
	$stmt = $ks_db->query ( $sql, $option_code );

	if ($stmt->rowCount () > 0) {
		$bFound = 1;

	} else {
		$bFound = 0;

	}
}

echo $bFound;