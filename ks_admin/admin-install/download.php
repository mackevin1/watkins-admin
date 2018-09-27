<?php

$strOutput = urldecode ( $_GET ['output'] );

$filename = 'config.php';

$now = gmdate ( 'D, d M Y H:i:s' ) . ' GMT';
header ( 'Content-Type: text-php' );
header ( 'Expires: ' . $now );
header ( 'Content-Disposition: attachment; filename="' . $filename . '"' );
header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
header ( 'Pragma: public' );
echo $strOutput;
exit ();