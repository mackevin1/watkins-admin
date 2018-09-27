<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$did = 0; 
if (isset ($_GET ['did'])) {
	$did = ( int ) $_GET ['did'];
}

$tabId = 0; 
if (isset ($_GET ['tabId'])) {
	$tabId = ( int ) $_GET ['tabId'];
}

$objDashboard = new KS_Dashboard ();
$objDashboard->setSearchRecordsPerPage ( 1000 );
$arrDashboard = $objDashboard->search ();
$totDashboard = count ( $arrDashboard );
$disabledelete = '';
if($totDashboard == 1){//makesure at least one data exist
	$disabledelete = "disabled=\"disabled\"";
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

$msg = 0; 
if (isset ($_GET ['msg'])) {
	$msg = ( int ) $_GET ['msg'];
}
// $msg = 'added';

$showMessageBox = 0;
switch ($msg) {
	case 'added' :
		$showMessageBoxType = 'alert alert-info';
		$showMessageBox = 1;
		$msg_desc = "Dashboard has been added. Specify properties for this dashboard.";
		break;
	case 'updated' :
		$msg_desc = "Dashboard has been updated.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	case 'portlet_deleted' :
		$msg_desc = "Portlet has been deleted.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	case 'portlet_updated' :
		$msg_desc = "Portlet has been updated.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	case 'portlet_added' :
		$msg_desc = "Portlet has been added.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	case 'menu_added' :
		$msg_desc = "Dashboard has been added in menu.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	default:
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';

?>
<script>
$(document).ready(function(){
	$("#formAdd").validationEngine();
	$("#usr_id").select();
});

function doPreview() {
	var name = escape( $("#name").val() );
	var desc = escape( $("#desc").val() );
	var columns = escape( $("#columns").val() );
	var portlets = escape( $("#portlets").val() );

	$("#loadImageTeam").show();
	$("#divTeamSearchResult").load("<?php echo KSCONFIG_URL;?>/admin/admin-dashboard/preview.php?did=<?php echo $did;?>&name=" + name + "&desc=" + desc + "&columns=" + columns + "&portlets=" + portlets, {limit: 25}, function(){
		   $("#loadImageTeam").hide();
	 });

}
</script>

	<div class="container">
	<?php
	include_once '../navbar_top.php';
	
	include_once 'breadcrumb.php';
	?>

<div class="<?php echo $showMessageBoxType;?>" style="display: <?php echo ($showMessageBox == 0) ?'none':'';?>">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo $msg_desc;?></div>

  <p>Property page for <strong><?php echo $objDashboard->getTitle ();?></strong>.</p>
          
          <div class="tabbable tabs-left">
                        <ul class="nav nav-tabs">
                        
                            <li class="<?php echo ($tabId==0)?'active':'';?>"><a href="#section0"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Properties&nbsp;</a></li>
                            <li class="<?php echo ($tabId==1)?'active':'';?>"><a href="#section1"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Editor&nbsp;</a></li>
                            <li class="<?php echo ($tabId==2)?'active':'';?>"><a href="#section2"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Source Code&nbsp;</a></li>   
                            <li class="<?php echo ($tabId==3)?'active':'';?>"><a href="#section3"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Menu&nbsp;</a></li>   
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
                            
	<table class="table table-bordered table-hover table-striped">
		<tbody>
			<tr>
				<th width="25%" align="right">Title :</th>
				<td width="75%"><?php echo $objDashboard->getTitle ();?></td>
			</tr>
			<tr>
				<th align="right">Description :</th>
				<td><?php echo $objDashboard->getDesc ();?></td>
			</tr>
			<tr>
				<th align="right">Number of Columns :</th>
				<td><?php echo $countCols;?></td>
			</tr>
			<tr style="display:none">
				<th align="right">Number of Portlets :</th>
				<td><input id="portlets" type="text"
					value="<?php echo $countBoxs;?>"
					name="portlets" size="12" maxlength="10" /></td>
			</tr>
			<tr id="trsubmit">
				<td></td>
				<td align="left">
					<input type="button" id="btnModify" name="btnModify" value="Modify" 
						onclick="location.href='modify.php?did=<?php echo $did;?>'" class="btn btn-primary" />
				 	<input type="button" value="Delete" onClick="deleteMenu('<?php echo $did?>');" 
				 		class="btn btn-danger" <?php echo $disabledelete;?>>
				</td>
			</tr>
		</tbody>
	</table>
   </div>
   <div class="tab-pane <?php echo ($tabId==1)?'active':'';?>" id="section1">
   			<div class="row">
  				<div class="col-xs-12 col-sm-12 col-md-12">
           		<div class="media">
      			<div class="media-body">Use this editor to edit the portlet.</div>
				</div> 
                    <div class="btn-group pull-right">
                        <button class="btn btn-primary" onClick="addPorlet();">Add Portlet</button>
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a onClick="addPorlet();">Add Portlet</a></li>
                        </ul>
                        </div>
                   </div>
				</div>
					<br/>
							<div id="divTeamSearchResult" >
							  <?php include_once 'preview.php';?>
				             </div>
			
   </div>
   <div class="tab-pane <?php echo ($tabId==2)?'active':'';?>" id="section2">
               <?php 
			   $parentId = 1;
			   include_once 'source_code.php'; ?>                   
   </div>  
	   <div class="tab-pane <?php echo ($tabId==3)?'active':'';?>" id="section3"><?php
	include_once 'menu.php';
	?></div>
  
  
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
				window.location.href = 'list.php?e=deleted';
		});
	}
}
</script>
<?php
include_once '../footer.php';
?>



