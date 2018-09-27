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
<p>Create new Menu.</p>
				
        <div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>

				<form action="add_handler.php" method="post" name="formAdd"
					id="formAdd">
					<table class="table table-bordered table-hover table-striped">
						<tbody>
							<tr>
								<th width="30%" align="right">Name :</th>
								<td><input class="form-control ks-form-control validate[required,length[0,255]]"
								type="text" name="name" id="name" size="50" value="Untitled Menu"></td>
							</tr>
							<tr>
								<th align="right">Layout :</th>
								<td><div class="radio"><label>
								<input name="menuo_layout" type="radio" value="1" checked="checked" /> Horizontal</label></div>
								<div class="radio"><label>
								<input name="menuo_layout" type="radio" value="2" /> Vertical</label></div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input name="btnSubmit" type="submit"
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
$(document).ready(function(){
 	$("#name").focus();
	$("#formAdd").validationEngine();
});	
</script>