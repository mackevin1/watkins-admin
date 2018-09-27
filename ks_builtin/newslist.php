<?php
include_once '../library.php';

session_start ();
// get userid from session
$ks_session = CUSTOM_User::getSessionData ();
$usr_role = $ks_session ['USR_ROLE'];
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$arrNews = KS_News::listNews ( 100 );

include_once '../layout_header.php';
?>
<ul class="breadcrumb">
	<li class="active"><i class="glyphicon glyphicon-star"></i> <?php echo $ks_translate->_('News'); ?></li>
</ul>
<?php
if (count ( $arrNews ) <= 0) {
	?>
<p align="center">
<div class="alert alert-info">No News found. Add news through the
	Control Panel.</div>
</p>
<?php
} else {
	foreach ( $arrNews ['data'] as $curNews ) {
		
		$title = $curNews ['title'];
		$desc = $curNews ['desc'];
		$id = $curNews ['id'];
		
		// read flag
		$objNews = new KS_News ();
		$objNews->setId ( $id );
		if (! $objNews->exists ()) {
			break;
		}
		$objNews->select ();
		
		// flag read
		$arrread = unserialize ( $objNews->getUserRead () );
		if (count ( $arrread ) == 0) {
			$arrread = array ();
			$arrread ['read'] [0] = $usr_id;
		} else {
			$billatest = (count ( $arrread ['read'] )) + 1;
			$arrread ['read'] [$billatest] = $usr_id;
		}
		
		$objNews->setUserRead ( serialize ( $arrread ) );
		$objNews->update ();
		?>
<p>
	<span class="lead"><a href="../ks_builtin/newsdisplay.php?id=<?php echo $id;?>"><?php echo $title;?></a></span><br>
	<small><?php echo KS_Date::toDD_MM_YYYY ( $objNews->getStartDate () );?></small><br />
		<?php echo nl2br( substr($desc,0,300).'...' );?>
		</p>
<?php
	
	}
}
?>
</div>
<?php 
include_once '../layout_footer.php';
?>