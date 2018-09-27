<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mId = ( int ) $_GET ['mid'];

$selMenuitemId = 0;
if (isset ($_GET ['selmenuitemid'])) {
	$selMenuitemId = ( int ) $_GET ['selmenuitemid'];
}

$output = "<label class=\"pull-left\">&nbsp;&nbsp;&nbsp;&nbsp;Please choose Parent : </label>
<select class=\"form-control ks-form-control\" name='menuitemid' id='menuitemid' size='1'>";
$tot = 0;

$sql = "SELECT mi_id, mi_label FROM ks_menuitem WHERE mi_menuid='$mId' ORDER BY mi_order ASC";
$stmt2 = $ks_db->query ( $sql );

$totalRecords = 0;
while ( true == ($row2 = $stmt2->fetch ()) ) {
	$totalRecords += 1;
	$desc = $row2 ['mi_label'];
	$code = $row2 ['mi_id'];
	$selected = ($selMenuitemId == $code) ? "selected" : "";
	$output .= "<option value='" . $code . "' $selected>$desc</option>";
}

if ($totalRecords == 0) {
	$output .= "<option value=''>-</option>";
}

echo $output . "</select>";

