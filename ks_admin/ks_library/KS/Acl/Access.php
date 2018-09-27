<?php

class KS_Acl_Access { 

	protected $sqlTable = 'ks_acl_access';
	
	protected $roleid;
	protected $resid;
	protected $privilegeid;
	protected $allow;

	// $this->search() related properties
	protected $arraySearchFields = array ();
	protected $searchSqlWhere = '';
	protected $searchRowCount = 0;
	protected $searchRecordsPerPage = 10;
	protected $searchSortOrder = 'ASC';
	protected $searchSortField = 'acc_roleid';
	protected $searchPageStart = 0;

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
			
				$sql = "SELECT * FROM $this->sqlTable WHERE acc_roleid = ?";
	
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
				$this->roleid = $row ['acc_roleid'];
				$this->resid = $row ['acc_resid'];
				$this->privilegeid = $row ['acc_privilegeid'];
				$this->allow = $row ['acc_allow'];
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
			
			if (! isset ( $this->roleid )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			$sql = "SELECT COUNT(*) FROM $this->sqlTable WHERE acc_roleid = ?";

			$result = $ks_db->query ( $sql, $this->roleid );
			
			//count how many rows found
			$totalRows = $result->fetchColumn();
			
			if ($totalRows > 0) {
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

			
			if (isset ( $this->roleid )) {
				$insertCols .= "acc_roleid, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->roleid;
			}
			if (isset ( $this->resid )) {
				$insertCols .= "acc_resid, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->resid;
			}
			if (isset ( $this->privilegeid )) {
				$insertCols .= "acc_privilegeid, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->privilegeid;
			}
			if (isset ( $this->allow )) {
				$insertCols .= "acc_allow, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->allow;
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
			
			if (isset ( $this->resid )) {
				$sql .= "acc_resid = ?, ";
				$arrBindings[] = $this->resid;
			}
			if (isset ( $this->privilegeid )) {
				$sql .= "acc_privilegeid = ?, ";
				$arrBindings[] = $this->privilegeid;
			}
			if (isset ( $this->allow )) {
				$sql .= "acc_allow = ?, ";
				$arrBindings[] = $this->allow;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE acc_roleid = ?";
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

			if (! isset ( $this->roleid )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}

			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with id of ($this->roleid)";
				exit ();
			}

			$ks_db->beginTransaction ();

			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE acc_roleid = ?";

			$ks_db->query ( $sql, $this->roleid );

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

			//store found objects as an array collection of objects
			//in this case, array of KS_Acl_Access
			$arrSearchResults = array ();
			
			//store found records as an array collection of objects, in this case, array of QS_Application
			$arrSearchResults = array ();
			
			//if sqlWhere clause is specified, use it
			if (trim ( $this->searchSqlWhere ) != '') {
				
				//remove the word WHERE, just in case specified
				$this->searchSqlWhere = preg_replace ( '/WHERE/', '', trim ( $this->searchSqlWhere ) );
				
				$sql = 'SELECT * FROM ' . $this->sqlTable;
				$sql .= ' WHERE ' . $this->searchSqlWhere;
			
			} else {
				//we use $this->arraySearchFields property
				if (! is_array ( $this->arraySearchFields )) {
					return $arrSearchResults;
				}

				$searchClause .= "1 ";
				
				//this array holds ignored values. maybe field is specified as SearchField but matches all 
				//(won't be included in where clause)
				$arrIgnoreValues = array ();
				$arrIgnoreValues [] = '';
				$arrIgnoreValues [] = '%';
				$arrIgnoreValues [] = '%%';
				$arrIgnoreValues [] = '0000-00-00';
				$arrIgnoreValues [] = '0000-00-00 00:00:00';
				$arrIgnoreValues [] = NULL;
				
				foreach ( $this->arraySearchFields as $curSearchFieldObject ) {
					$searchField = $curSearchFieldObject->getField ();
					$searchValue = $curSearchFieldObject->getValue ();
					$searchOperator = $curSearchFieldObject->getOperator ();
					
					if (in_array ( $searchValue, $arrIgnoreValues )) {
					
					} else {
						$searchClause .= " AND $searchField $searchOperator ? ";
						$searchBinding [] = $searchValue;
					}
				}
				
				$sql = "SELECT * FROM " . $this->sqlTable;
				$sql .= " WHERE $searchClause";
			}
			
			//we need to know in total, how many (before we limit the return by LIMIT )??
			$sqlTotal = $sql;
			
			
			if (trim ( $this->searchSortField )) {
				$sql .= " ORDER BY {$this->searchSortField} {$this->searchSortOrder} ";
			}
			
			$pageStart = $this->searchPageStart * $this->searchRecordsPerPage;
			
			$sql .= " LIMIT $pageStart, {$this->searchRecordsPerPage}";
			
			$stmt = $ks_db->query ( $sql, $searchBinding );
			
			$stmtTotal = $ks_db->query ( $sqlTotal, $searchBinding );
			
			//count how many rows found
			$this->searchRowCount = $stmtTotal->rowCount ();
			
			//record is found, associate columns to the object properties
			while ( true == ($row = $stmt->fetch ()) ) {
				
				$objResult = new KS_Acl_Access ( );
				
				$objResult->roleid = $row ['acc_roleid'];
				$objResult->resid = $row ['acc_resid'];
				$objResult->privilegeid = $row ['acc_privilegeid'];
				$objResult->allow = $row ['acc_allow'];
				
				$arrSearchResults [] = $objResult;
			}
			
			return $arrSearchResults;

		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * @return varchar(32)
	 */
	public function getRoleid(){
		return $this->roleid;
	}

	/**
	 * @return varchar(32)
	 */
	public function getResid(){
		return $this->resid;
	}

	/**
	 * @return varchar(64)
	 */
	public function getPrivilegeid(){
		return $this->privilegeid;
	}

	/**
	 * @return tinyint(1)
	 */
	public function getAllow(){
		return $this->allow;
	}
	/**
	 * @return array
	 */
	public function getArraySearchFields() {
		return $this->arraySearchFields;
	}
	
	/**
	 * @return int
	 */
	public function getSearchPageStart() {
		return $this->searchPageStart;
	}
	
	/**
	 * @return int
	 */
	public function getSearchRecordsPerPage() {
		return $this->searchRecordsPerPage;
	}
	
	/**
	 * @return int
	 */
	public function getSearchRowCount() {
		return $this->searchRowCount;
	}
	
	/**
	 * @return string
	 */
	public function getSearchSortField() {
		return $this->searchSortField;
	}
	
	/**
	 * @return string
	 */
	public function getSearchSortOrder() {
		return $this->searchSortOrder;
	}
	
	/**
	 * @return string
	 */
	public function getSearchSqlWhere() {
		return $this->searchSqlWhere;
	}
	
	/**
	 * @return string
	 */
	public function getSqlTable() {
		return $this->sqlTable;
	}

	/**
	 * @param varchar(32) roleid
	 */
	public function setRoleid($roleid) {
		$this->roleid = $roleid;
	}

	/**
	 * @param varchar(32) resid
	 */
	public function setResid($resid) {
		$this->resid = $resid;
	}

	/**
	 * @param varchar(64) privilegeid
	 */
	public function setPrivilegeid($privilegeid) {
		$this->privilegeid = $privilegeid;
	}

	/**
	 * @param tinyint(1) allow
	 */
	public function setAllow($allow) {
		$this->allow = $allow;
	}
	/**
	 * @param array $arraySearchFields
	 */
	public function setArraySearchFields($arraySearchFields) {
		$this->arraySearchFields[] = $arraySearchFields;
	}
	
	/**
	 * @param int $searchPageStart
	 */
	public function setSearchPageStart($searchPageStart) {
		$this->searchPageStart = $searchPageStart;
	}
	
	/**
	 * @param int $searchRecordsPerPage
	 */
	public function setSearchRecordsPerPage($searchRecordsPerPage) {
		$this->searchRecordsPerPage = $searchRecordsPerPage;
	}
	
	/**
	 * @param int $searchRowCount
	 */
	public function setSearchRowCount($searchRowCount) {
		$this->searchRowCount = $searchRowCount;
	}
	
	/**
	 * @param string $searchSortField
	 */
	public function setSearchSortField($searchSortField) {
		$this->searchSortField = $searchSortField;
	}
	
	/**
	 * @param string $searchSortOrder
	 */
	public function setSearchSortOrder($searchSortOrder) {
		$this->searchSortOrder = $searchSortOrder;
	}
	
	/**
	 * @param string $searchSqlWhere
	 */
	public function setSearchSqlWhere($searchSqlWhere) {
		$this->searchSqlWhere = $searchSqlWhere;
	}
	
	/**
	 * @param string $sqlTable
	 */
	public function setSqlTable($sqlTable) {
		$this->sqlTable = $sqlTable;
	}
}

?>