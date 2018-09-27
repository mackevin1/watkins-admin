<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$nid = 0; 
if (isset ($_GET ['nid'])) {
	$nid = ( int ) $_GET ['nid'];
}

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$objNews = new KS_News ();
$objNews->setId ( $nid );
if (! $objNews->exists ()) {
	echo "The news with id ($nid) does not exist.";
	exit ();
}
$objNews->select ();

$endDate = ($objNews->getEndDate () == '0000-00-00') ? '-' : displayBasedJs ( $objNews->getEndDate () );

function displayBasedJs($date) {
	$arrDate = explode ( "-", $date );
	$first = $arrDate [0];
	$second = $arrDate [1];
	$third = $arrDate [2];
	$newdate = $third . "/" . $second . "/" . $first;
	
	return $newdate;
}

include_once '../header_bootstrap.php';

?>

<script>
$(document).ready(function(){
	$("#formAdd").validationEngine();
	$("#usr_id").select();
});	
</script>

<div class="container">
	<?php
	include_once '../navbar_top.php';
	
	include_once 'breadcrumb.php';
	?>
        
<p>
		Property page for <strong><?php echo $objNews->getTitle ();?></strong>.
	</p>

	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">

			<li class="<?php echo ($tabId==0)?'active':'';?>"><a href="#section0"
				data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i>
					Properties</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">


				<table class="table table-bordered table-hover table-striped">
					<tbody>
					
					
					<tbody>
						<tr>
							<th width="25%" align="right">Title :</th>
							<td width="75%"><?php echo $objNews->getTitle ();?></td>
						</tr>
						<tr>
							<th align="right">Status :</th>
							<td><?php if(($objNews->getStatus()) == 1) { $statusnews = "Active / Publish"; } else { $statusnews = "Inactive / Don't Publish"; } ?>
			    <?php echo $statusnews;?>
			</td>
						</tr>
						<tr>
							<th align="right">Start Date :</th>
							<td><?php echo displayBasedJs ( $objNews->getStartDate () );?></td>
						</tr>
						<tr>
							<th align="right">End Date :</th>
							<td><?php echo displayBasedJs ( $objNews->getEndDate () );?></td>
						</tr>
						<tr>
							<th align="right">Visible to Public (without login) :</th>
							<td><?php if(($objNews->getPublic()) == 1) { $publicnews = "Yes"; } else { $publicnews = "No"; } ?>
				<?php echo $publicnews;?></td>
						</tr>
						<tr>
							<th align="right">Visible to Logged-in Users :</th>
							<td><?php if(($objNews->getPrivate()) == 1) { $privatenews = "Yes"; } else { $privatenews = "No"; } ?>
				<?php echo $privatenews?></td>
						</tr>
						<tr>
							<th align="right">News :</th>
							<td><?php echo nl2br( $objNews->getDesc () );?></td>
						</tr>
						<!-- tr>
			<th align="right">Receiver :</th>
			<td><select name="nwreceiver" id="nwreceiver"
				onchange="showHideButton();">
				<option value="">-</option>
			  <?php
					
					$objUser = new CUSTOM_User ();
					$objUser->setSearchRecordsPerPage ( 10000 );
					$arrUsers = $objUser->search ();
					
					foreach ( $arrUsers as $curUsers ) {
						?>
			    <?php echo $curUsers->getName ();?>
			  <?php
					}
					?>
	      </select></td>
		</tr-->
						<tr id="trsubmit">
							<td></td>
							<td align="left"><input type="button" id="btnModify"
								name="btnModify" value="Modify"
								onclick="location.href='modify.php?nid=<?php echo $nid;?>'"
								class="btn btn-primary" /> or <a
								href="javascript:history.back();">Cancel <input type="hidden"
									name="nid" id="nid" value="<?php echo $nid?>" />
							</a></td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

	</div>

</div>
<?php
include_once '../footer.php';
?>