<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime () );
$_SESSION [$ks_tokenid] = $ks_token;

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBoxType = 'error';
switch ($msg) {
	default :
		$msg_desc = '';
		$showMessageBox = 0;
		break;
}

include_once '../header_bootstrap.php';

?>
<p>Use this form to add a new news.</p>

<form name="formAdd" method="post" id="formAdd" action="addhandler.php" onSubmit="validateDate();return document.returnValue">
 <table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
		  <th align="right">Title :</th>
		  <td>
			<input class="form-control ks-form-control validate[required]"  
			name="nwtitle" type="text" id="nwtitle" size="50"
				maxlength="255" /> <font color="#FF0000">*</font></td>
		  </tr>
		<tr>
			<th align="right">Status :</th>
			<td><select name="nwstatus" id="nwstatus" class="form-control ks-form-control">
				<option value="1" selected>Active / Publish</option>
				<option value="0">Inactive / Don't Publish</option>
			</select></td>
		</tr>
		<tr>
			<th align="right">Start Date :</th>
			<td>
			<input class="form-control ks-form-control validate[required,custom[ksdate]]"  id="nwstartDate" type="text" value="<?php echo date("d/m/Y");?>" name="nwstartDate"
				size="12" maxlength="10"/> <font color="#FF0000">*</font> </td>
		</tr>
		<tr>
			<th align="right">End Date :</th>
			<td>
			<input class="form-control ks-form-control validate[custom[ksdate]]"  id="nwendDate" type="text" value="" name="nwendDate"
				size="12" maxlength="10"/> *Leave blank for no end
			date (publish forever)</td>
		</tr>
		<tr>
			<th align="right">Visible to Public (without login) :</th>
			<td><label><input type="checkbox" name="nwpublic" value="1" id="nwpublic" /> Yes</label></td>
		</tr>
		<tr>
			<th align="right">Visible to Logged-in Users :</th>
			<td><label><input type="checkbox" name="nwprivate" value="1" id="nwprivate"
				checked /> Yes</label></td>
		</tr>
		<tr>
			<th width="30%" align="right">News :</th>
			<td width="70%"><textarea class="form-control ks-form-control validate[required]"  
			name="nwDesc" id="nwDesc" cols="45"
				rows="8"></textarea></td>
		</tr>
		<!--tr>
			<th align="right">Receiver :</th>
			<td><select name="nwreceiver" id="nwreceiver" onchange="showHideButton();">
			  <option value="">-</option>
			  <?php
					
					$objUser = new CUSTOM_User ();
					$objUser->setSearchRecordsPerPage ( 10000 );
					$arrUsers = $objUser->search ();
					
					foreach ( $arrUsers as $curUsers ) {
						?>
			  <option value="<?php echo $curUsers->getId ();?>">
			    <?php echo $curUsers->getName ();?>
		      </option>
			  <?php
					}
					?>
		    </select></td>
		</tr-->
		<tr id="trsubmit">
			<td></td>
			<td align="left"><input name="btnSubmit" type="submit"
				class="btn btn-primary" id="btnSubmit" value="Save" /> or <a
				href="list.php">Cancel</a>
				<input type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
				<input type="hidden" name="ks_scriptname" value="<?php echo $ks_scriptname;?>" />
				</td>
		</tr>

	</tbody>
</table>
</form>

<script>
$(document).ready(function() {
						   
		$("#formAdd").validationEngine();
		$("#nwtitle").select();

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
