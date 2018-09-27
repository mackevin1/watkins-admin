<?php

if (! is_file ( '../../config.php' )) {
	header ( "Location: installer.php" );
	exit ();
} else {
	header ( "Location: upgrade.php" );
	exit ();
}
