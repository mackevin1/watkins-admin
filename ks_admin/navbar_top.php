<?php

$adminUrl = KSCONFIG_URL . "ks_admin";

//get userid from session
$ks_session = CUSTOM_User::getSessionData ();

//configure control panel
$cp_menu_active = 1;
$cp_option_active = 1;
$cp_user_active = 1;
$cp_dashboard_active = 1;
$cp_news_active = 1;
$cp_acl_active = 1;
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Dynamic Admin Panel</a>
  </div>

  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
     <ul class="nav navbar-nav">
	<li class=""><a href="<?php echo KSCONFIG_URL;?>home.php"><i class="glyphicon glyphicon-home"></i> Home</a></li>
	<li class="dropdown"><a href="#" class="dropdown-toggle"
		data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Control Panel <b class="caret"></b> </a>
	
		<ul class="dropdown-menu megamenu">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/';" ><i class="glyphicon glyphicon-cog"></i> Control Panel Home</li>
					<li class="divider"></li>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<li class="labelmegapills">Core Features</li>
					<li class="divider"></li>
					<?php if($cp_dashboard_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-dashboard/';" ><i class="glyphicon glyphicon-dashboard"></i> Dashboard</li><?php } ?>
					<?php if($cp_user_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-user/';" ><i class="glyphicon glyphicon-user"></i> Users</li><?php } ?>
					<?php if($cp_menu_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-menu/';" ><i class="glyphicon glyphicon-th-list"></i> Menu</li><?php } ?>
					<?php if($cp_option_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-option/';" ><i class="glyphicon glyphicon-check"></i> Option</li><?php } ?>
					<?php if($cp_news_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-news/';" ><i class="glyphicon glyphicon-star"></i> News</li><?php } ?>
					<?php if($cp_acl_active) { ?><li class="megapills" onClick="location.href='<?php echo $adminUrl; ?>/admin-acl/';" ><i class="glyphicon glyphicon-random"></i> Access Control List (ACL)</li><?php } ?>
				</div>
			</div>
		</ul>
	</li>
	<li class="dropdown navbar-right"><a data-toggle="dropdown" class="dropdown-toggle"
		href="#"><i class="glyphicon glyphicon-user"></i> <?php echo $ks_session ['USR_NAME'];?> <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="<?php echo KSCONFIG_URL;?>ks_user/modify.php?id=<?php echo $ks_session ['USR_ID'];?>"><i class="glyphicon glyphicon-user"></i> Edit Profile</a></li>
			<li><a href="<?php echo KSCONFIG_URL;?>ks_user/changepassword.php"><i class="glyphicon glyphicon-edit"></i> Change Password</a></li>
			<li class="divider"></li>
			<li><a href="<?php echo KSCONFIG_URL;?>ks_user/logout.php"><i class="glyphicon glyphicon-off"></i> Logout</a></li>
		</ul>
	</li>
	</ul>
  </div><!-- /.navbar-collapse -->
</nav>
