<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$tabId = 0;
if (isset ($_GET ['tabId'])) {
	$tabId = (int) $_GET ['tabId'];
}

$fname = '';
if (isset ($_GET ['fname'])) {
	$fname = KS_Filter::inputSanitize ( $_GET ['fname'] );
}

$frole = '';
if (isset ($_GET ['frole'])) {
	$frole = KS_Filter::inputSanitize ( $_GET ['frole'] );
}

$femail = '';
if (isset ($_GET ['femail'])) {
	$femail = KS_Filter::inputSanitize ( $_GET ['femail'] );
}

$parentId = KS_Cpmenu::MENU_USERS;

$sql = "SELECT cpm_url FROM ks_controlpanel_menu WHERE cpm_id = '$parentId'";
$urlparent = $ks_db->fetchOne ( $sql );
if ($urlparent == ''){
	$urlparent = 'admin-user/';
}

$objMenuItem = new KS_Cpmenu ( );
$objMenuItem->setSearchSQL ( "SELECT * FROM ks_controlpanel_menu WHERE cpm_parentid = '$parentId' AND cpm_status = 1 " );
$objMenuItem->setSearchSortOrder ( 'ASC' );
$objMenuItem->setSearchSortField ( 'cpm_order' );
$objMenuItem->setSearchRecordsPerPage ( 300 );
$arrMenuItem = $objMenuItem->search ();
$countmenu = count($arrMenuItem);

$objUser = new CUSTOM_User ();
$objUser->setSearchRecordsPerPage ( 10000 );
$arrUsers = $objUser->search ();
$totUsers = count ( $arrUsers );

$usr_id = ''; 
if (isset ($_GET ['usr_id'])) {
	$usr_id = KS_Filter::inputSanitize ( $_GET ['usr_id'] );
}

$msg = ''; 
if (isset ($_GET ['msg'])) {
	$msg = KS_Filter::inputSanitize ( $_GET ['msg'] );
}
$showMessageBox = 0;
$showMessageBoxType = '';

switch ($msg) {
	case 'userid_notfound' :
		$msg_desc = "User ID '$usr_id' is not found.";
		$showMessageBoxType = 'alert alert-danger';
		$showMessageBox = 1;
		break;
	case 'userid_deleted' :
		$msg_desc = "User ID '$usr_id' has been deleted.";
		$showMessageBoxType = 'alert alert-success';
		$showMessageBox = 1;
		break;
	default :
		$msg_desc = '';
		$showMessageBox = 0;
}

include_once '../header_bootstrap.php';
?>
	<div class="container">
	  <?php
	include_once '../navbar_top.php';
	?>
            
              <ul class="breadcrumb">
                <li class="active"><i class="glyphicon glyphicon-user"></i> <?php echo $ks_translate->_('User'); ?></li>
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
									
									$urltabex = explode($urlparent,$urlChild);
									$urltab = $urltabex[1];	
                            ?>
                            <li class="<?php echo ($tabId==$s)?'active':'';?>"><a href="#section<?php echo $s;?>"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> <?php echo $labelChild;?></a></li>
                            <?php
                            	$s++;
								}
							?>
                           <li class="<?php echo ($tabId==$s)?'active':'';?>"><a href="#section<?php echo $s;?>"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Active Users</a></li>
                          <li class="<?php echo ($tabId==$s+1)?'active':'';?>"><a href="#section<?php echo $s+1;?>"
                                data-toggle="tab"><i class="glyphicon glyphicon-chevron-right"></i> Disabled Users</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo ($tabId==0)?'active':'';?>" id="section0">
                              <?php
									$st = 'all';
								include 'listbystatus.php';
									?>
                                    
                            </div>
                            <?php
                            	$t = 1;
								$arrMenuSection = array_shift($arrMenuItem);
								foreach ( $arrMenuItem as $curMenuItem ) {
									$urlChild = $curMenuItem->getUrl ();
									$menuid = $curMenuItem->getId ();
									$labelChild = $curMenuItem->getLabel ();
									
									$urltabex = explode($urlparent,$urlChild);
									$urltab = $urltabex[1];
                            ?>
                            <div class="tab-pane <?php echo ($tabId==$t)?'active':'';?>" id="section<?php echo $t;?>">
                                <?php
								include_once $urltab;
								?>
                            </div>
                            <?php
                            	$t++;
								}
							?>
                            <div class="tab-pane <?php echo ($tabId==$t)?'active':'';?>" id="section<?php echo $t;?>">
								<?php
								$st = 'a';
								include 'listbystatus.php';
								?>
                            </div>
                            
                              <div class="tab-pane <?php echo ($tabId==$t+1)?'active':'';?>" id="section<?php echo $t+1;?>">
                                <?php
								$st = 'd';
								include 'listbystatus.php';
								?>
                            </div>
                
                      </div>   
                   
                   </div>
		</div>                  
<?php
include_once '../footer.php';
?>