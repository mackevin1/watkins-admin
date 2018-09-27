<?php

/*****************************************************************
Table Definition. Use the following SQL to (re)create the table.

CREATE TABLE `ks_lostpassword` (
  `lp_id` int(11) unsigned NOT NULL auto_increment,
  `lp_userid` varchar(50) NOT NULL default '',
  `lp_random` int(8) NOT NULL default '0',
  `lp_deadline` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`lp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1

*****************************************************************/

class KS_Lostpassword  { 

	protected $sqlTable = 'ks_lostpassword';
	
	protected $id;
	protected $userid;
	protected $random;
	protected $deadline;

	public function __construct() {
		try {
			//global $ks_db;
			global $ks_log;
			
		}catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}

	/**
	 * This method returns a single record based on primary key $id
	 *
	 * @param none
	 * @return true
	 */
	public function select(){

		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			//count how many rows found
			if($this->exists ()) {
			
				$sql = "SELECT * FROM $this->sqlTable WHERE lp_id = ?";
	
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['lp_id'];
					$this->userid = $row ['lp_userid'];
					$this->random = $row ['lp_random'];
					$this->deadline = $row ['lp_deadline'];

				}

			} else {
				echo "No record found with id ($this->id) from table ($this->sqlTable).";
			}	

		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method check whether the supplied primary key $id exists or not
	 *
	 * @param none
	 * @return true if record found, false if not found
	 */
	public function exists(){

		try {
			global $ks_db;
			global $ks_log;
			
			$bReturn = false;
			
			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE lp_id = ?";

			//count how many rows found
			$totalRow = $ks_db->fetchOne ( $sql, $this->id );
			
			if ($totalRow > 0) {
				$bReturn = true;
			}else {
				$bReturn = false;
			}
			
			return $bReturn;
			
		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This method inserts a single record
	 *
	 * @param none
	 */
	public function insert() {

		try {
			global $ks_db;
			global $ks_log;

			$ks_db->beginTransaction ();
			
			$arrBindings = array ();
			$insertCols = '';
			$insertVals = '';
			
			if (isset ( $this->userid )) {
				$insertCols .= "lp_userid, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->userid;
			}
			if (isset ( $this->random )) {
				$insertCols .= "lp_random, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->random;
			}
			if (isset ( $this->deadline )) {
				$insertCols .= "lp_deadline, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->deadline;
			}
			
			//remove trailing commas
			$insertCols = preg_replace("/, $/", "", $insertCols);
			$insertVals = preg_replace("/, $/", "", $insertVals);
			
			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";
			
			$ks_db->query ( $sql, $arrBindings );

			//set the id property
			$this->id = $ks_db->lastInsertId();
			
			$ks_db->commit ();
			
		} catch(Exception $e) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This method updates a single record, based on $id
	 *
	 * @param none
	 */
	public function update() {

		try {
			global $ks_db;
			global $ks_log;

			$ks_db->beginTransaction ();
			
			$arrBindings = array ();
			
			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with id of ($this->id)";
				exit ();
			}

			$sql = "UPDATE $this->sqlTable SET ";
			
			if (isset ( $this->userid )) {
				$sql .= "lp_userid = ?, ";
				$arrBindings[] = $this->userid;
			}
			if (isset ( $this->random )) {
				$sql .= "lp_random = ?, ";
				$arrBindings[] = $this->random;
			}
			if (isset ( $this->deadline )) {
				$sql .= "lp_deadline = ?, ";
				$arrBindings[] = $this->deadline;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE lp_id = ?";
			$arrBindings[] = $this->id;
			
			$ks_db->query ( $sql, $arrBindings );
	
			$ks_db->commit ();
			
		} catch(Exception $e) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This method deletes a single record, based on $id
	 *
	 * @param none
	 */
	public function delete() {

		try {
			global $ks_db;
			global $ks_log;

			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with id of ($this->id)";
				exit ();
			}

			$ks_db->beginTransaction ();

			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE lp_id = ?";

			$ks_db->query ( $sql, $this->id );

			$ks_db->commit ();

		} catch(Exception $e) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * @return int(11) unsigned
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return varchar(50)
	 */
	public function getUserid(){
		return $this->userid;
	}

	/**
	 * @return int(8)
	 */
	public function getRandom(){
		return $this->random;
	}

	/**
	 * @return datetime
	 */
	public function getDeadline(){
		return $this->deadline;
	}

	/**
	 * @param int(11) unsigned id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param varchar(50) userid
	 */
	public function setUserid($userid) {
		$this->userid = $userid;
	}

	/**
	 * @param int(8) random
	 */
	public function setRandom($random) {
		$this->random = $random;
	}

	/**
	 * @param datetime deadline
	 */
	public function setDeadline($deadline) {
		$this->deadline = $deadline;
	}
}

