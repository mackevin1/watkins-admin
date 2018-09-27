<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$parentId = KS_Cpmenu::MENU_MENU;

$sql = "SELECT cpm_url FROM ks_controlpanel_menu WHERE cpm_id = '$parentId'";
$urlparent = $ks_db->fetchOne ( $sql );
if(!$urlparent){
	$urlparent = 'admin-menu/';
}

$objMenuItem = new KS_Cpmenu ( );
$objMenuItem->setSearchSQL ( "SELECT * FROM ks_controlpanel_menu WHERE cpm_parentid = '$parentId' AND cpm_status = 1 " );
$objMenuItem->setSearchSortOrder ( 'ASC' );
$objMenuItem->setSearchSortField ( 'cpm_order' );
$objMenuItem->setSearchRecordsPerPage ( 300 );
$arrMenuItem = $objMenuItem->search ();

$objMenu = new KS_Menu ();
$objMenu->setSearchRecordsPerPage ( 1000 );
$arrMenu = $objMenu->search ();
$totMenu = count($arrMenu);

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}

$showMessageBox = 0;
$showMessageBoxType = ''; 
$msg_desc = '';
switch ($msg) {
	case 'added' :
		$msg_desc = 'Menu has been added.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'updated' :
		$msg_desc = 'Menu has been updated.';
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;

	case 'deleted' :
		$msg_desc = 'Menu has been deleted.';
		$showMessageBoxType = 'alert alert-success';
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
                <li class="active"><i class="glyphicon glyphicon-th-list"></i> <?php echo $ks_translate->_('Menu'); ?></li>
              </ul>
              
                 <div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo $msg_desc;?>
				</div>
         
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
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> <?php echo $labelChild;?></a></li>
                            <?php
								}
							?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
                        
                                      <div class="media">
                                            <div class="media-body">List of Menu found.
                                            </div>
                                        </div>
                                        
                            <div class="btn-group pull-right">
                        <button class="btn btn-primary" onClick="location.href='list.php?tabId=1';">Add Menu</button>
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a onClick="location.href='list.php?tabId=1';">Add Menu</a></li>
                        </ul>
                        </div>
                                        <?php if ($totMenu <= 0){?>
                                        <br/><br/>
									<div class="alert alert-info" align="center">
										No Menu found. Click on 'Add Menu' button to add.
									</div>
										<?php
									}
									?>
                             
                            <?php
									if ($totMenu > 0) {
										?>         
										<br/><br/> 
										<div class="">    
									<table class="table table-bordered table-hover table-striped">	
										<thead>			
											<tr align="center">
												<th>#</th>
												<th>ID</th>
												<th>Name</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										<?php
										$counter = 1;
										$curMenu = new KS_Menu ();
										foreach ( $arrMenu as $curMenu ) :
											?>
										  <tr>
												<td align="center"><?php echo $counter ++;?>.</td>
												<td align="center"><?php echo $curMenu->getId ();?></td>
												<td><a href="display.php?mid=<?php echo $curMenu->getId ();?>&tabId=1" class="lead"><?php echo $curMenu->getName ();?></a></td>
												<td align="center" nowrap>
												<input type="button" value="Properties" onClick="location.href='display.php?mid=<?php echo $curMenu->getId ();?>&tabId=1';" class="btn btn-primary">
												<input type="button" value="Source Code" onclick="location.href='display.php?mid=<?php echo $curMenu->getId ();?>&tabId=5';" class="btn btn-default">
												
												<?php 
												//can only delete if there are more than 1
												if($totMenu > 1) {
												?>
												<input type="button" value="Delete" onClick="deleteMenu('<?php echo $curMenu->getId ();?>');" class="btn btn-danger">
												<?php 
												}
												?>
												<div id="divCode" title="Menu Generated Code">
												<p align="center"><textarea id="textareaCode" cols="100" rows="8"
													onfocus="this.select();" wrap="off" style="display: none;"></textarea>
												<input type="button" name="btnCopy2Clipboard" id="btnCopy2Clipboard"
													value="Copy to Clipboard" onclick="copytoClipBoard();" class="btn btn-primary"/></p>
												</div>
												</td>
											</tr>
											<?php
											endforeach;
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
$(document).ready(function() {

	$("div[id^='divCode']").hide();
	$("#btnCopy2Clipboard").hide();
	if (jQuery.browser.msie) {
		$("#btnCopy2Clipboard").show();
	}

});

function copytoClipBoard() {
	if (jQuery.browser.msie) {
		var sContents = $("#textareaCode").text();
		window.clipboardData.setData("Text", sContents);
	}
}

function deleteMenu(id){
	if (confirm('Are you sure to delete this Menu?')){
		$.post("delete_handler.php", {
			mid : id }, function(data) {
				window.location.href = 'list.php?msg=deleted';
		});
	}
}
</script>
<?php
include '../footer.php';
?>