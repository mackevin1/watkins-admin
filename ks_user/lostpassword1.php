<?php
include_once '../library.php';

// check if really coming from lostpassword.php
$loginUrl = KSCONFIG_URL . "ks_user/lostpassword.php";

$serverReferer = explode ( "?", $_SERVER ['HTTP_REFERER'] );

if (! preg_match ( "|$loginUrl|", $serverReferer [0] )) {
	header ( "Location: lostpassword.php?msg=redirect" );
	exit ();
}

// next, check for secret key set in login.php,
$key = KS_Filter::inputSanitize ( $_POST ['key'] );
$value = KS_Filter::inputSanitize ( $_POST ['value'] );
if ((strlen ( $key ) == 0) || (strlen ( $value ) == 0)) {
	header ( "Location: lostpassword.php?msg=missingkey" );
	exit ();
}

// this is the secretkey combination.. also used in loginhandler.php
// so if changed here, must change there as well..
// otherwise, the login will always fail..
$strSecret = $key . date ( "Y-m-d" );
if ($value != sha1 ( $strSecret )) {
	header ( "Location: lostpassword.php?msg=missingvalue" );
	exit ();
}

$userid = KS_Filter::inputSanitize ( $_POST ['userid'] );
if (! strlen ( $userid )) {
	header ( "Location: lostpassword.php?msg=userid_notfound&id=$userid" );
	exit ();
}

$objUser = new CUSTOM_User ();
$objUser->setId ( $userid );

if (! $objUser->exists ()) {
	header ( "Location: lostpassword.php?msg=userid_notfound&id=$userid" );
	exit ();
}
$objUser->select ();

// generate a random seed, 8 digits long
$intRandom = mt_rand ( 11111111, 99999999 );

// for security reasons, we also need to set the deadline of the url
$intDeadlineInDays = 2; // lets make it 2 days = 48 hours
                        
// we need to store this in table lost password
$deadline = date ( "Y-m-d H:i:s", time () + ($intDeadlineInDays * 24 * 60 * 60) );

$objLostpassword = new KS_Lostpassword ();
$objLostpassword->setUserid ( $objUser->getId () );
$objLostpassword->setRandom ( $intRandom );
$objLostpassword->setDeadline ( $deadline );
$objLostpassword->insert ();

// then we email the new link.
$strUrlRetrieval = KSCONFIG_URL . "ks_user/lostpassword2.php?ran=$intRandom";
$strSubject = "Password retrieval for " . KSCONFIG_SYSTEM_NAME;
$strContent = "
Hi {$objUser->getName()}!


We have received a request for lost password retrieval for user ID '{$objUser->getId()}'.

Click the following URL to retrieve your password:
$strUrlRetrieval

The link will expire in $intDeadlineInDays days. You may safely ignore this message
if someone else try to retrieve your password.

Thank you.

Administrator,";

$strContent .= KSCONFIG_SYSTEM_NAME;

$pageTitle = "Retrieve lost password";

mail ( $objUser->getEmail (), $strSubject, $strContent );
include_once '../layout_header.php';
?>
<script>
$(document).ready(function(){
	try {
	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});
</script>

<ul class="breadcrumb">
	<li class="active"><?php echo $ks_translate->_('Lost Password Retrieval'); ?></li>
</ul>

<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<i class="glyphicon glyphicon-exclamation-sign"></i> An email has
			been sent to "<strong><?php echo $objUser->getEmail ();?></strong>". Check
			that email.
		</div>

		<div class="">
			<p>The link will expire after <?php echo $intDeadlineInDays;?> days.</p>
			<p>
				Contact system administrator for assistance or back to <a
					href="login.php" class="btn btn-primary">Login page</a>
			</p>
		</div>
	</div>
</div>

</div>
<?php
include_once '../layout_footer.php';
?>