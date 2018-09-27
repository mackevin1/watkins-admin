<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

//list Roles from ks_acl_role
$sqlRole = "SELECT * FROM ks_acl_role ORDER BY role_id ASC";
$stmtRole = $ks_db->query($sqlRole);

while (true == ($rowRole = $stmtRole->fetch())) {
	$arrRole [] = $rowRole['role_id'];
}

$did = (int)$_GET ['did'];

$objDashboard = new KS_Dashboard();
$objDashboard->setId ( $did );
$objDashboard->select ();

//get menuitem linked to this view
$defaultform = "ks_builtin/dashboard.php?did=$did";

$objMenuItem = new KS_Menuitem ();
$objMenuItem->setSearchSqlWhere ( " mi_url='$defaultform'" );
$arrMenuItem = $objMenuItem->search ();
$totMenuItem = count ( $arrMenuItem );

//get menu preview 
$strWhereMenu = '';
if ($totMenuItem > 0) {
	
	$strWhereMenu = " menu_id NOT IN (";
	
	//get menuid
	$arrMenu = array ();
	$curMenuItem = new KS_Menuitem ();
	foreach ( $arrMenuItem as $curMenuItem ) {
		$strWhereMenu .= $curMenuItem->getMenuid () . ",";
	}
	
	$strWhereMenu = preg_replace ( '/,$/', ')', $strWhereMenu );

}

//get all menu
$objMenu = new KS_Menu ();
$objMenu->setSearchSqlWhere ( $strWhereMenu );
$arrMenu = $objMenu->search ();

$strOptionMenu = '';
if (count ( $arrMenu ) > 0) {
	$curMenu = new KS_Menu ();
	foreach ( $arrMenu as $curMenu ) {
		$mid = $curMenu->getId ();
		$menu_name = $curMenu->getName ();
		
		$strOptionMenu .= "<option value=\"$mid\">$menu_name</option>";
	}
}

include_once '../header_bootstrap.php';

?>

<script>
$(document).ready(function(){
	$("#formAdd").validationEngine();
	refreshmenuitem();
});	

function showDiv(id){

	if (id == "parent") {
		$("#divParent").hide();
	} else {
		$("#divParent").show();
	}
}

function refreshmenuitem(){
	var mid = $("#menu_id").val();
	var url = "refreshmenuitem.php?mid=" + mid;
	$("#divMenuitem").load(url);
}

function validate(){

	var urlinternal = $("#url").val();
	if(urlinternal.length < 1){
		//alert('Please insert URL.');
	}else{
		$.ajax({
		    url:'<?php echo KSCONFIG_URL;?>' + urlinternal,
		    type:'HEAD',
		    error: function()
		    {
		        //file not exists
		        $("#urlinternal").focus();
		        alert('File does not exist.');
		    },
		    success: function()
		    {
		        //file exists
		    	$("#formAdd").submit();
		    }
		});
	}	
}
</script>
<div class="container">
<?php
include_once '../navbar_top.php';

include_once 'breadcrumb.php';
?>
<p>Use this form to create a new menu linked to this dashboard.</p>
<form action="menu_add_handler.php" method="post" name="formAdd"
	id="formAdd" enctype='multipart/form-data'>
<table class="table table-bordered table-hover table-striped">
	<tbody>
		<tr>
			<th align="right" valign="top">Menu :</th>
			<td><select class="form-control ks-form-control" id="menu_id" 
			name="menu_id" onchange="refreshmenuitem();"><?php echo $strOptionMenu;?></select></td>
		</tr>
		<tr>
			<th align="right" valign="top">Add as :</th>
			<td><div class="radio"><label><input type="radio" id="menutype1" name="menutype"
				value="parent" checked="checked" onclick="showDiv(this.value);">Parent
			Menu</label></div><b>or</b><br/> <div class="radio"><label><input type="radio" id="menutype2" name="menutype"
				value="sub" onclick="showDiv(this.value);"> Sub Menu</label></div>
			<div id="divParent" style="display: none;">
			<div id="divMenuitem"></div>
			</div>
			</td>
		</tr>
		<tr>
			<th width="30%" align="right" valign="top">Label :</th>
			<td><input
			 type="text" name="label" id="label" size="50"
				class="form-control ks-form-control validate[required,length[0,255]]"
				value="<?php echo $objDashboard->getTitle ();?>">
			</td>
		</tr>
		<tr>
			<th align="right" valign="top">URL :</th>
			<td><label class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo KSCONFIG_URL;?></label>
			<input type="text" name="url" id="url" size="50"
				class="form-control ks-form-control validate[required,length[0,255]]"
				value="ks_builtin/dashboard.php?did=<?php echo $did;?>"></td>
		</tr>
		<tr>
			<th align="right" valign="top">Tooltip :</th>
			<td>
			<input class="form-control ks-form-control" type="text" name="tooltip" id="tooltip" size="50">
			</td>
		</tr>
		<tr>
			<th align="right" valign="top" rowspan="<?php echo count($arrRole);?>">Access Control :</th>
			<?php
			if (count ( $arrRole ) > 0) {
				foreach ( $arrRole as $curRole ) { ?>
					<td><?php echo "<input type=\"checkbox\" id=\"role_" . $curRole . "\" name=\"role_" . $curRole . "\" value=\"" . $curRole . "\" checked> " . $curRole . "</div>";
					?>
					</td>
		</tr>
					<?php
				}
			}
			?>
		<tr>
			<th align="right" valign="top">Not Login Access :</th>
			<td><label><input type="checkbox" name="notlogin" value="1"> Yes , user no need login to access</label></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="dashboard_id" type="hidden" id="dashboard_id"
				value="<?php echo $did;?>" /><input name="btnSubmit" type="button" 
				class="btn btn-primary" id="btnSubmit" value="Save" onclick="validate();" />
			or <a href="display.php?did=<?php echo $did;?>&tabId=3">Cancel</a></td>
		</tr>
	</tbody>
</table>
</form>
