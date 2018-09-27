<?php

class KS_Date {
	
	/**
	 *This function accepts MYSQL date input and produces output in d/M/Y format
	 * @param $value in YYYY-MM-DD
	 */
	public static function toDD_MM_YYYY($value) {
		
		$dateformat = "d/m/Y";
		$timeformat = "H:i:s";
		$ampm = " A";
		
		$date_isvalid = preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}/", $value );
		if ($value != "0000-00-00") {
			$date_nonzero = 1;
		}
		$time_specified = preg_match ( "/[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $value );
		if ($value != "00:00:00") {
			$time_nonzero = 1;
		}
		
		$values = explode ( " ", $value );
		
		if ($date_isvalid && $date_nonzero) { //$value is something valid and nonzero
			

			//handling data formatting 	
			$dates = explode ( "-", $values [0] );
			$year = ( int ) $dates [0];
			$mon = ( int ) $dates [1];
			$day = ( int ) $dates [2];
			
			if ($time_specified && $time_nonzero) {
				$times = explode ( ":", $values [1] );
				
				$hour = ( int ) $times [0];
				$min = ( int ) $times [1];
				$sec = ( int ) $times [2];
				
				$thevalue = mktime ( $hour, $min, $sec, $mon, $day, $year );
				
				$output = $dateformat . " " . $timeformat . $ampm;
				$value_new = date ( "$output", $thevalue );
				
				$display_item = "$value_new";
			
			} else {
				
				$thevalue = mktime ( 0, 0, 0, $mon, $day, $year );
				
				$output = $dateformat;
				$value_new = date ( "$output", $thevalue );
				
				$display_item = "$value_new";
			
			}
		} else { //date is zero
			$display_item = "";
		}
		
		return $display_item;
	}
	
	/**
	 *This function converts d/m/Y [H:i:s] date into MySQL date YYYY-MM-DD
	 * @param $date DD/MM/YYYY
	 * @return YYYY-MM-DD mysql date
	 */
	public static function toYYYY_MM_DD($date) {
		
		$val = "0000-00-00";
		
		if (isset ($date )) {
			if (preg_match ( "|^[0-9]{2}/[0-9]{2}/[0-9]{4}( [0-9]{2}:[0-9]{2}:[0-9]{2})*|", $date )) {
				
				//split by space
				$tmpDatetimeSplit = explode ( " ", $date );
				$valDate = $tmpDatetimeSplit [0];
				
				$valTime = '';
				if (isset ($tmpDatetimeSplit [1])) {
					$valTime = $tmpDatetimeSplit [1];
				}
				
				$vals = explode ( "/", $valDate );
				$year = $vals [2];
				$mon = $vals [1];
				$day = $vals [0];
				$val = "$year-$mon-$day";
				
				if (preg_match ( "/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $valTime )) {
					$vals2 = explode ( ":", $valTime );
					$hour = $vals2 [0];
					$minute = $vals2 [1];
					$second = $vals2 [2];
					$val = "$val $hour:$minute:$second";
				}
			}
		}
		return $val;
	}
}

?>