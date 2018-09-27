<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$today = date ( "Y-m-d" );
$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$name = ''; 
if (isset ($_GET ['name'])) {
	$name = KS_Filter::inputSanitize ( $_GET ['name'] );
}

$desc = ''; 
if (isset ($_GET ['desc'])) {
	$desc = KS_Filter::inputSanitize ( $_GET ['desc'] );
}

$noCols = 0;
if (isset ($_GET ['noCols'])) {
	$noCols = ( int ) $_GET ['noCols'];
}

$noPortlets = 0;
if (isset ($_GET ['portlets'])) {
	$noPortlets = ( int ) $_GET ['portlets'];
}

if ($noCols) {
	$eachCols = ( int ) ($noPortlets / $noCols);
	$balancePortlets = ( int ) ($noPortlets % $noCols);
}

$newPortletId = 0;
if (isset ($_GET ['newPortletId'])) {
	$newPortletId = ( int ) $_GET['newPortletId'];
}

$objDashboard = new KS_Dashboard ();
$objDashboard->setId ( $did );
if (! $objDashboard->exists ()) {
	header("Location: list.php?msg=notexist&did=$did");
	exit ();
}
$objDashboard->select ();
$bColumns = unserialize ( $objDashboard->getPortlet () );

$countCols = 0;
$countBoxs = 0;
if ($bColumns) {
	foreach ( $bColumns as $curbColumns => $box ) {
		if ($curbColumns != '') {
			$countCols = ++ $countCols;
			foreach ( $box as $curbBox ) {
				$countBoxs = ++ $countBoxs;
			}
		}
	}
}

$noCols = $countCols;
$noPortlets = $countBoxs;
$span = ( int ) (12 / $noCols);

$arrhidebox = array ();

include_once '../header_bootstrap.php';
?>
<input type="hidden" value="<?php echo KSCONFIG_URL?>" name="hiddenurl"
	id="hiddenurl" />
<input type="hidden" value="<?php echo $did;?>" name="did" id="did" />
<input type="hidden" value="<?php echo $newPortletId;?>"
	name="newPortletId" id="newPortletId" />
<input type="hidden" value="1" name="cp" id="cp" />
<div class="container-fluid" id="container-fluid">
	<div class="row" id="rowfluid"><?php

	foreach ( $bColumns as $curbColumns => $box ) {

		if ($curbColumns != '') {

			if ($noCols == 5) {
				if ($curbColumns == 'col1' || $curbColumns == 'col5') {
					$span = 3;
				} else {
					$span = ( int ) (12 / $noCols);
				}
			}

			?>
<div class="col-12 col-sm-<?php echo $span;?> col-lg-<?php echo $span;?> column ui-sortable" id="<?php echo $curbColumns?>">
		<?php
			foreach ( $box as $curbBox ) {

				$classboxheader = "box-header round-top";
				$classicon = "glyphicon glyphicon-minus";
				$classboxcontainer = "box-container-toggle";

				if ((count ( $arrhidebox )) > 0) {
					if (in_array ( $curbBox, $arrhidebox )) {

						$classboxheader = "box-header round-all";
						$classicon = "glyphicon glyphicon-plus";
						$classboxcontainer = "box-container-toggle box-container-closed";
					}
				}

				// onkeypress="this.style.width = ((this.value.length + 1) * 8)
				// + 'px';"
				?> <!-- Portlet: Box -->
			<div class="box" id="<?php echo $curbBox;?>">
				<!-- id=box-x is used for the position storing of the boxes -->
				<h4 class="<?php echo $classboxheader?>" align="left">
					<input class="span7" type="text" placeholder="<?php echo $curbBox;?>"
						style="background-color: #E9E9E9; border: none; box-shadow: none; color: #000; margin-bottom: 0px; font-size: 18px; height: 25px; color: inherit; font-weight: bold;"
						onchange="unsetStyle(this.id);setTitle($(this));"
						id="title<?php echo $curbBox?>" name="title<?php echo $curbBox?>"
						value="<?php echo $bColumns[''][$curbBox]['title'];?>"
						onfocus="setStyle(this.id);" onblur="unsetStyle(this.id);" /> <a
						class="box-btn" title="close" id="close<?php echo $curbBox;?>"><i
						class="glyphicon glyphicon-remove"></i></a>
					<!-- Can be removed if not wanted -->
					<a class="box-btn" title="toggle"><i class="<?php echo $classicon?>"></i></a>
					<!-- Can be removed if not wanted -->
					<a class="box-btn" title="config" data-toggle="modal"
						id="config<?php echo $curbBox;?>"><i class="glyphicon glyphicon-cog"></i></a>
					<!-- Can be removed if not wanted -->
				</h4>
				<div class="<?php echo $classboxcontainer;?>">
					<div class="box-content form-box">
				<?php

				$typec = $bColumns [''] [$curbBox] ['type'];

				if ($typec == 'URL') {
					echo $contentp = "<iframe frameborder=\"0\" seamless=\"seamless\" width=\"98%\" height=\"300\" src=\"" . $bColumns [''] [$curbBox] ['content'] . "\"></iframe>";
				} else if ($bColumns [''] [$curbBox] ['type'] == 'HTML') {
					echo $contentp = $bColumns [''] [$curbBox] ['content'];
				} else {
					echo $contentp = $bColumns [''] [$curbBox] ['content'];
				}
				?>
					</div>
				</div>
			</div>


			<!--/span--> <?php
			}
			?></div>
		<?php
		}
	}
	?></div>


	<div id="dialog-config-widget" style="display: none;" class="dialog">
		<div class="modal-body">
			<div class="divDialogElements" id="divDialogElements"
				name="divDialogElements"></div>
		</div>
	</div>
	</div>
<script>
function setStyle(x)
{
	$('#'+x).css({backgroundColor: 'white'});
	$('#'+x).css({border: 'inset grey 1px'});
}
function unsetStyle(x)
{
	$('#'+x).css({backgroundColor: '#E9E9E9'});
	$('#'+x).css({border: 'none'});
}
</script>