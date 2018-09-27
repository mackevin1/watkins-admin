<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

//form use token to avoid form hijacking / CSRF
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
$ks_token = md5 ( KSCONFIG_DB_NAME . microtime() );
$ks_tokenid = 'token_' .$ks_scriptname;
$_SESSION [$ks_tokenid] =  $ks_token;

$parentId = 1;
$mid = ( int ) $_GET ['mid'];

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$objMenu = new KS_Menu ();
$objMenu->setId ( $mid );
if(!$objMenu->exists()) {
	header("Location: list.php?e=menu_notfound&mid=$mid");
	exit;
}
$objMenu->select();

//unserialize input layout
$optionlayout = unserialize($objMenu->getOption());

$menulayout = $optionlayout['menuo_layout'];

$styleLYH = '';
$styleLYV = '';
if ($menulayout == '1') { // 1 = horizontal
	$styleLYH = "checked='checked'";
} else { // 2 = vertical
	$styleLYV = "checked='checked'";
}

include_once '../header_bootstrap.php';

?>

<div class="container"><?php
include_once '../navbar_top.php';

include_once 'breadcrumb.php';
?>

<p>Modify properties <strong><?php echo $objMenu->getName ();?></strong>.</p>

<form action="modify_handler.php" method="post" name="formModify"
	id="formModify">
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
			<th align="right">Name :</th>
			<td><input class="form-control ks-form-control validate[required,length[0,255]]" type="text" name="name" id="name" size="50"
				value="<?php echo $objMenu->getName ();?>"></td>
		</tr>
		<tr>
			<th align="right" valign="top">Layout :</th>
			<td><div class="radio"><label><input name="menuo_layout" type="radio" value="1"
			 <?php echo $styleLYH;?> /> Horizontal</label></div>
			 <div class="radio"><label><input name="menuo_layout"
				type="radio" value="2" <?php echo $styleLYV;?> /> Vertical</label></div></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="hidden" name="mid" id="mid" value="<?php echo $mid;?>"> <input
				name="btnSubmit" type="submit" class="btn btn-primary"
				id="btnSubmit" value="Save" /> or <a href="display.php?mid=<?php echo $mid;?>">Cancel</a>
				<input type="hidden" name="ks_token" id="ks_token" value="<?php echo $ks_token;?>" />
				<input type="hidden" name="ks_scriptname" value="<?php echo $ks_scriptname;?>" />
				</td>
		</tr>
	</tbody>
</table>
</form>

</div>
<?php
include_once '../footer.php';
?>
