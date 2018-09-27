<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0;
if (isset ( $_GET ['tabId'] )) {
	$tabId = ( int ) $_GET ['tabId'];
}

$ocode = '';
if (isset ( $_GET ['ocode'] )) {
	$ocode = KS_Filter::inputSanitize ( $_GET ['ocode'] );
	$ocode = preg_replace ( "/[^a-zA-Z0-9_]+/", "", $ocode );
}

if (! $ocode) {
	header ( "Location: list.php?msg=code_notfound&code=$ocode" );
	exit ();
}

$objOption = new KS_Option ();
$objOption->setCode($ocode);
if(!$objOption->exists()) {
	header("Location: list.php?msg=code_notfound&code=$ocode");
	exit;
}
$objOption->select();

$setting_readonly = $objOption->getReadonly();
if($setting_readonly == 1){
	$isreadonly = "disabled=\"disabled\"";
}else{
	$isreadonly = "";
}

$strOptionOption = "[";
$arrOption = KS_Option::getGroupList();
if (count ( $arrOption ) > 0) {
	$curOption = new KS_Option ();
	foreach ( $arrOption as $curOption ) {
		$option_code = $curOption['option_code'];
		$option_group = $curOption['option_group'];
		if($option_group != ''){
			//$strOptionOption .= "<option value=\"$option_group\">$option_group</option>";
			$strOptionOption .= "{id: '".$option_group."', text: '".$option_group."'},";
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

<p>
		Property page for option <strong><?php echo $ocode;?></strong>.
	</p>

	<form action="add_handler.php" method="post" name="formAdd"
		id="formAdd">
		<table class="table table-bordered table-hover table-striped">
			<tbody>
				<tr>
					<th width="30%" align="right">Group :</th>
					<td><?php echo $objOption->getGroup();?></td>
				</tr>
				<tr>
					<th align="right">Code :</th>
					<td><?php echo $ocode;?></td>
				</tr>
				<tr>
					<th align="right">Description:</th>
					<td><?php echo $objOption->getDesc();?></td>
				</tr>
				<tr>
					<th align="right">Value:</th>
					<td><?php echo $objOption->getValue();?></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="button" name="Button2" value="Modify"
						onclick="location.href='modify.php?ocode=<?php echo $ocode;?>'"
						class="btn btn-primary"> <input type="button" id="btnGenerate"
						value="Source Code" onclick="generateCode('<?php echo $ocode;?>');"
						class="btn btn-default"> <input type="button"
						onclick="deleleOption('<?php echo $ocode;?>','<?php echo $tabId;?>');"
						value="Delete" class="btn btn-danger" <?php echo $isreadonly;?>></td>
				</tr>
			</tbody>
		</table>
	</form>

	<div id="divCode" title="Generated Code">
		<textarea id="textareaCode" cols="50" rows="4"
			onfocus="this.select();" wrap="off" style="display: none;"
			class="form-control ks-form-control"></textarea>
		<p align="left">
			<small>To use this value, copy and paste this code into a PHP file.</small>
		</p>
	</div>

	<script>
$(document).ready(function(){
	var hd = <?php echo $strOptionOption;?>;
	//alert(hd);
	$("#option_group").select2({
		createSearchChoice:function(term, data) 
		{ 
			if ($(data).filter(function() { return this.text.localeCompare(term)===0; }).length===0) {return {id:term, text:term};} },
			multiple: false,
			data: hd
	   }); 
	   
	$("#btnCopy2Clipboard").hide();
	if (jQuery.browser.msie) {
		$("#btnCopy2Clipboard").show();
	}

	$("#divCode").dialog({ 
		autoOpen: false,
		width: 600, 
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function deleleOption(ocode,tabId){
	if (confirm('Are you sure to delete this option?')){
		$.post("delete_handler.php", {
			ocode : ocode,
			tabId : tabId }, function(data) {
				window.location.href = 'list.php?msg=deleted&tabId='+ tabId ;
		});
	}
}

function generateCode(id) {
	var strA = "KS_Option::getOptionValue('"+id+"');\n";

	$("#textareaCode").text(strA);
	$("#textareaCode").show();
	$("#divCode").dialog("open");
}

</script>
</div>
<?php
include_once '../footer.php';
?>