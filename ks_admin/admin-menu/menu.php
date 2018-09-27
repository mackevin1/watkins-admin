<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$mid = ( int ) $_GET ['mid'];

$objMenuitem = new KS_Menuitem ( );
$objMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND (mi_parentid IS NULL OR mi_parentid='')" );
$objMenuitem->setSearchSortField ( "mi_order" );
$objMenuitem->setSearchSortOrder ( 'ASC' );
$objMenuitem->setSearchRecordsPerPage ( 1000 );
$arrMenuitem = $objMenuitem->search ();
$totMenuitem = count ( $arrMenuitem );

?>

<script>
$(document).ready(function() {

	$("ul.subnav").parent().append("<span></span>");
	 /*Only shows drop down trigger when js is enabled - Adds empty span tag after ul.subnav*/
	
	$("ul.topnav li span").click(function() {
		/* When trigger is clicked... */
		
		/* Following events are applied to the subnav itself (moving subnav up and down) */
		$(this).parent().find("ul.subnav").slideDown('fast').show(); 

		/* Drop down the subnav on click */
		$(this).parent().hover(function() {
		}, function(){	
			$(this).parent().find("ul.subnav").slideUp('slow');
			/* When the mouse hovers out of the subnav, move it back up */
		});

		/* Following events are applied to the trigger (Hover events for the trigger) */
		}).hover(function() { 
			$(this).addClass("subhover");
		}, function(){
			$(this).removeClass("subhover"); 
	});
});
</script>

<?php
if ($totMenuitem > 0) {
	echo "<div class=\"container\">
<ul class=\"topnav\">";
	$curMenuitem = new KS_Menuitem ( );
	foreach ( $arrMenuitem as $curMenuitem ) :
		
		$menuitem_id = $curMenuitem->getId ();
		
		//menuitem
		echo "<li><a href=\"" . $curMenuitem->getUrl () . "\">" . $curMenuitem->getLabel () . "</a>";
		
		$totSubMenuitem = 0;
		$objSubMenuitem = new KS_Menuitem ( );
		$objSubMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND mi_parentid='$menuitem_id' " );
		$objSubMenuitem->setSearchSortField ( "mi_order" );
		$objSubMenuitem->setSearchSortOrder ( 'ASC' );
		$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
		$arrSubMenuitem = $objSubMenuitem->search ();
		$totSubMenuitem = count ( $arrSubMenuitem );
		
		//sub-menuitem
		if ($totSubMenuitem > 0) {
			echo "<ul class=\"subnav\">";
			$curSubMenuitem = new KS_Menuitem ( );
			foreach ( $arrSubMenuitem as $curSubMenuitem ) :
				echo "<li><a href=\"" . $curSubMenuitem->getUrl () . "\">" . $curSubMenuitem->getLabel () . "</a></li>";
			endforeach;
			echo "</ul></li>";
		} else {
			echo "</li>";
		}
	
	endforeach;
	echo "</ul>
</div>";
}
?>

