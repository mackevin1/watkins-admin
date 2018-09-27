<?php
//firstly select all distinct resourceid
$sql = $ks_db->select ()->distinct ()->from ( array ('r' => 'ks_acl_resource' ), 'res_id' )->order ( 'res_id' );
$stmt = $ks_db->query ( $sql );

$arrResourcesId = array ();
while ( true == ($row = $stmt->fetch ()) ) {
	$arrResourcesId [] = $row ['res_id'];
}

//now we put all access into array, let's group by roleid
$sql = $ks_db->select ()->from ( 'ks_acl_access' )->where ( 'acc_roleid=?', $roleId )->order ( 'acc_resid' )->order ( 'acc_privilegeid' );
$stmt = $ks_db->query ( $sql );

$arrAccess = array ();
while ( true == ($row = $stmt->fetch ()) ) {
	$arrAccess [$row ['acc_roleid']] [$row ['acc_resid']] [$row ['acc_privilegeid']] = $row ['acc_allow'];
}

?>
<script>
$(document).ready(function(){
	$("#textareaCode<?=$roleId;?>").hide();
	$("#divCode<?=$roleId;?>").hide();
	
	//only show copy2clipboard if ie
	$("#btnCopy2Clipboard").hide();
	if (jQuery.browser.msie) {
		$("#btnCopy2Clipboard").show();
	}
});

function generateCode(res, priv , formid) {

	var idAcl = 'allowed_' + formid + '_' + priv;
	var strAcl = "$objAcl = new KS_Acl ( );\n";
	strAcl += "$" + idAcl + " = $objAcl->isAllowed ( $usr_role, '" + res + "', '" + priv + "' );\n";
	strAcl += "if($" + idAcl + ") {\n";
	strAcl += "    //do something if allowed\n";
	strAcl += "} else {\n";
	strAcl += "    //do something else\n";
	strAcl += "}";

	$("#textareaCode<?=$roleId;?>").text(strAcl);
	$("#textareaCode<?=$roleId;?>").show();
	$("#divCode<?=$roleId;?>").dialog({ height: 360, width: 600, buttons: {
				"Close": function() {
					$( this ).dialog( "close" );
				}
			}
 });
}

function copytoClipBoard() {
	if (jQuery.browser.msie) {
		var sContents = $("#textareaCode<?=$roleId;?>").text();
		window.clipboardData.setData("Text", sContents);
	}
}

/**
* this function un/checks all children
*/
/*function handleChildren(parentId) {

	var $foundChildren = $('input[id^=chkAccess\\[' + parentId + '\\]]');

	var $parentChecked = $('input[id^=chkParent\\[' + parentId + '\\]]').attr('checked');

	$foundChildren.each(function() { 
		if($parentChecked) {
			$(this).attr('checked','checked');
		} else {
			$(this).removeAttr('checked');
		}	
	});

}**/

</script>

<p>List of Access Privileges found.</p>
<div class="btn-group pull-right">
<button class="btn btn-primary" onClick="location.href='roledisplay.php?roleId=<?=$roleId;?>&tabId=2';">Add Privilege</button>
<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
<span class="caret"></span></button>
<ul class="dropdown-menu">
	<li><a href="roledisplay.php?roleId=<?=$roleId;?>&tabId=2">Add Privilege</a></li>
</ul>
</div>
<br/>
<div id="listprivilege<?=$roleId;?>">
<form id="form2" name="form2" method="post" action="aclmodifyhandler.php">
  <input type="hidden" name="roleId" value="<?=$roleId;?>">
<table class="table table-bordered table-hover table-striped">
  <thead>
    <tr>
      <th >Resource</th>
      <th align="left">Privilege</th>
      <th align="left">Privilege Description</th>
      <th align="left">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
				foreach ( $arrResourcesId as $curResourceId ) :
					$checked = "";
					if ($arrAccess [$roleId] [$curResourceId] ['all'] == 1) {
						$checked = "checked";
					}
					?>
    <tr>
      <td colspan="4">
        <?=$curResourceId;?></td>
    </tr>
    <?php
					$sql = $ks_db->select ()->from ( 'ks_acl_resource' )->where ( 'res_id = ?', $curResourceId )->order ( 'res_id' )->order ( 'res_privilegeid' );
					$stmt = $ks_db->query ( $sql );
					while ( true == ($row = $stmt->fetch ()) ) :
						$checked = "";
						if ($arrAccess [$roleId] [$row ['res_id']] [$row ['res_privilegeid']] == 1) {
							$checked = "checked";
						}
						$passResource = explode("(", $curResourceId);
						$getIdResource = $passResource[1];
						$getIdResource = str_replace(")", "", $getIdResource);
						?>
    <tr>
      <td>&nbsp;</td>
      <td><label for="chkAccess[<?=$curResourceId;?>][<?=$row ['res_privilegeid']?>]" class="lead"><input
			name="chkAccess[<?=$curResourceId;?>][<?=$row ['res_privilegeid']?>]"
			type="checkbox"
			id="chkAccess[<?=$curResourceId;?>][<?=$row ['res_privilegeid']?>]"
			value="1" <?=$checked;?> />
        <?=$row ['res_privilegeid']?></label></td>
      <td><?=$row ['res_desc']?>&nbsp;</td>
      <td align="center" class="bottomThinBorder"><input type="button" name="button" id="button" value="Generate Code" onclick="generateCode('<?=$curResourceId;?>','<?=$row ['res_privilegeid']?>','<?=$getIdResource;?>');" class="btn btn-default"/></td></tr>
    <?php
					endwhile;
				endforeach;
				?>
    </tbody>
</table>
  <div align="center">
    <input type="submit" name="button2" id="button2"
			value="Save" class="btn btn-primary"/>
  </div>
</form>
</div>
<div id="divCode<?=$roleId;?>" title="ACL Generated Code">
  <p align="center">
    <textarea id="textareaCode<?=$roleId;?>" cols="60" rows="8" onfocus="this.select();" wrap="off"></textarea>
    <input type="button" name="btnCopy2Clipboard" id="btnCopy2Clipboard" value="Copy to Clipboard" onclick="copytoClipBoard();" class="btn" />
  </p>
</div>



