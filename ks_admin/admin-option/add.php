<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_tokenid = 'token_' . $ks_scriptname;
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime () );
$_SESSION [$ks_tokenid] = $ks_token;

$strOptionOption = "[";
$arrOption = KS_Option::getGroupList ();
if (count ( $arrOption ) > 0) {
	$curOption = new KS_Option ();
	foreach ( $arrOption as $curOption ) {
		$option_code = $curOption ['option_code'];
		$option_group = $curOption ['option_group'];
		if ($option_group != '') {
			$strOptionOption .= "{id: '" . $option_group . "', text: '" . $option_group . "'},";
		}
	}
}

$strOptionOption .= "]";
include_once '../header_bootstrap.php';

?>

<div class="container"><?php
include_once '../navbar_top.php';

include_once 'breadcrumb.php';
?>

<p>Use this form to add a Option.</p>
	<form action="add_handler.php" method="post" name="formAdd"
		id="formAdd">
		<table class="table table-bordered table-hover table-striped">
			<tbody>
				<tr>
					<th width="30%" align="right">Group :</th>
					<td><input type='hidden' id='option_group' name='option_group'
						style='width: 300px' /></td>
				</tr>
				<tr>
					<th align="right">Code :</th>
					<td><input
						class="form-control ks-form-control validate[required,length[0,255]]"
						type="text" name="option_code" id="option_code" size="50">
						<div id="divIdnoAvailable" class="font-error">
							<p class="text-danger">Code already exists.Please insert new
								code.</p>
						</div></td>
				</tr>
				<tr>
					<th align="right">Description:</th>
					<td><textarea id="option_desc" name="option_desc" cols="50"
							rows="4" class="form-control ks-form-control"></textarea></td>
				</tr>
				<tr>
					<th align="right">Value:</th>
					<td><textarea id="option_value" name="option_value" cols="50"
							rows="4" class="form-control ks-form-control"></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input name="btnSubmit" type="submit" class="btn btn-primary"
						id="btnSubmit" value="Save" /> or <a href="list.php">Cancel</a>
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
	$("#divIdnoAvailable").hide();
	$("#divIdAvailable").hide();

	var hd = <?php echo $strOptionOption;?>;
	//alert(hd);
	$("#option_group").select2({
		createSearchChoice:function(term, data) 
		{ 
			if ($(data).filter(function() { return this.text.localeCompare(term)===0; }).length===0) {return {id:term, text:term};} },
			multiple: false,
			data: hd
	   }); 
});

$("#option_code").keyup(function() {
	handleExist();
});

$("#option_code").change(function() {
	handleExist();
});

function handleExist() {

	var option_code = $("#option_code").val();
	//alert(option_code);
	if(option_code != ''){
		$.post("checkcodeexist.php", {
			option_code: option_code}, function(data) {
			if(data == 1) {
				$("#divIdnoAvailable").show();
				$("#btnSubmit").attr('disabled','disabled');
				//$("#btnSubmit").removeClass('button1');

			} else {
				$("#divIdnoAvailable").hide();
				$("#btnSubmit").removeAttr('disabled');
				//$("#btnSubmit").addClass('button1');

			}			
	});
	}
}
</script>
<?php
include_once '../footer.php';
?>