<?php
include_once '../../library.php';
include_once '../header_isadmin.php';

$nid = 0;
if (isset ( $_GET ['nid'] )) {
	$nid = ( int ) $_GET ['nid'];
}

$tabId = 0;
if (isset ( $_GET ['tabId'] )) {
	$tabId = ( int ) $_GET ['tabId'];
}

$parentId = KS_Cpmenu::MENU_NEWS;

$sql = "SELECT cpm_url FROM ks_controlpanel_menu WHERE cpm_id = '$parentId'";
$urlparent = $ks_db->fetchOne ( $sql );

$objMenuItem = new KS_Cpmenu ();
$objMenuItem->setSearchSQL ( "SELECT * FROM ks_controlpanel_menu WHERE cpm_parentid = '$parentId' AND cpm_status = 1 " );
$objMenuItem->setSearchSortOrder ( 'ASC' );
$objMenuItem->setSearchSortField ( 'cpm_order' );
$objMenuItem->setSearchRecordsPerPage ( 300 );
$arrMenuItem = $objMenuItem->search ();

$objNews = new KS_News ();
$objNews->setSearchRecordsPerPage ( 1000 );
$arrNews = $objNews->search ();
$totNews = count ( $arrNews );

$msg = '';
if (isset ( $_GET ['msg'] )) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBox = 0;
$showMessageBoxType = ''; 
$msg_desc = '';
switch ($msg) {
	case 'added' :
		$msg_desc = 'News has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	
	case 'updated' :
		$msg_desc = 'News has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	
	case 'deleted' :
		$msg_desc = 'News has been deleted.';
		$showMessageBoxType = 'success';
		$showMessageBox = 1;
		break;
	
	default :
		$msg_desc = '';
		$showMessageBox = 0;
		break;
}

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

function deleteMenu(id){
	if (confirm('Are you sure to delete this News?')){
		$.post("delete_handler.php", {
			nid : id }, function(data) {
				window.location.href = 'list.php?e=deleted';
		});		
	}
}

</script>
<div class="container"><?php
include_once '../navbar_top.php';
?>
<ul class="breadcrumb">
		<li class="active"><i class="glyphicon glyphicon-star"></i> <?php echo $ks_translate->_('News'); ?></li>
	</ul>

	<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php echo $msg_desc;?>
	</div>

	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<?php
			$s = 0;
			foreach ( $arrMenuItem as $curMenuItem ) {
				$urlChild = $curMenuItem->getUrl ();
				$menuid = $curMenuItem->getId ();
				$labelChild = $curMenuItem->getLabel ();
				
				?>
				<li class="<?php echo ($tabId==$s)?'active':'';?>"><a href="#section<?php echo $s++;?>"
							data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> <?php echo $labelChild;?></a></li>
					<?php
			}
			?>
		</ul>
		<div class="tab-content">
			<div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">

				<div class="media">
					<div class="media-body">List of News.</div>
				</div>

				<div class="btn-group pull-right">
					<button class="btn btn-primary"
						onClick="location.href='list.php?tabId=1';">Add News</button>
					<button class="btn btn-primary dropdown-toggle"
						data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="list.php?tabId=1">Add News</a></li>
					</ul>
				</div>
				<?php
				if ($totNews <= 0) {
					?>
					<br />
								<div class="alert alert-info" align="center">No News found. Click
									on 'Add News' button to add.</div>
				<?php } else{ ?>
				<br />
				<br />
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr align="center">
							<th width="5%">#.</th>
							<th width="20%">Title</th>
							<th width="25%">News</th>
							<th width="8%">Status</th>
							<th width="8%">Start Date</th>
							<th width="8%">End Date</th>
							<th width="8%">Public</th>
							<th width="8%">Login-Users</th>
							<th width="15%">Action</th>
						</tr>
					</thead>
					<tbody>
					   <?php
						$counter = 1;
						$curNews = new KS_News ();
						foreach ( $arrNews as $curNews ) {
							$endDate = ($curNews->getEndDate () == '0000-00-00') ? '-' : displayBasedJs ( $curNews->getEndDate () );
							?>
					  <tr valign="top">
							<td align="center"><?php echo $counter ++;?>.</td>
							<td width="20%" align="left"><strong><a
									href="display.php?nid=<?php echo $curNews->getId ();?>" class="lead"><?php echo $curNews->getTitle ();?></a></strong>&nbsp;</td>
							<td align="left"><?php echo nl2br( substr ($curNews->getDesc (), 0, 100) );?><?php echo (strlen($curNews->getDesc ()) > 100)?'...':'';?>&nbsp;</td>
							<td width="10%" align="center"><?php echo $curNews->getStatus()?'<span class="label label-success">Active / Publish</span>':'<span class="label label-danger">Disabled / Don\'t  Publish</span>';?>&nbsp;</td>
							<td align="center"><?php echo displayBasedJs($curNews->getStartDate ());?>&nbsp;</td>
							<td align="center"><?php echo $endDate;?>&nbsp;</td>
							<td align="center"><input name="nwpublic" type="checkbox"
								id="nwpublic" value="1"
								<?php echo ($curNews->getPublic() ==1)?'checked="checked"':''; ?>
								disabled="disabled" />&nbsp;</td>
							<td align="center"><input type="checkbox" name="nwprivate"
								value="1" id="nwprivate"
								<?php echo ($curNews->getPrivate() ==1)?'checked="checked"':''; ?>
								disabled="disabled" />&nbsp;</td>
							<td align="center" nowrap="nowrap"><?php if ($curNews->getStatus () != '2'){?><input
								type="button" value="Properties"
								onClick="location.href='display.php?nid=<?php echo $curNews->getId ();?>';"
								class="btn btn-primary"><?php }?> <input type="button"
								value="Delete" onClick="deleteMenu('<?php echo $curNews->getId ();?>');"
								class="btn btn-danger"></td>
						</tr>
				    <?php
					}
					?>
					</tbody>
				</table>
		<?php
		}
		?>
		</div>

		<div class="tab-pane <?php echo ($tabId==1)?'active':'';?>" id="section1">
			<?php include_once ('add.php'); ?>
		</div>
		</div>
	</div>
</div>
<?php
include_once '../footer.php';
?>