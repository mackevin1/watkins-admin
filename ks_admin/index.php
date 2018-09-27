<?php

if (file_exists ( '../config.php' )) {
	header ( "Location: controlpanel.php" );
} else {
	header ( "Location: admin-install/" );
}