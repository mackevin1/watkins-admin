<?php

include_once '../../library.php';
include_once '../header_isadmin.php';

$search = '';

if (! isset ($st)) {
	$st = '';
}

$sqlWhere = '';

if ($st == 'a') {
	//active users
	$sqlWhere .= " AND usr_enabled='1'" ;
	$strNoUserFound = "No Active users found";
} else if ($st == 'd') {
	//disabled users
	$sqlWhere .=" AND usr_enabled='0'";
	$strNoUserFound = "No Disabled users found";
}

$fname = '';
if (isset ($_GET ['fname'])) {
	$fname = KS_Filter::inputSanitize ( $_GET ['fname'] );
	$fname = preg_replace("/[^a-zA-Z0-9 ]+/", "", $fname);
}

$frole = '';
if (isset ($_GET ['frole'])) {
	$frole = KS_Filter::inputSanitize ( $_GET ['frole'] );
	$frole = preg_replace("/[^a-zA-Z0-9]+/", "", $frole);
}

$femail = '';
if (isset ($_GET ['femail'])) {
	$femail = KS_Filter::inputSanitize ( $_GET ['femail'] );
	$femail = preg_replace("/[^a-zA-Z0-9]+/", "", $femail);
}

if($fname){
	$sqlWhere .= " AND usr_name LIKE '%".$fname."%'";
}
if($femail){
	$sqlWhere .=" AND usr_email LIKE '%".$femail."%'" ;
}

$objUser = new CUSTOM_User ();
$sqluser = "SELECT *  FROM t_user WHERE 1 $sqlWhere";
$objUser->setSearchSql ($sqluser);
$objUser->setSearchRecordsPerPage ( 10000 );
$objUser->setSearchSortField ( 'usr_name' );
$objUser->setSearchSortOrder('ASC');

$arrUsers = $objUser->search ();
$totUsers = count ( $arrUsers );

$msg2 = '';
$msg2_desc = '';
if ($totUsers == 0) {
	$msg2 = 'users_notfound';
}

$showMessageBoxType2 = '';
$showMessageBox2 = 0;
switch ($msg2) {
	case 'users_notfound' :
		$msg2_desc = "No User ID found.";
		$showMessageBoxType2 = 'alert alert-info';
		$showMessageBox2 = 1;
		break;
}

?>
 
<div class="btn-group pull-right">
	<button class="btn btn-primary" onClick="location.href='list.php?tabId=1';">Add User</button>
    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
    <ul class="dropdown-menu">
	    <li><a onClick="location.href='list.php?tabId=1';">Add User</a></li>
    </ul>
</div>
	           	
<div class="<?php echo $showMessageBoxType2;?>" style="display: <?php echo ($showMessageBox2 == 0) ?'none':'';?>">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<?php echo $msg2_desc;?></div>

          <p class="clearfix">List of users found. <span class="label label-info"><?php echo $totUsers;?></span>  users.</p>
           	
          <form class="form-inline" role="form" method="get" action="list.php">
	          Filter users by : <input id="fname" class="form-control ks-form-control" type="text" value="<?php echo $fname;?>" size="28" 
	          maxlength="255" name="fname" placeholder="Name"> 
	          <input id="femail" class="form-control ks-form-control" type="text" value="<?php echo $femail;?>" size="28" maxlength="255" 
	          name="femail" placeholder="Email">
	          <input type="submit" name="search" value="Search" class="btn btn-primary">
			</form>
<?php
if ($totUsers > 0) {
	?>
<br/>
<table class="table table-bordered table-hover table-striped">
	<thead>
		<tr align="center">
			<td class="col-lg-1">#</td>
			<td class="col-lg-2">User ID</td>
			<td class="col-lg-2">Name</td>
			<td class="col-lg-2">Email</td>
			<td class="col-lg-1">Role</td>
			<td class="col-lg-1">Status</td>
			<td class="col-lg-3">Action</td>
		</tr>
	</thead>
	<tbody>
<?php
	
	$counter = 1;
	foreach ( $arrUsers as $objUser ) {
		//$objList = new KS_Lists();
		$urlDisplay = "display.php?id=" . $objUser->getId ();
		$urlDelete = "delete.php?id=" . $objUser->getId ();
		
		?>
		<tr align="center">
			<td><?php echo $counter ++?>.</td>
			<td align="left"><a href="<?php echo $urlDisplay;?>" class="lead"><?php echo $objUser->getId ();?></a></td>
			<td align="left"><?php echo $objUser->getName ();?></td>
			<td align="left"><a href="mailto:<?php echo $objUser->getEmail ();?>"><?php echo $objUser->getEmail ();?></a></td>
			<td><?php echo $objUser->getRole();?></td>
			<td align="center" valign="top"><?php echo $objUser->getEnabled () ? '<span class="label label-info">Enabled</span>' : '<span class="label label-danger">Disabled</span>';?>&nbsp;</td>
			<td nowrap><input type="button" onclick="location.href='<?php echo $urlDisplay;?>';"
				value="Properties" class="btn btn-primary"> 
			<?php 
			//cant delete if there's only 1 user
			if($totUsers > 1) {
			?>	
				<input type="button" id="btnDelete"
				onclick="doDelete('<?php echo addslashes ( $objUser->getId () );?>');"
				value="Delete" class="btn btn-danger">
			<?php 
			}
			?>
			</td>
		</tr>
<?php
	}
	?></tbody>
</table>

<?php
} //end if $totUser > 0
?>

<script>
function doDelete(userId) {
	if(confirm("Delete user '" + userId + "'?")) {
		location.href= "delete.php?id=" + userId;
	}
}
</script>