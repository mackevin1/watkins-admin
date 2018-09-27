<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime() );
$ks_tokenid = 'token_' .$ks_scriptname;
$_SESSION [$ks_tokenid] =  $ks_token;

$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$objDashboard = new KS_Dashboard ();
$objDashboard->setId ( $did );
if (! $objDashboard->exists ()) {
	header("Location: list.php?msg=notexist&did=$did");
	exit ();
}
$objDashboard->select ();

$bColumns = unserialize($objDashboard->getPortlet());
$countCols = 0;
$countBoxs = 0;
if($bColumns){
	foreach ( $bColumns as $curbColumns => $box ) {
		if ($curbColumns != ''){
		$countCols = ++$countCols;
		foreach ( $box as $curbBox) {
			$countBoxs = ++$countBoxs;
		}
		}
	}
}

include_once '../header_bootstrap.php';

?>
<script type="text/javascript" src="../ks_scripts/html5slider.js"></script>       
	<div class="container">
	<?php
	include_once '../navbar_top.php';
	
	include_once 'breadcrumb.php';
	?>
<form name="formModify" method="post" id="formModify"
	action="modify_handler.php">
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
			<th width="25%" align="right">Title :</th>
			<td width="75%"><input
			 name="name" type="text" id="name" size="50" maxlength="255"
				value="<?php echo $objDashboard->getTitle ();?>" class="form-control ks-form-control validate[required]"/>
				<font color="#FF0000">*</font></td>
		</tr>
		<tr>
			<th align="right">Description :</th>
			<td>
			<textarea class="form-control ks-form-control" name="desc" cols="50" id="desc"><?php echo $objDashboard->getDesc ();?></textarea></td>
		</tr>
		<tr>
			<th align="right">Number of Columns :</th>
			<td><label class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<input type="range" name="columns" id="columns" min="1" max="6"/></label>
			<input class="form-control ks-form-control" id="txt_columns" name="txt_columns"
				tabindex="0" size="1" maxlength="1"></td>
		</tr>
		<tr style="display:none">
			<th align="right">Number of Portlets :</th>
			<td><input id="portlets" type="text"
				value="<?php echo $countBoxs;?>"
				name="portlets" size="12" maxlength="10" /></td>
		</tr>
		<tr id="trsubmit">
			<td></td>
			<td align="left"><input
			name="btnSearchTeam" type="submit" value="Save" class="btn btn-primary"/> or <a
				href="display.php?did=<?php echo $did;?>">Cancel <input type="hidden"
				name="did" id="did" value="<?php echo $did?>" /> </a>
				
			<input	type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
			<input type="hidden" name="ks_scriptname" value="<?php echo $ks_scriptname;?>" /></td>
		</tr>
       
	</tbody>
</table>
</form>

</div>

<script>
$(document).ready(function() {
						   
	$("#name").focus();
							   
	$("#columns").val(<?php echo $countCols;?>);
	$("#txt_columns").val(<?php echo $countCols;?>);

	$("#nwstartDate").datepicker({
		dateFormat: 'dd/mm/yy',
		buttonImage: '../../ks_images/icons_calendar/calendar0.gif',
		buttonImageOnly: true,
		showOn: 'both',
		changeMonth: true,
		changeYear: true
	});

	$("#nwendDate").datepicker({
		dateFormat: 'dd/mm/yy',
		buttonImage: '../../ks_images/icons_calendar/calendar0.gif',
		buttonImageOnly: true,
		showOn: 'both',
		changeMonth: true,
		changeYear: true
	});
});

function validateDate(){
	try {
		var strMula = $("#nwstartDate").val();
		var strSiap = $("#nwendDate").val();

		var year1	= eval(strMula.split('/')[2] - 0);
		var mon1	= eval(strMula.split('/')[1] - 1);
		var day1	= eval(strMula.split('/')[0] - 0);
		var utcMula = Date.UTC(year1,mon1,day1);

		var year2	= eval(strSiap.split('/')[2] - 0);
		var mon2	= eval(strSiap.split('/')[1] - 1);
		var day2	= eval(strSiap.split('/')[0] - 0);
		var utcSiap = Date.UTC(year2,mon2,day2);


		if(strMula == '00/00/0000' || strMula == '') {
			alert("Please Enter Start Date.");
			document.returnValue = false;
		}else if(strSiap == '00/00/0000' || strSiap == '') {
			alert("Please Enter End Date.");
			document.returnValue = false;
		}else if(utcMula > utcSiap ) {
			alert("End Date must be greater then Start Date.Please Enter Again.");
			document.returnValue = false;
		}else if(utcMula == utcSiap ) {
			alert("End Date must be greater then Start Date.Please Enter Again.");
			document.returnValue = false;
		}else {
			document.returnValue = true;
		}
	}catch(e) {
		alert("JS Fatal Error in validateDate(): " + e.message);
	}
}

$("#columns").change (function() {
	$("#txt_columns").val($(this).val());
});
$("#txt_columns").change (function() {
	if(($(this).val()) > 6){
		var valcol = 6;
	}else{
		var valcol = $(this).val();
	}
	$("#txt_columns").val(valcol);
	$("#columns").val(valcol);
});

</script>

<?php
include_once '../footer.php';
?>
