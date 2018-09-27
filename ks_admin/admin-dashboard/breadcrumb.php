<?php

$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );

$objDashboard1 = new KS_Dashboard ();
$objDashboard1->setSearchRecordsPerPage ( 1000 );
$objDashboard1->setSearchSortField('dsh_id');
$objDashboard1->setSearchSortOrder('DESC');
$arrDashboard = $objDashboard1->search ();
$totDashboard = count ( $arrDashboard );

$strForm = '';
foreach ( $arrDashboard as $curDashboard ) {
	
	if($did == $curDashboard->getId ()){
		$highlightli = "style=\"background-color:#CCC\"";
	}else{
		$highlightli = "style=\"\"";
	}

	$strForm .= '<li><a '.$highlightli.' href="?did=' . $curDashboard->getId () . '">' .$curDashboard->getTitle() . '</a></li>';

}

?>
<ul class="breadcrumb">
	<li><a href="list.php"><i class="glyphicon glyphicon-dashboard"></i> <?php echo $ks_translate->_('Dashboard'); ?></a>
	</li>
	<li class="dropdown">Dashboard : <a class="dropdown-toggle"
		id="ks_table" data-toggle="dropdown" href="list.php?did=<?php echo $did;?>"> <?php echo $objDashboard->getTitle ();?>
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="list.php">All Dashboard</a></li>
		<li><a href="list.php?tabId=1">Add Dashboard</a></li>
		<li class="divider"><a href="#"></a></li>
		<?php echo $strForm;?>
	</ul>
	</li>
	<?php if($ks_scriptname == 'modify'){ ?>
	<li class="active">Modify Dashboard in <a
		href="display.php?did=<?php echo $did;?>"><strong><?php echo $objDashboard->getTitle ();?></strong></a></li>
		<?php } if($ks_scriptname == 'display'){ ?>
		<li class="active">Properties</li>
		<?php } if($ks_scriptname == 'menu_add'){ ?>
		<li class="active">Add to Menu in <a
		href="display.php?did=<?php echo $did;?>"><strong><?php echo $objDashboard->getTitle ();?></strong></a></li>
		<?php } ?>
</ul>
