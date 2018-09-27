<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

// check form token to avoid form hijacking / CSRF
$ks_tokenpost = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_token']);
$ks_scriptname = preg_replace('/[^a-zA-Z0-9_.]+/', '', $_POST ['ks_scriptname']);
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = $_SESSION [$ks_tokenid];

if ($ks_tokenpost != $ks_token) {
	$redirect = "$ks_scriptname.php?msg=csrf_invalid";
	header ( "Location:$redirect" );
	exit ();
}

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$did = (int) $_POST ['did'];

$name = KS_Filter::inputSanitize ( $_POST ['name'] );
$desc = KS_Filter::inputSanitize ( $_POST ['desc'] );
$noCols = (int) KS_Filter::inputSanitize ( $_POST ['columns'] );
//$noPortlets = $noCols + 1;
$noPortlets = (int)KS_Filter::inputSanitize ( $_POST ['portlets'] );

$objDashboard = new KS_Dashboard();
$objDashboard->setId($did);
if (! $objDashboard->exists ()) {
	header("Location: list.php?msg=notexist&did=$did");
	exit ();
}
$objDashboard->select();

$arrportlets = unserialize($objDashboard->getPortlet());

$countBoxs = 0;
if($arrportlets){
	foreach ( $arrportlets as $curbColumns => $box ) {
		if($curbColumns != ''){

			foreach ( $box as $curbBox) {
				$countBoxs = ++$countBoxs;
			}
		}
	}
}

$noOldPortlets = $countBoxs;

if($noOldPortlets > $noPortlets){
	$noNewPortlets = $noOldPortlets;
}else{
	$noNewPortlets = $noPortlets;
}

if (!$noCols ) {
	$noCols = 1;
}
$eachCols = (int)($noNewPortlets / $noCols);
$balancePortlets = (int)($noNewPortlets % $noCols);

$bColumns = array ();
$bBox = array ();

$no = 0;
for ($i = 1; $i <= $noCols; $i++) {
	//$bColumns[$i]= 'col'.$i;

	for ($j = 1; $j <= $eachCols; $j++) {
		$bColumns['col'.$i][$j]= 'box-'.++$no;
	}
}

if ($balancePortlets){
	for ($b = $eachCols+1; $b <= $eachCols+$balancePortlets; $b++) {
		$bColumns['col1'][$b]= 'box-'.++$no;
	}
}

if(count($arrportlets['']) == 0){

	$serbColumns = serialize($bColumns);

} else{

	$arrclosedb = $arrportlets[''];
	$end = array(''=> $arrclosedb);

	$result = array_merge((array)$bColumns, (array)$end);
	$serbColumns = serialize($result);
}

//add title to each column
$bColumnsN = unserialize ( $serbColumns );
$countColsN = 0;
$countBoxsN = 0;
if ($bColumnsN) {
	foreach ( $bColumnsN as $curbColumnsN => $boxN ) {
		if ($curbColumnsN != '') {
			$countColsN = ++ $countColsN;
			foreach ( $boxN as $curbBoxN ) {
				$countBoxsN = ++ $countBoxsN;
				$existbox = count($bColumnsN[''][$curbBoxN]);
				if($existbox == 0){
					$typeceven = "HTML";
					$contenteven = "<i>Please put your html code here.</i>";
					$arrportno = explode("-",$curbBoxN);
					$portno = $arrportno[1];
					$bColumnsN[''][$curbBoxN]['type']= $typeceven ;
					$bColumnsN[''][$curbBoxN]['content']= $contenteven ;
					$bColumnsN[''][$curbBoxN]['title']= "Portlet ".$portno ;
				}
			}
		}
	}
}

$serbColumns = serialize($bColumnsN);

$objDashboard = new KS_Dashboard();
$objDashboard->setId ( $did );
if (! $objDashboard->exists ()) {
	echo "The dashboard with id ($did) does not exist.";
	exit ();
}
$objDashboard->setTitle($name);
$objDashboard->setDesc($desc);
$objDashboard->setPortlet($serbColumns);
$objDashboard->setModifiedBy($usr_id);
$objDashboard->setModifiedDate(date ( "Y-m-d" ));
$objDashboard->update();

/*ks_user(usr_option)*/

$objUser = new CUSTOM_User();
$objUser->setId($usr_id);
if (! $objUser->exists ()) {
	echo "The user with id ($usr_id) does not exist.";
	exit ();
}
$objUser->select();

$arrportletsuser = unserialize($objUser->getOption());
if(count($arrportletsuser[$did])> 0){
	$arrportletsuser[$did]= $bColumnsN ;
	$objUser = new CUSTOM_User();
	$objUser->setId($usr_id);
	if (! $objUser->exists ()) {
		echo "The user with id ($did) does not exist.";
		exit ();
	}
	$objUser->setOption(serialize($arrportletsuser));
	$objUser->update();
}

$redirect = "display.php?did=$did&msg=updated";

header ( "Location: $redirect" );