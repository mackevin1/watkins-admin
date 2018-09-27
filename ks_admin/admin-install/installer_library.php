<?php

function inputSanitize($input) {
	if (is_array ( $input )) {
		$input = $input [0];
	}
	
	$input = trim ( $input );
	$input = strip_tags ( $input );
	$input = htmlspecialchars ( $input );
	return $input;
}

//identify config file location
$config_file = realpath(__DIR__ . '/../..') . DIRECTORY_SEPARATOR . "config.php";

//get version 
$version = file_get_contents('../version.txt');

/* installer title*/
$installerTitle = 'Dynamic Admin Panel Installer <br/><small>Version ' . $version . '</small>';