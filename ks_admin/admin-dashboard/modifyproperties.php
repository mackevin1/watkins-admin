<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$boxid = 0; 
if (isset ($_GET ['boxid'])) {
	$boxid = $_GET ['boxid'];
}

$actionp = '';
if (isset ($_GET ['actionp'])) {
	$actionp = KS_Filter::inputSanitize ( $_GET ['actionp'] );
}

$objDashboard = new KS_Dashboard ();
$objDashboard->setId ( $did );
if (! $objDashboard->exists ()) {
	header ( "Location: list.php?msg=notexist&did=$did" );
	exit ();
}
$objDashboard->select ();
$bColumns = unserialize ( $objDashboard->getPortlet () );

$typec = '';
if (isset ( $bColumns [''])) {
	$typec = $bColumns [''] [$boxid] ['type'];
}

$urlinex = '';
$contenturlin = '';
$contenturlex = '';
if ($typec == 'HTML') {
	$contenthtml = $bColumns [''] [$boxid] ['content'];
} else {
	$contenturl = $bColumns [''] [$boxid] ['content'];
	$newstrurl = str_replace ( "/", "\/", KSCONFIG_URL );
	$deliurl = "/^" . $newstrurl . "/";
	if (preg_match ( $deliurl, $contenturl )) {
		$urlinex = "internal";
		$arrurl = explode ( KSCONFIG_URL, $contenturl );
		$contenturlin = $arrurl [1];
	} else {
		$urlinex = "external";
		$contenturlex = $contenturl;
	}
}

?>

<form action="/ks_builtin/dashboard_handler.php" method="post"
	enctype="multipart/form-data" class="form-vertical">
	<div class="control-group">
		<label class="control-label" for="input01">Type of content :</label>
		<div class="controls">
			<select name="typec" id="typec">
				<option value="URL" <?php if($typec == 'URL'){?> selected="selected"
					<?php }?>>URL</option>
				<option value="HTML" <?php if($typec == 'HTML'){?>
					selected="selected" <?php }?>>HTML</option>
			</select>
		</div>
	</div>

	<div id="urlc" class="control-group" style="display: none">
		<label class="control-label" for="input01">Content :</label>
		<div class="controls">
			<input type="radio" name="url" value="internal"
				onclick="showUrl(this.value);"
				<?php echo ($urlinex == "internal") ? 'checked' : "";?>>Internal URL <input
				type="radio" name="url" value="external"
				onclick="showUrl(this.value);"
				<?php echo ($urlinex == "external") ? 'checked' : "";?>>External URL<br>
			<div id="divinternalurl"><?php echo KSCONFIG_URL;?><input type="text"
					name="urlinternal" id="urlinternal" size="50"
					value="<?php echo $contenturlin;?>">
			</div>
			<div id="divexternalurl">
				<input type="text" name="urlexternal" id="urlexternal" size="30"
					value="<?php echo $contenturlex?>">
			</div>
		</div>
	</div>
	<div id="htmlc" class="control-group" style="display: none">
		<label class="control-label" for="input01">Content :</label>
		<div class="controls">
			<textarea name="content_html" cols="30" rows="3" id="content_html"><?php echo $contenthtml;?></textarea>
		</div>
	</div>
</form>

<script>
$(document).ready(function() {
	showUrl('');
});

    function displayVals() {
      var singleValues = $("#typec").val();

	  if(singleValues == 'HTML'){
		  $("#urlc").hide();
		  	$("#htmlc").show();
	  }else{
		    $("#urlc").show();
		  	$("#htmlc").hide();
	  }
    }

    $("#typec").change(displayVals);
    displayVals();

function showUrl(type){
	if (type == "internal") {
 		$("#divinternalurl").show();
 		$("#divexternalurl").hide();
	}else if (type == "external"){
 		$("#divinternalurl").hide();
 		$("#divexternalurl").show();
	}else{
		<?php if($urlinex == "internal"){?>
 		$("#divinternalurl").show();
 		$("#divexternalurl").hide();
		<?php }else{?>
		$("#divinternalurl").hide();
 		$("#divexternalurl").show();
		<?php }?>
	}
}
</script>

