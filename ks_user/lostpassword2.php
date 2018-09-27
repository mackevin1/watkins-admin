<?php

include_once '../library.php';

$ran = ( int ) $_GET ['ran'];

if (! $ran) {
	header ( "Location: lostpassword.php?msg=code_invalid" );
	exit ();
}

$sql = "SELECT * FROM ks_lostpassword WHERE lp_random = '$ran' 
		AND TO_DAYS(lp_deadline)-TO_DAYS(NOW()) > 0";
$stmt = $ks_db->query ( $sql );

if ($stmt->rowCount () == 0) {
	header ( "Location: lostpassword.php?msg=code_invalid" );
	exit ();
}

while ( true == ($row = $stmt->fetch ()) ) {
	$usr_id = $row ['lp_userid'];
}
//now we find the user details
$objUser = new CUSTOM_User ();
$objUser->setId ( $usr_id );
if (! $objUser->exists ()) {
	header ( "Location: lostpassword.php?msg=user_notfound&id=$usr_id" );
	exit ();
}
$objUser->select();

session_start();
$_SESSION ['LOSTPASSWORD_USERID'] = $objUser->getId();

// minimum password length
$user_password_minlength = ( int ) KS_Option::getOptionValue ( 'user_password_minlength' );
if (! $user_password_minlength) {
	$user_password_minlength = 8;
}

include_once '../layout_header.php';
?>
<script>
$(document).ready(function(){

	try {
	 	$("#password").focus();
		$("#formLost").validationEngine();

	} catch(error) {
		var msg = "JavaScript Fatal Error: " + error.description;
		alert(msg);
	}
});

</script>

<ul class="breadcrumb">
	<li class="active"><?php echo $ks_translate->_('Lost Password'); ?></li>
</ul>

<div class="row">
  <div class="col-lg-6 col-lg-offset-3">
    <h5>User Lost Password Retrieval</h5>
    <form action="lostpassword3.php" method="post" name="formLost" id="formLost">
      <table class="table table-responsive table-striped table-bordered" align="center">
        <tr>
          <th width="30%" align="right">User ID :</th>
          <td><?php echo $objUser->getId ();?></td>
        </tr>
        <tr>
          <th width="30%" align="right">New Password :</th>
          <td><input type="password" name="password" id="password" value=""
			class="form-control ks-form-control validate[required,minSize[<?php echo $user_password_minlength;?>],maxSize[32]] " /></td>
        </tr>
        <tr>
          <th width="30%" align="right">New Password (again) :</th>
          <td><input type="password" name="password2" id="password2" value=""
			class="form-control ks-form-control validate[required,equals[password]] " /></td>
        </tr>
        <tr>
          <th align="right">&nbsp;</th>
          <td><input type="submit" name="button" id="button" value="Submit" class="btn btn-primary" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</div>
<?php
include_once '../layout_footer.php';
?>