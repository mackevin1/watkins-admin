<?php

require_once 'KS/Search.php';
	
class KS_Acl_Resource extends KS_Search { 

	protected $sqlTable = 'ks_acl_resource';
	
	protected $id;
	protected $parentid;
	protected $privilegeid;
	protected $desc;

	// $this->search() related properties.. 
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'res_parentid';

	//this public variable holds the search result in associative array
	//to use it, use: $['usr_email']
	public $searchResultAssociativeArray = '';
		

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
			
				$sql = "SELECT * FROM $this->sqlTable WHERE res_parentid = ?";
	
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['res_id'];
					$this->parentid = $row ['res_parentid'];
					$this->privilegeid = $row ['res_privilegeid'];
					$this->desc = $row ['res_desc'];

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

			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE res_parentid = ?";

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
			
			if (isset ( $this->id )) {
				$insertCols .= "res_id, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->id;
			}
			if (isset ( $this->privilegeid )) {
				$insertCols .= "res_privilegeid, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->privilegeid;
			}
			if (isset ( $this->desc )) {
				$insertCols .= "res_desc, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->desc;
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
			
			if (isset ( $this->id )) {
				$sql .= "res_id = ?, ";
				$arrBindings[] = $this->id;
			}
			if (isset ( $this->privilegeid )) {
				$sql .= "res_privilegeid = ?, ";
				$arrBindings[] = $this->privilegeid;
			}
			if (isset ( $this->desc )) {
				$sql .= "res_desc = ?, ";
				$arrBindings[] = $this->desc;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE res_parentid = ?";
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
			$sql .= " WHERE res_parentid = ?";

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
	 * This method perform search based on this object.
	 *
	 * @param none
	 * @return array $class
	 */
	public function search() {

		try {
			global $ks_db;
			global $ks_log;

			parent::initSearch ();
			
			$stmt = $ks_db->query ( $this->searchSQL, $this->searchBinding );

			//record is found, associate columns to the object properties
			while ( true == ($row = $stmt->fetch ()) ) {
				
				$objResult = new KS_Acl_Resource ( );
				
				$objResult->id = $row ['res_id'];
				$objResult->parentid = $row ['res_parentid'];
				$objResult->privilegeid = $row ['res_privilegeid'];
				$objResult->desc = $row ['res_desc'];
				
				$arrSearchResults [] = $objResult;
				
				$objResult->searchResultAssociativeArray = $row;
				
			}
			
			return $arrSearchResults;

		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}

	/**
	 * @return varchar(32)
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return varchar(32)
	 */
	public function getParentid(){
		return $this->parentid;
	}

	/**
	 * @return varchar(64)
	 */
	public function getPrivilegeid(){
		return $this->privilegeid;
	}

	/**
	 * @return text
	 */
	public function getDesc(){
		return $this->desc;
	}
	
	/**
	 * @return string
	 */
	public function getSearchSortField() {
		return $this->searchSortField;
	}

	/**
	 * @param varchar(32) id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param varchar(32) parentid
	 */
	public function setParentid($parentid) {
		$this->parentid = $parentid;
	}

	/**
	 * @param varchar(64) privilegeid
	 */
	public function setPrivilegeid($privilegeid) {
		$this->privilegeid = $privilegeid;
	}

	/**
	 * @param text desc
	 */
	public function setDesc($desc) {
		$this->desc = $desc;
	}
	/**
	 * @param string $searchSortField
	 */
	public function setSearchSortField($searchSortField) {
		$this->searchSortField = $searchSortField;
	}

	/**
	 * @return array
	 */
	public function getSearchResultAssociativeArray() {
		return $this->searchResultAssociativeArray;
	}	
	
}

?>