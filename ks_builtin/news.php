<?php

include_once '../library.php';

session_start();
//get userid from session
$ks_session = CUSTOM_User::getSessionData ();
$usr_role = $ks_session ['USR_ROLE'];
$usr_id = $ks_session ['USR_ID'];
$usr_name = $ks_session ['USR_NAME'];

$format = KS_Filter::inputSanitize ( $_GET ['format'] );
$maxnews = (int) $_GET ['maxnews'];
if($maxnews == 0){
	$maxnews = 5;//by default 5
}

$arrNews = KS_News::listNews ( 100 );
$arrNewsUnread = KS_News::listNews ( 100 );

$totalUnread = 0;
// handle unread flag
if (is_array($arrNewsUnread ['data'])) {
	foreach ( $arrNewsUnread ['data'] as $curNews ) {

		$usrread = unserialize ( $curNews ['userread'] );
		if (count ( $usrread ['read'] ) == 0) {
			$usrread ['read'] = array ();
		}
		if (!in_array ( $usr_id, $usrread ['read'] )) {
			$totalUnread++;
		}
	}
}

$arrReturn = array ();

if ($format == 'json') {

	if (is_array($arrNews ['data'])) {

		foreach ( $arrNews ['data'] as $curNews ) {
			$desc = $curNews ['desc'];
			$restDesc = substr ( $desc, 0, 60 );
			if (strlen ( $desc ) > 60) {
				$restDesc .= '...';
			}

			// handle read flag
			$usrread = unserialize ( $curNews ['userread'] );
			if (count ( $usrread ['read'] ) == 0) {
				$usrread ['read'] = array ();
			}

			if (in_array ( $usr_id, $usrread ['read'] )) {
				// $menunews .= '';
			} else {
				$arrCurrentNews = array ();
				$arrCurrentNews ['title'] = $curNews['title'];
				$arrCurrentNews ['desc'] = $restDesc;
				$arrCurrentNews ['url'] = $curNews ['id'];
				$arrCurrentNews ['startdate'] = date('d F',strtotime($curNews ['startdate']));
				$arrNewsFinal [] = $arrCurrentNews;
			}
		}
	}

	//get total news to be display by maxNews
	if(count($arrNewsFinal) != 0){
		$arrNewsFinal = array_slice($arrNewsFinal, 0, $maxnews);
	}
	$arrReturn ['news'] = $arrNewsFinal;
	$arrReturn ['total_unread'] = $totalUnread;

	echo json_encode ( $arrReturn );
	exit;
} else {

	$menunews = "<li class=\"dropdown\"><a data-toggle=\"dropdown\" class=\"dropdown-toggle\" href=\"#\">News <span class=\"badge badge-important\" id=\"ks_news_badge_unread\">".$totalUnread."</span></a>";
	$menunews .= "<ul class=\"dropdown-menu\">";

	if (is_array($arrNews ['data'])) {

		foreach ( $arrNews ['data'] as $curNews ) {

			$desc = $curNews ['desc'];
			$restDesc = substr ( $desc, 0, 60 );

			if (strlen ( $desc ) > 60) {
				$restDesc .= '...<a href="../builtin/newsdisplay.php?id=' . $curNews ['id'] . '">[Read More]</a>';
			}
			// handle read flag
			$usrread = unserialize ( $curNews ['userread'] );
			if (count ( $usrread ['read'] ) == 0) {
				$usrread ['read'] = array ();
			}
			if (in_array ( $usr_id, $usrread ['read'] )) {
				// $menunews .= '';
			} else {
				$menunews .= "<li><p><a href=\"../builtin/newsdisplay.php?id=" . $curNews ['id'] . "\">" . $curNews ['title'] . "</a><strong> (". $curNews ['startdate'] . ") </strong><br/>" . $restDesc . "</p></li>";
			}
		}
	}

	$menunews .= "</ul></li>";
}

echo $menunews;
