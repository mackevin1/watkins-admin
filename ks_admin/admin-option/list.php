<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0;
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$msg = '';
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$arrOption = KS_Option::getGroupList();

$showMessageBox = 0;
$showMessageBoxType = '';
$msg_desc = '';
switch ($msg) {
	case 'updated' :
		$msg_desc = "<b>Option has been updated.</b>";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'code_notfound' :
		$ocode = KS_Filter::inputSanitize( $_GET['ocode'] );
		$msg_desc = "<b>Code '$ocode' not found. Perhaps it has been deleted?</b>";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;
	
	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';

?>

	<div class="container">
	  <?php
	include_once '../navbar_top.php';
	?>
            
	<ul class="breadcrumb">
    	<li class="active"><i class="glyphicon glyphicon-check"></i> <?php echo $ks_translate->_('Option'); ?></li>
	</ul>
    
    <p>List of Option found. Click 'Source Code' to put it into your code.</p>
              
    <div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $msg_desc;?>
	</div>
			
 <div class="tabbable tabs-left">
	<ul class="nav nav-tabs">
    <?php
    if (count ( $arrOption ) > 0) {
	$curOption = new KS_Option ();
	foreach ( $arrOption as $curOption => $v) {
		$option_code = $v['option_code'];
		$option_group = $v['option_group'];
		 
		if($option_group != NULL){
							?>
       <li class="<?php echo ($tabId==$curOption)?'active':'';?>"><a href="#section<?php echo $curOption;?>"
     	data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i>   <?php echo $option_group;?></a></li>
                                    
  <?php
				}
	}
}
				?>
               </ul>
       <div class="tab-content">
                            <?php
                                if (count ( $arrOption ) > 0) {
	$curOption = new KS_Option ();
	foreach ( $arrOption as $curOption => $v) {
		$option_code = $v['option_code'];
		$option_group = $v['option_group'];
		if($option_group != ''){
							?>
    	<div class="tab-pane <?php echo ($tabId==$curOption)?'active':'';?>" id="section<?php echo $curOption;?>">
    	              <div class="btn-group pull-right">
                        <button class="btn btn-primary" onClick="location.href='add.php';">Add Option</button>
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a onClick="location.href='add.php';">Add Option</a></li>
                        </ul>
                        </div>
                        <br/><br/>
                       	<table class="table table-bordered table-hover table-striped">	
		<thead>
		<tr align="center">
			<th>#</th>
			<th class="text-center">Code</th>
			<th class="text-center">Description</th>
			<th class="text-center">Value</th>
			<th class="text-center">Action</th>
		</tr>
	</thead>
	<tbody>
     <?php 
     $counter = 0;
     $arrSetting = KS_Option::getOptionList($option_group);
		 if (count ( $arrSetting ) > 0) {
			$curSetting = new KS_Option ();
			foreach ( $arrSetting as $curSetting => $setting) {
				$setting_code = $setting['option_code'];
				$setting_value = $setting['option_value'];
				$setting_desc = $setting['option_desc'];
				$setting_readonly = $setting['option_readonly'];
				if($setting_readonly == 1){
				  $isreadonly = "disabled=\"disabled\"";
				}else{
					$isreadonly = "";
				}
				if($setting_code != ''){
					
			//$value = "<textarea name=\"{$setting_code}\" rows=\"2\" cols=\"30\" class=\"form-control ks-form-control\">{$setting_value}</textarea>";
		$value = $setting_value;
		?>
		<tr>
			<td><?php echo ++$counter;?>.</td>
			<td><?php echo $setting_code;?></td>
			<td><?php echo $setting_desc;?></td>
			<td><?php echo $value;?></td>
			<td align="center" nowrap="nowrap">
			<input type="button"
					class="btn btn-primary"
					onclick="location.href='display.php?ocode=<?php echo $setting_code;?>&tabId=<?php echo $curOption;?>';" value="Properties">
				<input type="button" id="btnGenerate" value="Source Code"
				onclick="generateCode('<?php echo $setting_code;?>');" class="btn btn-default"> 
					<input
					type="button" class="btn btn-danger"
					onclick="deleleOption('<?php echo $setting_code;?>','<?php echo $curOption;?>');"
					value="Delete" <?php echo $isreadonly;?>></td>
		</tr>
		<?php
						}
					}
				}
				?>
	</tbody>
</table>
    	 </div>
                                    
  <?php
						}
					}
				}
				?>
        </div>    
                
<div id="divCode" title="Generated Code">
<textarea id="textareaCode" cols="50" rows="4"
	onfocus="this.select();" wrap="off" style="display: none;" class="form-control ks-form-control"></textarea>
<p align="left"><small>To use this value, copy and paste this code into a PHP file.</small>
</p>
</div>

<script>
$(document).ready(function() {
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

function generateCode(id) {
	var strA = "KS_Option::getOptionValue('"+id+"');\n";

	$("#textareaCode").text(strA);
	$("#textareaCode").show();
	$("#divCode").dialog("open");
}

function deleleOption(ocode,tabId){
	if (confirm('Are you sure to delete this option?')){
		$.post("delete_handler.php", {
			ocode : ocode,
			tabId : tabId }, function(data) {
				window.location.href = 'list.php?msg=deleted&tabId='+ tabId ;
		});
	}
}
</script>
</div>
</div>
<?php
include_once '../footer.php';
?>