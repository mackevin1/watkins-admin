<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

// check form token to avoid form hijacking / CSRF
$ks_scriptname = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_scriptname']);
$ks_tokenpost = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_token']);
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = $_SESSION [$ks_tokenid];

if ($ks_tokenpost != $ks_token) {
	$redirect = "$ks_scriptname.php?msg=csrf_invalid";
	header ( "Location:$redirect" );
	exit ();
}

$today = date ( "Y-m-d" );

$name = KS_Filter::inputSanitize ( $_POST ['name'] );
$desc = KS_Filter::inputSanitize ( $_POST ['desc'] );
$noCols = (int)KS_Filter::inputSanitize ( $_POST ['columns'] );
$noPortlets = $noCols + 1;

if (!$noCols ) {
	$noCols = 1;
}
$eachCols = (int)($noPortlets / $noCols);
$balancePortlets = (int)($noPortlets % $noCols);

$bColumns = array ();
$bBox = array ();

$no = 0;
for ($i = 1; $i <= $noCols; $i++) {
	for ($j = 1; $j <= $eachCols; $j++) {
		$bColumns['col'.$i][$j]= 'box-'.++$no;
	}
}
if ($balancePortlets){

	for ($b = $eachCols+1; $b <= $eachCols+$balancePortlets; $b++) {
		$bColumns['col1'][$b]= 'box-'.++$no;
	}
}

$typecodd = "URL";
$contentodd = "http://codecanyon.com/";

$typeceven = "HTML";
$contenteven = "<i>Please put your html code here.</i>
<button type=\"button\" class=\"btn btn-primary\" onClick=\"location.href='../home.php';\">Go to home</button>
";

$typec3rd = "URL";
$content3rd = KSCONFIG_URL . "easypiechart.php";

$i = 0;	
foreach ( $bColumns as $curbColumns => $box ) {
	foreach ( $box as $curbBox) {
		$balancei = $i++ % 3;
		$arrportno = explode("-",$curbBox);
		$portno = $arrportno[1];

		if($balancei == 0){
			$bColumns[''][$curbBox]['type']= $typeceven ;
			$bColumns[''][$curbBox]['content']= $contenteven ;
			$bColumns[''][$curbBox]['title']= "Portlet ".$portno ;
		}else if ($balancei == 1) {
			$bColumns[''][$curbBox]['type']= $typecodd ;
			$bColumns[''][$curbBox]['content']= $contentodd ;
			$bColumns[''][$curbBox]['title']= "Portlet ".$portno ;
		}else{
			$bColumns[''][$curbBox]['type']= $typec3rd ;
			$bColumns[''][$curbBox]['content']= $content3rd ;
			$bColumns[''][$curbBox]['title']= "Portlet ".$portno ;
		}
	}
}

$serbColumns = serialize($bColumns);

$objDashboard = new KS_Dashboard();
$objDashboard->setTitle($name);
$objDashboard->setDesc($desc);
$objDashboard->setPortlet($serbColumns);
$objDashboard->setCreatedBy($usr_id);
$objDashboard->setCreatedDate ( $today );
$objDashboard->insert ();

$did = $objDashboard ->getId ();

if(! $did) {
	header("Location: list.php?msg=add_failed");
	exit;
}

$redirect = "display.php?did=$did&tabId=1&msg=added";

header ( "Location: $redirect" );