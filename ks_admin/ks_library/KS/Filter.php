<?php
class KS_Filter {

	/**
	 * This method sanitizes input
	 *
	 * @param unknown_type $input
	 */
	public static function inputSanitize($input) {
		if (is_array ( $input )) {
			return $input;
		} else {
		
			$input = trim ( $input );
			$input = strip_tags ( $input );
			$input = htmlspecialchars ( $input );
			$input = str_replace(array('\r', '\n'), '', $input); //remove newline from string
			return $input;
		}
	}
}
