<?php
$ks_scriptname = basename ( $_SERVER ['SCRIPT_NAME'], ".php" );
?>
<ul class="breadcrumb">
	<li><a href="list.php"><i class="glyphicon glyphicon-cog"></i> Option</a>
	</li>
	<?php if($ks_scriptname != 'add') {?>
		<li class="dropdown"><a class="dropdown-toggle" id="ks_table"
			data-toggle="dropdown">Option <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="list.php">All Option</a></li>
			<li><a href="add.php?tabId=1">Add Option</a></li>
		</ul>
		</li>
	<?php } if($ks_scriptname == 'modify'){?>
		<li class="active">Modify Option</li>
	<?php } elseif($ks_scriptname == 'add') {?>
		<li class="active">Add Option</li>
	<?php } else {?>
		<li class="active">Properties</li>
	<?php }?>
</ul>
