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

$objDashboard = new KS_Dashboard ();
$objDashboard->setSearchRecordsPerPage ( 1000 );
$objDashboard->setSearchSortField('dsh_id');
$objDashboard->setSearchSortOrder('DESC');
$arrDashboard = $objDashboard->search ();
$totDashboard = count ( $arrDashboard );

include_once '../header_bootstrap.php';

?>
<script type="text/javascript" src="../ks_scripts/html5slider.js"></script>
<div class="media">
	<div class="media-body">Create new Dashboard.</div>
</div>

<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox) ? '' : 'none';?>">
	<div class="message_box_content" align="center">
                   <?php echo $msg_desc;?>
               </div>
</div>

<form action="add_handler.php" method="post" name="formAdd" id="formAdd">
	<table class="table table-bordered table-hover table-striped">
		<tbody>
			<tr>
				<th width="30%" align="right">Title :</th>
				<td><input type="text" name="name" id="name" size="50"
					class="form-control ks-form-control validate[required,length[0,255]]"
					value="Dashboard <?php echo $totDashboard + 1;?>"></td>
			</tr>
			<tr>
				<th width="30%" align="right">Description :</th>
				<td><textarea class="form-control ks-form-control" name="desc"
						cols="50" id="desc"></textarea></td>
			</tr>
			<tr>
				<th width="30%" align="right">Number of Columns :</th>
				<td><label class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<input
						type="range" name="columns" id="columns" min="1" max="6" /></label>
					<input id="txt_columns" name="txt_columns" tabindex="0" size="1"
					maxlength="1" class="form-control ks-form-control"></td>
			</tr>
			<tr style="display: none">
				<th width="30%" align="right">Number of Portlets :</th>
				<td><input type="text" name="portlets" id="portlets" size="10" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="btnSubmit" type="submit" class="btn btn-primary"
					id="btnSubmit" value="Save" /> or <a href="list.php">Cancel</a> <input
					type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
					<input type="hidden" name="ks_scriptname"
					value="<?php echo $ks_scriptname;?>" /></td>
			</tr>
		</tbody>
	</table>
</form>
<script>
$(document).ready(function(){
 	$("#name").focus();
	$("#formAdd").validationEngine();
							   
	$("#columns").val(2);
	$("#txt_columns").val(2);
});

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

