<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime() );
$ks_tokenid = 'token_' .$ks_scriptname;
$_SESSION [$ks_tokenid] =  $ks_token;

$nid = KS_Filter::inputSanitize ( $_GET ['nid'] );

$objNews = new KS_News ();
$objNews->setId ( $nid );
if (! $objNews->exists ()) {
	echo "The news with id ($nid) does not exist.";
	exit ();
}
$objNews->select ();

$endDate = ($objNews->getEndDate () == '0000-00-00')?'-':displayBasedJs($objNews->getEndDate ());

function displayBasedJs($date) {
	
	$arrDate = explode ( "-", $date );
	$first = $arrDate [0]; //year
	$second = $arrDate [1]; // month
	$third = $arrDate [2]; // day

	$newdate = $third . "/" . $second . "/" . $first;
	
	return $newdate;
}

include_once '../header_bootstrap.php';

?>

	<div class="container">
	<?php
	include_once '../navbar_top.php';
	
	include_once 'breadcrumb.php';
	?>
<form name="formModify" method="post" id="formModify" action="modifyhandler.php" onSubmit="validateDate();return document.returnValue">
 <table class="table table-bordered table-hover table-striped">
	<tbody>
	<tbody>
		<tr>
			<th width="25%" align="right">Title :</th>
			<td width="75%"><input class="form-control ks-form-control validate[required]"  
			name="nwtitle" type="text" id="nwtitle" size="50" maxlength="255"
				value="<?php echo $objNews->getTitle ();?>" /> <font color="#FF0000">*</font></td>
		</tr>
		<tr>
			<th align="right">Status :</th>
			<td><select name="nwstatus" id="nwstatus" class="form-control ks-form-control">
					<option value="1" selected>Active / Publish</option>
					<option value="0">Inactive / Don't Publish</option>
				</select>
			</td>
		</tr>
		<tr>
			<th align="right">Start Date :</th>
			<td><input class="form-control ks-form-control validate[required,custom[ksdate]]"  id="nwstartDate" type="text"
				value="<?php echo displayBasedJs ( $objNews->getStartDate () );?>"
				name="nwstartDate" size="12" maxlength="10"/></td>
		</tr>
		<tr>
			<th align="right">End Date :</th>
			<td><input class="form-control ks-form-control validate[custom[ksdate]]"  id="nwendDate" type="text"
				value="<?php echo displayBasedJs ( $objNews->getEndDate () );?>"
				name="nwendDate" size="12" maxlength="10" /></td>
		</tr>
		<tr>
			<th align="right">Visible to Public (without login) :</th>
			<td><label><input name="nwpublic" type="checkbox" id="nwpublic" value="1"
				<?php echo ($objNews->getPublic () == '1') ? "checked=\"checked\"" : ""?> /> Yes</label></td>
		</tr>
		<tr>
			<th align="right">Visible to Logged-in Users :</th>
			<td><label><input type="checkbox" name="nwprivate" value="1" id="nwprivate"
				<?php echo ($objNews->getPrivate () == '1') ? "checked=\"checked\"" : ""?> /> Yes</label></td>
		</tr>
		<tr>
			<th align="right">News :</th>
			<td><textarea class="form-control ks-form-control validate[required]"  name="nwDesc" id="nwDesc" cols="45"
				rows="8"><?php echo $objNews->getDesc ();?></textarea></td>
		</tr>
		<tr id="trsubmit">
			<td></td>
			<td align="left"><input name="btnSubmit" type="submit"
				class="btn btn-primary" id="btnSubmit" value="Save" /> or <a
				href="javascript:history.back();">Cancel <input type="hidden"
				name="nid" id="nid" value="<?php echo $nid?>" /> </a>
				<input type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
				<input type="hidden" name="ks_scriptname" value="<?php echo $ks_scriptname;?>" />
				</td>
		</tr>

	</tbody>
</table>
</form>
    </div>
<script>
$(document).ready(function(){
	$("#formModify").validationEngine();
		
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
			$("#nwstartDate").select();
			document.returnValue = false;						
       }else if( !(strSiap == '00/00/0000' || strSiap == '') && strMula > strSiap ) {
			alert("End Date must be greater than Start Date. Please enter again.");
			document.returnValue = false;			
		}else {
			document.returnValue = true;
		}
	}catch(e) {
		alert("JS Fatal Error in validateDate(): " + e.message);
	}
}
</script>
<?php
include_once '../footer.php';
?>