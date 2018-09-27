<?php

//always include library
include_once '../library.php';

// check authentication.. if not login, ask to login
$isAuth = CUSTOM_User::checkAuthentication ();

if (! $isAuth) {
	header ( "Location: ../ks_user/login.php?msg=notlogin" );
	exit ();
}

// include page header that contains js and css files
include_once '../layout_header.php';

?>

<!-- Add fancyBox -->
<link rel="stylesheet" href="styles/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/jquery.fancybox.js?v=2.1.5"></script>

<!-- show breadcrumb -->
<ul class="breadcrumb">
	<li class=""><i class="glyphicon glyphicon-home"></i> <a href="../home.php"><?php echo $ks_translate->_('Home'); ?></a></li>
	<li class="active"><i class="glyphicon glyphicon-random"></i> <?php echo $ks_translate->_('Sample ACL'); ?></li>
</ul>

<div class="row">
	<div class="col-md-12">
	<p>This sample shows how ACL works. You can configure ACL in the <a href="../ks_admin/admin-acl">Control Panel</a>.
	</p>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h2>What is ACL?</h2>
		<p>ACL stands for Access Control List, used to control a user's access to a component within an application. 
		An application usually has multi-user and multi-roles, and developers need an easy way to grant or deny privileges.</p>
		
		<p>This product uses Zend_ACL from Zend Framework. In Zend_ACL, all privileges are managed through coding.
		This product makes it easier by storing the ACL configuration in Database and provide a web interface to make changes easier. 
		</p>
		<h2>Defining a Privilege</h2>
		<p>In this example, we want to control access to a button named as 'Add Sample'.
	  <ol>
			<li>Go to Control Panel, <a href="../ks_admin/admin-acl/">choose ACL module</a>.</li>
			<li>Choose a Role, for example ADMIN</li>
			<li>Click tab 'Access Privileges' on the left</li>
			<li>Add Privilege by clicking the 'Add Privilege' button<br>
		    <a class="fancybox" rel="group" href="images/acl_04.png"><img src="images/acl_04_small.png" alt=""/></a></li>
			<li>Specify Resource ID or Create new Resource ID (example: sample) </li>
			<li>Specifiy Privilege ID (example: add)
			<br/><a class="fancybox" rel="group" href="images/acl_00.png"><img src="images/acl_00_small.png" width="406" height="238"  alt=""/></a>
			</li>
			<li>Grant to Role(s) you want. In this example, choose 'ADMIN'.</li>
			<li>Click 'Save'. The privilege has been added.</li>
	  </ol>
		<p>What we've done here is that we've defined a Privilege. We've also selected Roles that have access to this privilege.</p>
		<p>If we don't want to grant access, simply uncheck the corresponding roles.</p>
		<p>This is easier than having to change the source code manually.</p>
		<h2>Example use</h2>
		<p>Once we've defined a privilege, we can place it anywhere we want to. For example, we want to control a button 
		using privilege defined above.</p>
	  <p>The following is the HTML code that we use to create this button. We can control access to this button by adding ACL Source Code.<br/>
		<button class="btn btn-primary">Add Sample</button>
		<code>&lt;button class="btn btn-primary"&gt;Add Sample&lt;/button&gt;</code>
	</p>
	    <ol>
	      <li>Go to the ACL module, choose role <a href="../ks_admin/admin-acl/roledisplay.php?roleId=ADMIN&tabId=1">'Administrator'</a>.</li>
	      <li>Look for the resource named 'Sample' and privilege named 'Add'. Click 'Generate Code' button to generate the source code.<br>
	        The checkbox indicates that this privilege (Add) is granted to the role (ADMIN).
          <br>
        	<a class="fancybox" rel="group" href="images/acl_01.png"><img src="images/acl_01_small.png" alt=""  border="1"/>
        </a>
      </li>
	      <li>Copy the highlighted source code.
            <br>
            <a class="fancybox" rel="group" href="images/acl_02.png">
			<img src="images/acl_02_small.png" alt=""  border="1"/>   
        </a>          </li>
	      <li>Place the copied source code surrounding the button. Make sure to put the button within the if() statement. Since the copied code are PHP, we must put within &lt;?PHP.. ?&gt; tags.<br>
	        <code>&lt;?php<br>
	        <br>
	        $objAcl = new KS_Acl ( );<br>
	        $allowed__add = $objAcl-&gt;isAllowed ( $usr_role, 'sample', 'add' );<br>
	        if($allowed__add) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;//do something if allowed<br>
&nbsp;&nbsp;&nbsp;&nbsp;?&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;button class=&quot;btn btn-primary&quot;&gt;Add Sample&lt;/button&gt;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&lt;?php
<br>
} else {<br>
&nbsp;&nbsp;&nbsp;&nbsp;//do something else<br>
}<br>
?&gt;
<br></code></li>
	      <li>This button will be visible if Administrator is granted the privilege. Try to uncheck the privilege for role 'ADMIN' and this button will not appear. You have to reload this page first.<br>
          <?php
try {
$objAcl = new KS_Acl ( );
$allowed__add = $objAcl->isAllowed ( $usr_role, 'sample', 'add' );
if($allowed__add) {
//do something if allowed
?>
<button class="btn btn-primary">Add Sample</button>
<?php 
} else {
//do something else
}
} catch (Exception $e) {
	echo "<div class=\"alert alert-danger\">Please add Resouce 'Sample' and Privileve 'Add' first. This code will only work after they are defined.</div>";
}
?> </li>
      </ol>
	    <h2>Built-in Integration with Menu</h2>
	    <p>ACL Module has built-in integration with Menu. You can control which role(s) can see a particular menu item.</p>
	    <p><a class="fancybox" rel="group" href="images/acl_03.png"><img src="images/acl_03_small.png" width="406" height="238"  alt=""/></a></p>
	    <h2>ACL Usage</h2>
	    <p>ACL module can be used in many cases, we just need to put them within the if() statement. The following are some examples of ACL:</p>
	    <ul>
	      <li>Controlling access to button (such as Delete, Modify, Approve)</li>
	      <li>Control visibility a section within HTML page (&lt;div&gt;, &lt;table&gt;, &lt;span&gt;)</li>
	      <li>Control access to a page (such as only role MANAGER can see this page)</li>
	      <li>Built-in integration with menu items. </li>
	      <li>Controlling SQL Statement</li>
      </ul>
      <p>&nbsp;</p>
  </div>
</div>

</div><!-- closing div class=container -->
<script>
$(document).ready(function() {
	$(".fancybox").fancybox();
});
</script>
<?php 
include_once '../layout_footer.php';
?>