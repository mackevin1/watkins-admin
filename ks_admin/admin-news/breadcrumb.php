<?php
include_once '../../library.php';

$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );

$nid = 0;
if (isset ($_GET ['nid'])) {
	$nid = ( int ) $_GET ['nid'];
}

$objNews1 = new KS_News();
$objNews1->setSearchRecordsPerPage ( 1000 );
$objNews1->setSearchSortField('ns_title');
$objNews1->setSearchSortOrder('DESC');
$arrNews1 = $objNews1->search ();
$totNews = count ( $arrNews1 );

$news_title = 'News details';

$strNews = '';
foreach ( $arrNews1 as $curNews ) {
	
	if($nid == $curNews->getId ()){
		$highlightli = "style=\"background-color:#CCC\"";
		$news_title = $curNews->getTitle();
	}else{
		$highlightli = "style=\"\"";
	}

	$strNews .= '<li><a '.$highlightli.' href="?nid=' . $curNews->getId () . '">' .$curNews->getTitle() . '</a></li>';
}
?>
<ul class="breadcrumb">
	<li><a href="list.php"><i class="glyphicon glyphicon-star"></i> <?php echo $ks_translate->_('News'); ?></a>
	</li>
	<li class="dropdown">News : <a class="dropdown-toggle"
		id="ks_table" data-toggle="dropdown" href="list.php?nid=<?php echo $nid;?>"> <?php echo $news_title;?>
	<b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="list.php">All News</a></li>
		<li><a href="list.php?tabId=1">Add News</a></li>
		<li class="divider"><a href="#"></a></li>
		<?php echo $strNews;?>
	</ul>
	</li>
	<?php if($ks_scriptname == 'modify'){ ?>
		<li class="active">Modify News in <a
		href="display.php?nid=<?php echo $nid;?>"><strong><?php echo $news_title;?></strong></a></li>
	<?php } if($ks_scriptname == 'display'){ ?>
		<li class="active">Properties</li>
	<?php } if($ks_scriptname == 'menu_add'){ ?>
		<li class="active">Add to Menu in <a
		href="display.php?nid=<?php echo $nid;?>"><strong><?php echo $news_title;?></strong></a></li>
	<?php } ?>
</ul>
