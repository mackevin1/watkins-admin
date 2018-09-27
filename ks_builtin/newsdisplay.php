<?php
include_once '../library.php';

session_start ();
// get userid from session
$ks_session = CUSTOM_User::getSessionData ();
$usr_role = $ks_session ['USR_ROLE'];
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$id = ( int ) $_GET ['id'];

$objNews = new KS_News ();
$objNews->setId ( $id );
if (! $objNews->exists ()) {
	header ( "Location: news.php?msg=notfound&id=$id" );
}
$objNews->select ();

// if users is not authenticated, only show public news
$isAuth = CUSTOM_User::checkAuthentication ();
if ($isAuth == 1) {
	if ($objNews->getPrivate () != 1) {
		header ( "Location: news.php?msg=notfound&id=$id" );
	}
} else {
	if ($objNews->getPublic () != 1) {
		header ( "Location: news.php?msg=notfound&id=$id" );
	}
}

$title = $objNews->getTitle ();
$desc = $objNews->getDesc ();

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

include_once '../layout_header.php';
?>
<ul class="breadcrumb">
	<li class=""><i class="glyphicon glyphicon-star"></i> <a
		href="newslist.php"><?php echo $ks_translate->_('News'); ?></a></li>
	<li class="active"><?php echo $title;?></li>
</ul>
<p>
	<span class="lead"><?php echo $title; ?></span>
</p>
<p class=""><?php echo KS_Date::toDD_MM_YYYY ( $objNews->getStartDate () );?></p>
<br/><?php echo nl2br ( $desc );?>
</div>
<?php 
include_once '../layout_footer.php';