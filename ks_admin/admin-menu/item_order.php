<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = 1;
if (isset ($_GET ['mid'])) {
	$mid = ( int ) $_GET ['mid'];
}

$objMenuitem = new KS_Menuitem ();
$objMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid = 0" );
$objMenuitem->setSearchSortField ( "mi_order" );
$objMenuitem->setSearchSortOrder ( 'ASC' );
$objMenuitem->setSearchRecordsPerPage ( 1000 );
$arrMenuitem = $objMenuitem->search ();
$totMenuitem = count ( $arrMenuitem );

include_once '../header_bootstrap.php';

?>
<script>
$(document).ready(function(){

	$("#tblMenuItems tbody").sortable({
		helper: fixHelper
	}).disableSelection();
});	

var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};

function SelectItem (curObj)
{
	$(curObj).attr('bgColor', '#DDDDDD');
}

function doChangeMenu (j,cb){
	newVal = 0;
	var valj = $("#arrsubmenu_"+j).val();
	
	if(cb==1){
			newVal = parseInt(valj)-1;
	}else{
		newVal = parseInt(valj)+1;
	}
	$("#arrsubmenu_"+j).val(newVal);
}

</script>

<div class="media">
<div class="media-body">Drag row to reorder Menu Item.</div>
</div>

<form action="item_order_handler.php" method="post" name="formModify"
	id="formModify">
<table id="tblMenuItems" class="table table-bordered table-hover table-striped">
	<thead>
		<tr align="center">
			<td width="2%" colspan="4">#.</td>
			<td colspan="2">Label</td>
			<td width="40%">URL</td>
			<td width="23%">Action</td>
		</tr>
	</thead>
	<tbody>
	<?php
	$counter = $j = 0;
	$curMenuitem = new KS_Menuitem ();
	foreach ( $arrMenuitem as $curMenuitem ) :

	$menuitem_id = $curMenuitem->getId ();
	$j = $curMenuitem->getOrder()-1;

	$totSubMenuitem = $counterSub = 0;
	$objSubMenuitem = new KS_Menuitem ();
	$objSubMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$menuitem_id' " );
	$objSubMenuitem->setSearchSortField ( "mi_order" );
	$objSubMenuitem->setSearchSortOrder ( 'ASC' );
	$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
	$arrSubMenuitem = $objSubMenuitem->search ();
	$totSubMenuitem = count ( $arrSubMenuitem );
	?>
		<tr style="cursor: pointer;" bgcolor="#DDDDDD"
			onclick="SelectItem(this);">
			<td align="center"><?php echo ++ $counter;?>.</td>
			<td colspan="5" align="center"><input type="hidden"
				id="arrmenuitem[]" name="arrmenuitem[]" value="<?php echo $menuitem_id?>"><input
				type="hidden" id="arrsubmenu_<?php echo $j;?>" name="arrsubmenu[]" value="0"><b><?php echo $curMenuitem->getLabel ();?></b></td>
			<td><?php echo $curMenuitem->getUrl ();?></td>
			<td nowrap><div class="radio"><label><input type="radio" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" value="2" name="cb_<?php echo $menuitem_id?>">
			change to sub menu</label></div></td>
		</tr>
		<?php

		if ($totSubMenuitem > 0) {
			$curSubMenuitem = new KS_Menuitem ();
			foreach ( $arrSubMenuitem as $curSubMenuitem ) :
			$submenuitem_id = $curSubMenuitem->getId ();
			$j = $curSubMenuitem->getOrder()-1;


			$totSubMenuitem1 = 0;
			$objSubMenuitem1 = new KS_Menuitem ();
			$objSubMenuitem1->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id' " );
			$objSubMenuitem1->setSearchSortField ( "mi_order" );
			$objSubMenuitem1->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem1->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem1 = $objSubMenuitem1->search ();
			$totSubMenuitem1 = count ( $arrSubMenuitem1 );
			?>
		<tr style="cursor: pointer;" bgcolor="#DDDDDD"
			onclick="SelectItem(this);">
			<td align="center">&nbsp;</td>
			<td align="center"><?php echo $counter;?>.<?php echo ++ $counterSub;?>.</td>
			<td colspan="4" align="center"><input type="hidden"
				id="arrmenuitem[]" name="arrmenuitem[]"
				value="<?php echo $submenuitem_id?>"> <input type="hidden"
				id="arrsubmenu_<?php echo $j;?>" name="arrsubmenu[]" value="1"><b><?php echo $curSubMenuitem->getLabel ();?></b></td>
			<td><?php echo $curSubMenuitem->getUrl ();?></td>
			<td nowrap><div class="radio"><label><input type="radio" value="1" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" name="cb_<?php echo $submenuitem_id?>">
			change to parent menu</label></div>
            <div class="radio"><label><input type="radio" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" value="2" name="cb_<?php echo $submenuitem_id?>">
			change to sub menu</label></div></td>
		</tr>

		<?php

		//submenu
		$counterItem1 = 0;
		if ($totSubMenuitem1 > 0) {
			$curSubMenuitem1 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem1 as $curSubMenuitem1 ) :
			$submenuitem_id1 = $curSubMenuitem1->getId ();
			$j = $curSubMenuitem1->getOrder()-1;

			$totSubMenuitem2 = 0;
			$objSubMenuitem2 = new KS_Menuitem ();
			$objSubMenuitem2->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id1' " );
			$objSubMenuitem2->setSearchSortField ( "mi_order" );
			$objSubMenuitem2->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem2->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem2 = $objSubMenuitem2->search ();
			$totSubMenuitem2 = count ( $arrSubMenuitem2 );
			?>
		<tr style="cursor: pointer;" bgcolor="#DDDDDD"
			onclick="SelectItem(this);">
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center"><?php echo $counter;?>.<?php echo $counterSub;?>.<?php echo ++ $counterItem1;?>.</td>
			<td colspan="3" align="center"><input type="hidden"
				id="arrmenuitem[]" name="arrmenuitem[]" value="<?php echo $submenuitem_id1;?>"><input
				type="hidden" id="arrsubmenu_<?php echo $j;?>" name="arrsubmenu[]" value="2"><b><?php echo $curSubMenuitem1->getLabel ();?></b></td>
			<td><?php echo $curSubMenuitem1->getUrl ();?></td>
			<td><div class="radio"><label><input type="radio" value="1" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" name="cb_<?php echo $submenuitem_id1?>">
			change to parent menu</label></div>
            <div class="radio"><label><input type="radio" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" value="2" name="cb_<?php echo $submenuitem_id1?>">
			change to sub menu</label></div></td>
		</tr>

		<?php

		//submenu
		$counterItem2 = 0;
		if ($totSubMenuitem2 > 0) {
			$curSubMenuitem2 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem2 as $curSubMenuitem2 ) :
			$submenuitem_id2 = $curSubMenuitem2->getId ();
			$j = $curSubMenuitem2->getOrder()-1;

			$totSubMenuitem3 = 0;
			$objSubMenuitem3 = new KS_Menuitem ();
			$objSubMenuitem3->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$submenuitem_id2' " );
			$objSubMenuitem3->setSearchSortField ( "mi_order" );
			$objSubMenuitem3->setSearchSortOrder ( 'ASC' );
			$objSubMenuitem3->setSearchRecordsPerPage ( 1000 );
			$arrSubMenuitem3 = $objSubMenuitem3->search ();
			$totSubMenuitem3 = count ( $arrSubMenuitem3 );
			?>
		<tr style="cursor: pointer;" bgcolor="#DDDDDD"
			onclick="SelectItem(this);">
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center"><?php echo $counter;?>.<?php echo $counterSub;?>.<?php echo $counterItem1;?>.<?php echo ++ $counterItem2;?>.</td>
			<td colspan="2" align="center"><input type="hidden"
				id="arrmenuitem[]" name="arrmenuitem[]" value="<?php echo $submenuitem_id2?>"><input
				type="hidden" id="arrsubmenu_<?php echo $j;?>" name="arrsubmenu[]" value="3"><b><?php echo $curSubMenuitem2->getLabel ();?></b></td>
			<td><?php echo $curSubMenuitem2->getUrl ();?></td>
			<td><div class="radio"><label><input type="radio" value="1" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" name="cb_<?php echo $submenuitem_id2?>">
			change to parent menu</label></div>
            <div class="radio"><label><input type="radio" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" value="2" name="cb_<?php echo $submenuitem_id2?>">
			change to sub menu</label></div></td>
		</tr>

		<?php

		//submenu
		$counterItem3 = 0;
		if ($totSubMenuitem3 > 0) {
			$curSubMenuitem3 = new KS_Menuitem ();
			foreach ( $arrSubMenuitem3 as $curSubMenuitem3 ) :
			$submenuitem_id3 = $curSubMenuitem3->getId ();
			$j = $curSubMenuitem3->getOrder()-1;
			?>
		<tr style="cursor: pointer;" bgcolor="#DDDDDD"
			onclick="SelectItem(this);">
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center" width="3%"><?php echo $counter;?>.<?php echo $counterSub;?>.<?php echo $counterItem1;?>.<?php echo $counterItem2;?>.<?php echo ++$counterItem3;?>.</td>
			<td width="32%"><input type="hidden" id="arrmenuitem[]"
				name="arrmenuitem[]" value="<?php echo $submenuitem_id3;?>"><input
				type="hidden" id="arrsubmenu_<?php echo $j;?>" name="arrsubmenu[]" value="4"><b><?php echo $curSubMenuitem3->getLabel ();?></b></td>
			<td><?php echo $curSubMenuitem3->getUrl ();?></td>
			<td><div class="radio"><label><input type="radio" value="1" onClick="doChangeMenu('<?php echo $j;?>',$(this).val());" name="cb_<?php echo $submenuitem_id3?>">
			change to parent menu</label></div></td>
		</tr>
		<?php
		endforeach
		;
		}

		endforeach
		;
		}
		endforeach
		;
		}
		endforeach
		;
		}
		endforeach
		;
		?>
	</tbody>
</table>
<p align="center"><input type="hidden" name="menu_id"
	value="<?php echo $mid;?>"><input type="submit" value="Save"
	class="btn btn-primary"> or <a href="display.php?mid=<?php echo $mid?>">Cancel</a></p>
</form>
