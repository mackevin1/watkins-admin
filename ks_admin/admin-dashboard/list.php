<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$msg = '';
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$parentId = KS_Cpmenu::MENU_DASHBOARD;

$sql = "SELECT cpm_url FROM ks_controlpanel_menu WHERE cpm_id = '$parentId'";
$urlparent = $ks_db->fetchOne ( $sql );
if (!$urlparent){
	$urlparent = 'admin-dashboard/';
}

$objMenuItem = new KS_Cpmenu ( );
$objMenuItem->setSearchSQL ( "SELECT * FROM ks_controlpanel_menu WHERE cpm_parentid = '$parentId' AND cpm_status = 1 " );
$objMenuItem->setSearchSortOrder ( 'ASC' );
$objMenuItem->setSearchSortField ( 'cpm_order' );
$objMenuItem->setSearchRecordsPerPage ( 300 );
$arrMenuItem = $objMenuItem->search ();

$objDashboard = new KS_Dashboard ();
$objDashboard->setSearchRecordsPerPage ( 1000 );
$objDashboard->setSearchSortField('dsh_id');
$objDashboard->setSearchSortOrder('DESC');
$arrDashboard = $objDashboard->search ();
$totDashboard = count ( $arrDashboard );

$disabledelete = '';
if($totDashboard == 1){//makesure at least one data exist
	$disabledelete = "disabled=\"disabled\"";
}

$showMessageBox = 0;
$showMessageBoxType = ''; 
$msg_desc = '';
switch ($msg) {
	case 'added' :
		$msg_desc = 'Dashboard has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'updated' :
		$msg_desc = 'Dashboard has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'deleted' :
		$msg_desc = 'Dashboard has been deleted.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'notexist' :
		$msg_desc = "Dashboard with ID ($did) is not found. Perhaps it has been deleted?";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;

	case 'add_failed' :
		$msg_desc = "Dashboard insert failed, most likely Database error. Please check error log " . KSCONFIG_ERROR_LOG;
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;

	default :
		$msg_desc = '';
		$showMessageBox = 0;
		break;
}


include_once '../header_bootstrap.php';

?>
	<div class="container">
	  <?php
	include_once '../navbar_top.php';
	?>
              <ul class="breadcrumb">
                <li class="active"><i class="glyphicon glyphicon-dashboard"></i> <?php echo $ks_translate->_('Dashboard'); ?></li>
              </ul>
         
         <div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>
                    <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                        	<?php
								$s = 0;
								$i = 0;
								foreach ( $arrMenuItem as $curMenuItem ) {
									$urlChild = $curMenuItem->getUrl ();
									$menuid = $curMenuItem->getId ();
									$labelChild = $curMenuItem->getLabel ();
									
									$urltabex = explode($urlparent,$urlChild);
									$urltab = $urltabex[1];	
                            ?>
                            <li class="<?php echo ($tabId==$i++)?'active':'';?>"><a href="#section<?php echo $s++;?>"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> <?php echo $labelChild;?>&nbsp;</a></li>
                            <?php
								}
							?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
                        
                                      <div class="media">
                                            <div class="media-body">List of dashboard found.</div>
                                        </div>
		                      	<div class="btn-group pull-right">
		                        <button class="btn btn-primary" onClick="location.href='list.php?tabId=1';">Add Dashboard</button>
		                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
		                        <span class="caret"></span></button>
		                        <ul class="dropdown-menu">
		                            <li><a onClick="location.href='list.php?tabId=1';">Add Dashboard</a></li>
		                        </ul>
		                        </div>
							<?php
							if ($totDashboard <= 0) {
								?>
								<br/><br/> 
							<div class="alert alert-info" align="center">
								No Dashboard found. Click on 'Add Dashboard' button to add.
							</div>
							<?php } ?>  
                            <?php
									if ($totDashboard > 0) {
										?>    
										<br/><br/> 
										<div class="table-responsive">    
									<table class="table table-bordered table-hover table-striped">	
										<thead>
											<tr>
												<th>#.</th>
												<th>Title</th>
												<th>Description</th>
												<th>Number of Columns</th>
												<th>Number of Portlets</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$counter = 1;
										$curDashboard = new KS_Dashboard ();
										foreach ( $arrDashboard as $curDashboard ) {
											$bColumns = unserialize($curDashboard->getPortlet());
											$countCols = 0;
											$countBoxs = 0;
											if($bColumns){
												foreach ( $bColumns as $curbColumns => $box ) {
													if ($curbColumns != ''){
														$countCols = ++$countCols;
														foreach ( $box as $curbBox) {
															$countBoxs = ++$countBoxs;
														}
													}
												}
											}
									
									
											?>
											<tr valign="top">
												<td align="center"><?php echo $counter ++;?>.</td>
												<td width="23%" align="left"><strong><a
													href="display.php?did=<?php echo $curDashboard->getId ();?>&tabId=1" class="lead"><?php echo $curDashboard->getTitle ();?></a></strong>&nbsp;</td>
												<td align="left"><?php echo nl2br( substr ($curDashboard->getDesc (), 0, 100) );?><?php echo (strlen($curDashboard->getDesc ()) > 100)?'...':'';?>&nbsp;</td>
												<td width="15%" align="center"><?php echo $countCols;?>&nbsp;</td>
												<td align="center"><?php echo $countBoxs;?>&nbsp;</td>
												<td align="center" nowrap><input type="button" value="Properties"
													onClick="location.href='display.php?did=<?php echo $curDashboard->getId ();?>&tabId=1';" class="btn btn-primary">
                                                    <input type="button" value="Delete"
													onClick="deleteMenu('<?php echo $curDashboard->getId ();?>');"
													class="btn btn-danger" <?php echo $disabledelete;?>></td>
											</tr>
											<?php
										}
										?>
										</tbody>
									</table>		
									</div>							
										<?php
									}
									?>
                            	</div>
	                            <?php
	                            	$t = 0;
	                            	$tab = 0;
									$arrMenuSection = array_shift($arrMenuItem);
									foreach ( $arrMenuItem as $curMenuItem ) {
										$urlChild = $curMenuItem->getUrl ();
										$menuid = $curMenuItem->getId ();
										$labelChild = $curMenuItem->getLabel ();
										
										$urltabex = explode($urlparent,$urlChild);
										$urltab = $urltabex[1];
	                            ?>
	                            <div class="tab-pane <?php echo ($tabId==++$tab)?'active':'';?>" id="section<?php echo ++$t;?>">
	                                     <?php
									include_once $urltab;
										?>
	                            </div>
	                            <?php
									}
								?>
                
                      	</div>   
                   
                   </div>
		</div>                  

<script>

$(document).ready(function () {

});

function deleteMenu(id){
	if (confirm('Are you sure to delete this Dashboard?')){
		$.post("delete_handler.php", {
			id : id }, function(data) {
				window.location.href = 'list.php?msg=deleted';
		});
	}
}
</script>

<?php
include_once '../footer.php';
?>
