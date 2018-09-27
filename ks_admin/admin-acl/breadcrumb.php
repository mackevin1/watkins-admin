<?php

$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );

$objRole = new KS_Acl_Role ( );
$objRole->setSearchRecordsPerPage ( 1000 );
$arrRoles = $objRole->search ();
$totRoles = count($arrRoles);


foreach ( $arrRoles as $curRoles ) {
	
	if($roleId ==  $curRoles->getId()){
		$highlightli = "style=\"background-color:#CCC\"";
	}else{
		$highlightli = "style=\"\"";
	}

	$strRoles .= '<li><a '.$highlightli.' href="?roleId=' . $curRoles->getId() . '">' .$curRoles->getName(). '</a></li>';

}
?>
<ul class="breadcrumb">
	<li><a href="list.php"><i class="glyphicon glyphicon-random"></i> <?=$ks_translate->_('Access Control List'); ?></a>
		</li>
	<li class="dropdown">Role: <a class="dropdown-toggle" id="ks_table"
		data-toggle="dropdown" href="list.php"><?=$objAclRole->getName();?> <b
			class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="list.php">All KS Roles</a></li>
			<li><a href="list.php?tabId=1">Add Role</a></li>
			<li class="divider"><a href="#"></a></li>
			<?=$strRoles;?>
		</ul>
	</li>
	<?php if($ks_scriptname == 'roledisplay'){ ?>
    <li class="active">Properties</li>
    <?php }if($ks_scriptname == 'rolemodify'){ ?>
    <li class="active">Modify Role in <a
		href="roledisplay.php?roleId=<?=$objAclRole->getId();?>"><strong><?=$objAclRole->getName();?></strong></a></li>
    <?php } ?>
</ul>