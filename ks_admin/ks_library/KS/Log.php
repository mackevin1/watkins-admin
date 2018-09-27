<?php

class KS_Log extends Zend_Log {
	
	/**
	 * This method rotates the file.
	 *
	 */
	public function rotate() {
		try {
			global $ks_log;
			global $ks_logstream;
			
			$max_size = 100000000;
			
			//code to get the file size 
			$filesize = filesize ( KSCONFIG_ERROR_LOG );
			
			//Checking the file size 
			if ($filesize >= $max_size) {
				
				//close previous file handle 
				fclose ( $ks_logstream );
				
				//make backup of that file 
				if (true != rename ( KSCONFIG_ERROR_LOG, KSCONFIG_CACHE_BACKUP_PATH . time () . '_error.txt' )) {
					if (! file_exists ( KSCONFIG_CACHE_BACKUP_PATH )) {
						die ( "Fatal Error: Cannot rotate log file. Please contact System Administrator. Error: 'Log archive directory not found, set to " . KSCONFIG_CACHE_BACKUP_PATH . "'." );
					} else {
						die ( "Fatal Error: Cannot rotate log file. Please contact System Administrator." );
					
					}
				}
				
				#create a new file .
				if (true != ($ks_logstream = fopen ( KSCONFIG_ERROR_LOG, 'a+', false ))) {
					die ( "Fatal Error: Cannot open log file. Please contact System Administrator." );
				}
				
				fclose ( $ks_logstream );
			}
		
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
}

?>