<?php

include_once '../library.php';

$did = 0;
if (isset ($_POST ['did'])) {
	$did = ( int ) $_POST ['did'];
}

$pid = 0;
if (isset ($_POST ['pid'])) {
	$pid = KS_Filter::inputSanitize ( $_POST ['pid'] );
}

$actionp = ''; 
if (isset ($_POST ['actionp'])) {
	$actionp = KS_Filter::inputSanitize ( $_POST ['actionp'] );
}

$typec = ''; 
if (isset ($_POST ['typec'])) {
	$typec = KS_Filter::inputSanitize ( $_POST ['typec'] );
}

$content = ''; 
if (isset ($_POST ['content'])) {
	$content = $_POST ['content'];//sent html code
}

$bColumns = '';
if (isset ($_POST ['bColumns'])) {
	$bColumns = KS_Filter::inputSanitize ( $_POST ['bColumns']) ;
}

$closedBoxes = ''; 
if (isset ($_POST ['closedBoxes'])) {
	$closedBoxes = KS_Filter::inputSanitize ( $_POST ['closedBoxes']);
}

$titleValue = ''; 
if (isset ($_POST ['titleValue'])) {
	$titleValue = KS_Filter::inputSanitize ( $_POST['titleValue']);
}

$cp = ''; 
if (isset ($_POST ['cp'])) {
	$cp = (int) $_POST['cp'];
}

$parameterp = $actionp. '_' . $did . '_' .  $pid . '_' . $typec . '_' . $cp;

$newPortlet = '';

KS_Dashboard::updateportlets($parameterp , $content ,$bColumns , $closedBoxes, $titleValue);

echo $newPortlet;
