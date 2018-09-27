<?php

/*****************************************************************
 Table Definition. Use the following SQL to (re)create the table.

 CREATE TABLE `ks_news` (
 `ns_id` int(11) NOT NULL AUTO_INCREMENT,
 `ns_title` varchar(100) DEFAULT NULL,
 `ns_desc` text,
 `ns_start_date` date NOT NULL DEFAULT '0000-00-00',
 `ns_end_date` date NOT NULL DEFAULT '0000-00-00',
 `ns_public` int(1) DEFAULT NULL,
 `ns_private` int(1) DEFAULT NULL,
 `ns_sender` varchar(32) DEFAULT NULL,
 `ns_receiver` varchar(32) DEFAULT NULL,
 `ns_user_read` longtext,
 `ns_option` longtext,
 `ns_status` int(1) DEFAULT NULL,
 `ns_created_by` varchar(25) DEFAULT NULL,
 `ns_modified_by` varchar(25) DEFAULT NULL,
 `ns_created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `ns_modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`ns_id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=26

 *****************************************************************/
require_once 'KS/Search.php';
class KS_News extends KS_Search {
	const STATUS_ACTIVE = 1;
	const STATUS_DISABLED = 0;
	protected $sqlTable = 'ks_news';
	protected $id;
	protected $title;
	protected $desc;
	protected $startDate;
	protected $endDate;
	protected $public;
	protected $private;
	protected $sender;
	protected $receiver;
	protected $userRead;
	protected $option;
	protected $status;
	protected $createdBy;
	protected $modifiedBy;
	protected $createdDate;
	protected $modifiedDate;
	
	// $this->search() related properties..
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'ns_id';
	
	// this public variable holds the search result in associative array
	// to use it, use: $['usr_email']
	public $searchResultAssociativeArray = '';
	public function __construct() {
		try {
			// global $ks_db;
			global $ks_log;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns a single record based on primary key ns_id
	 *
	 * @param
	 *        	none
	 * @return true
	 */
	public function select() {
		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Primary key Id not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// count how many rows found
			if ($this->exists ()) {
				
				$sql = "SELECT * FROM $this->sqlTable WHERE ns_id = ? ";
				
				$stmt = $ks_db->query ( $sql, array (
						$this->id 
				) );
				
				// record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['ns_id'];
					$this->title = $row ['ns_title'];
					$this->desc = $row ['ns_desc'];
					$this->startDate = $row ['ns_start_date'];
					$this->endDate = $row ['ns_end_date'];
					$this->public = $row ['ns_public'];
					$this->private = $row ['ns_private'];
					$this->sender = $row ['ns_sender'];
					$this->receiver = $row ['ns_receiver'];
					$this->userRead = $row ['ns_user_read'];
					$this->option = $row ['ns_option'];
					$this->status = $row ['ns_status'];
					$this->createdBy = $row ['ns_created_by'];
					$this->modifiedBy = $row ['ns_modified_by'];
					$this->createdDate = $row ['ns_created_date'];
					$this->modifiedDate = $row ['ns_modified_date'];
				}
			} else {
				echo "No record found with primary key (Id) from table ($this->sqlTable).";
			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method checks if a row exists based on the supplied primary key ns_id
	 *
	 * @param
	 *        	none
	 * @return true if record found, false if not found
	 */
	public function exists() {
		try {
			global $ks_db;
			global $ks_log;
			
			$bReturn = false;
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Primary key Id not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE ns_id = ? ";
			
			// count how many rows found
			$totalRow = $ks_db->fetchOne ( $sql, array (
					$this->id 
			) );
			
			if ($totalRow > 0) {
				$bReturn = true;
			} else {
				$bReturn = false;
			}
			
			return $bReturn;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method inserts a single record
	 *
	 * @param
	 *        	none
	 */
	public function insert() {
		try {
			global $ks_db;
			global $ks_log;
			
			$ks_db->beginTransaction ();
			
			$arrBindings = array ();
			$insertCols = '';
			$insertVals = '';
				
			if (isset ( $this->title )) {
				$insertCols .= "ns_title, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->title;
			}
			if (isset ( $this->desc )) {
				$insertCols .= "ns_desc, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->desc;
			}
			if (isset ( $this->startDate )) {
				$insertCols .= "ns_start_date, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->startDate;
			}
			if (isset ( $this->endDate )) {
				$insertCols .= "ns_end_date, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->endDate;
			}
			if (isset ( $this->public )) {
				$insertCols .= "ns_public, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->public;
			}
			if (isset ( $this->private )) {
				$insertCols .= "ns_private, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->private;
			}
			if (isset ( $this->sender )) {
				$insertCols .= "ns_sender, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->sender;
			}
			if (isset ( $this->receiver )) {
				$insertCols .= "ns_receiver, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->receiver;
			}
			if (isset ( $this->userRead )) {
				$insertCols .= "ns_user_read, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->userRead;
			}
			if (isset ( $this->option )) {
				$insertCols .= "ns_option, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->option;
			}
			if (isset ( $this->status )) {
				$insertCols .= "ns_status, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->status;
			}
			if (isset ( $this->createdBy )) {
				$insertCols .= "ns_created_by, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->createdBy;
			}
			if (isset ( $this->modifiedBy )) {
				$insertCols .= "ns_modified_by, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->modifiedBy;
			}
			if (isset ( $this->createdDate )) {
				$insertCols .= "ns_created_date, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->createdDate;
			}
			if (isset ( $this->modifiedDate )) {
				$insertCols .= "ns_modified_date, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->modifiedDate;
			}
			
			// remove trailing commas
			$insertCols = preg_replace ( "/, $/", "", $insertCols );
			$insertVals = preg_replace ( "/, $/", "", $insertVals );
			
			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";
			
			$ks_db->query ( $sql, $arrBindings );
			
			// set the auto-increment property, only if the primary column is auto-increment
			$this->id = $ks_db->lastInsertId ();
			
			$ks_db->commit ();
		} catch ( Exception $e ) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method updates a single record, based on primary key ns_id
	 *
	 * @param
	 *        	none
	 */
	public function update() {
		try {
			global $ks_db;
			global $ks_log;
			
			$ks_db->beginTransaction ();
			
			$arrBindings = array ();
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Primary key Id not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// check if record exists
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with primary key (Id) from table ($this->sqlTable).";
				exit ();
			}
			
			$sql = "UPDATE $this->sqlTable SET ";
			
			if (isset ( $this->title )) {
				$sql .= "ns_title = ?, ";
				$arrBindings [] = $this->title;
			}
			if (isset ( $this->desc )) {
				$sql .= "ns_desc = ?, ";
				$arrBindings [] = $this->desc;
			}
			if (isset ( $this->startDate )) {
				$sql .= "ns_start_date = ?, ";
				$arrBindings [] = $this->startDate;
			}
			if (isset ( $this->endDate )) {
				$sql .= "ns_end_date = ?, ";
				$arrBindings [] = $this->endDate;
			}
			if (isset ( $this->public )) {
				$sql .= "ns_public = ?, ";
				$arrBindings [] = $this->public;
			}
			if (isset ( $this->private )) {
				$sql .= "ns_private = ?, ";
				$arrBindings [] = $this->private;
			}
			if (isset ( $this->sender )) {
				$sql .= "ns_sender = ?, ";
				$arrBindings [] = $this->sender;
			}
			if (isset ( $this->receiver )) {
				$sql .= "ns_receiver = ?, ";
				$arrBindings [] = $this->receiver;
			}
			if (isset ( $this->userRead )) {
				$sql .= "ns_user_read = ?, ";
				$arrBindings [] = $this->userRead;
			}
			if (isset ( $this->option )) {
				$sql .= "ns_option = ?, ";
				$arrBindings [] = $this->option;
			}
			if (isset ( $this->status )) {
				$sql .= "ns_status = ?, ";
				$arrBindings [] = $this->status;
			}
			if (isset ( $this->createdBy )) {
				$sql .= "ns_created_by = ?, ";
				$arrBindings [] = $this->createdBy;
			}
			if (isset ( $this->modifiedBy )) {
				$sql .= "ns_modified_by = ?, ";
				$arrBindings [] = $this->modifiedBy;
			}
			if (isset ( $this->createdDate )) {
				$sql .= "ns_created_date = ?, ";
				$arrBindings [] = $this->createdDate;
			}
			if (isset ( $this->modifiedDate )) {
				$sql .= "ns_modified_date = ?, ";
				$arrBindings [] = $this->modifiedDate;
			}
			
			$sql = preg_replace ( '/, $/', '', $sql );
			$sql .= " WHERE ns_id = ? ";
			
			$arrBindings [] = $this->id;
			
			$ks_db->query ( $sql, $arrBindings );
			
			$ks_db->commit ();
		} catch ( Exception $e ) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method deletes a single record, based on primary key ns_id
	 *
	 * @param
	 *        	none
	 */
	public function delete() {
		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Primary key Id not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// check if record exists
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with primary key (Id) from table ($this->sqlTable).";
				exit ();
			}
			
			$ks_db->beginTransaction ();
			
			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE ns_id = ? ";
			
			$ks_db->query ( $sql, array (
					$this->id 
			) );
			
			$ks_db->commit ();
		} catch ( Exception $e ) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method perform search based on this object.
	 *
	 * @param
	 *        	none
	 * @return array $className
	 */
	public function search() {
		try {
			global $ks_db;
			global $ks_log;
			
			$arrSearchResults = array ();
			
			parent::initSearch ();
			
			$stmt = $ks_db->query ( $this->searchSQL, $this->searchBinding );
			
			// record is found, associate columns to the object properties
			while ( true == ($row = $stmt->fetch ()) ) {
				
				$objResult = new KS_News ();
				
				$objResult->id = $row ['ns_id'];
				$objResult->title = $row ['ns_title'];
				$objResult->desc = $row ['ns_desc'];
				$objResult->startDate = $row ['ns_start_date'];
				$objResult->endDate = $row ['ns_end_date'];
				$objResult->public = $row ['ns_public'];
				$objResult->private = $row ['ns_private'];
				$objResult->sender = $row ['ns_sender'];
				$objResult->receiver = $row ['ns_receiver'];
				$objResult->userRead = $row ['ns_user_read'];
				$objResult->option = $row ['ns_option'];
				$objResult->status = $row ['ns_status'];
				$objResult->createdBy = $row ['ns_created_by'];
				$objResult->modifiedBy = $row ['ns_modified_by'];
				$objResult->createdDate = $row ['ns_created_date'];
				$objResult->modifiedDate = $row ['ns_modified_date'];
				
				$arrSearchResults [] = $objResult;
				
				$objResult->searchResultAssociativeArray = $row;
			}
			
			return $arrSearchResults;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 *
	 * @return int(11)
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @return varchar(100)
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 *
	 * @return text
	 */
	public function getDesc() {
		return $this->desc;
	}
	
	/**
	 *
	 * @return date
	 */
	public function getStartDate() {
		return $this->startDate;
	}
	
	/**
	 *
	 * @return date
	 */
	public function getEndDate() {
		return $this->endDate;
	}
	
	/**
	 *
	 * @return int(1)
	 */
	public function getPublic() {
		return $this->public;
	}
	
	/**
	 *
	 * @return int(1)
	 */
	public function getPrivate() {
		return $this->private;
	}
	
	/**
	 *
	 * @return varchar(32)
	 */
	public function getSender() {
		return $this->sender;
	}
	
	/**
	 *
	 * @return varchar(32)
	 */
	public function getReceiver() {
		return $this->receiver;
	}
	
	/**
	 *
	 * @return longtext
	 */
	public function getUserRead() {
		return $this->userRead;
	}
	
	/**
	 *
	 * @return longtext
	 */
	public function getOption() {
		return $this->option;
	}
	
	/**
	 *
	 * @return int(1)
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 *
	 * @return varchar(25)
	 */
	public function getCreatedBy() {
		return $this->createdBy;
	}
	
	/**
	 *
	 * @return varchar(25)
	 */
	public function getModifiedBy() {
		return $this->modifiedBy;
	}
	
	/**
	 *
	 * @return datetime
	 */
	public function getCreatedDate() {
		return $this->createdDate;
	}
	
	/**
	 *
	 * @return datetime
	 */
	public function getModifiedDate() {
		return $this->modifiedDate;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSearchSortField() {
		return $this->searchSortField;
	}
	
	/**
	 *
	 * @param
	 *        	int(11) id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 *
	 * @param
	 *        	varchar(100) title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 *
	 * @param
	 *        	text desc
	 */
	public function setDesc($desc) {
		$this->desc = $desc;
	}
	
	/**
	 *
	 * @param
	 *        	date startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	
	/**
	 *
	 * @param
	 *        	date endDate
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	
	/**
	 *
	 * @param
	 *        	int(1) public
	 */
	public function setPublic($public) {
		$this->public = $public;
	}
	
	/**
	 *
	 * @param
	 *        	int(1) private
	 */
	public function setPrivate($private) {
		$this->private = $private;
	}
	
	/**
	 *
	 * @param
	 *        	varchar(32) sender
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}
	
	/**
	 *
	 * @param
	 *        	varchar(32) receiver
	 */
	public function setReceiver($receiver) {
		$this->receiver = $receiver;
	}
	
	/**
	 *
	 * @param
	 *        	longtext userRead
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}
	
	/**
	 *
	 * @param
	 *        	longtext option
	 */
	public function setOption($option) {
		$this->option = $option;
	}
	
	/**
	 *
	 * @param
	 *        	int(1) status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
	
	/**
	 *
	 * @param
	 *        	varchar(25) createdBy
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
	}
	
	/**
	 *
	 * @param
	 *        	varchar(25) modifiedBy
	 */
	public function setModifiedBy($modifiedBy) {
		$this->modifiedBy = $modifiedBy;
	}
	
	/**
	 *
	 * @param
	 *        	datetime createdDate
	 */
	public function setCreatedDate($createdDate) {
		$this->createdDate = $createdDate;
	}
	
	/**
	 *
	 * @param
	 *        	datetime modifiedDate
	 */
	public function setModifiedDate($modifiedDate) {
		$this->modifiedDate = $modifiedDate;
	}
	/**
	 *
	 * @param string $searchSortField        	
	 */
	public function setSearchSortField($searchSortField) {
		$this->searchSortField = $searchSortField;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getSearchResultAssociativeArray() {
		return $this->searchResultAssociativeArray;
	}
	
	/**
	 * List news available for viewing
	 * @param unknown $count
	 * @return Ambigous <multitype:, mixed>
	 */
	public static function listNews($count = 100) {
		try {
			global $ks_db;
			global $ks_log;
			
			$arrListNews = array ();
			
			$isAuth = CUSTOM_User::checkAuthentication ();
			if ($isAuth == 1) {
				$wherePublic = " AND ns_private = '1' ";
			} else {
				$wherePublic = " AND ns_public = '1'";
			}
			
			$curdate = date ( "Y-m-d" );
			
			//ns_status = 1(publish), 0 = (dont publish)
			$sqlStatementWhere = " AND ns_status = 1
					AND (ns_start_date <= '$curdate')
					AND ((ns_end_date >= '$curdate') OR (ns_end_date = '0000-00-00'))
					$wherePublic
			";
			
			$sql = "SELECT * FROM ks_news WHERE 1 $sqlStatementWhere ORDER BY ns_start_date DESC LIMIT $count";
			
			$stmt = $ks_db->query ( $sql );
			while ( true == ($row = $stmt->fetch ()) ) {
				
				$id = $row ['ns_id'];
				
				$arrListNews ['data'] [$id] ['id'] = $row ['ns_id'];
				$arrListNews ['data'] [$id] ['title'] = $row ['ns_title'];
				$arrListNews ['data'] [$id] ['desc'] = $row ['ns_desc'];
				$arrListNews ['data'] [$id] ['startdate'] = $row ['ns_start_date'];
				$arrListNews ['data'] [$id] ['enddate'] = $row ['ns_end_date'];
				$arrListNews ['data'] [$id] ['public'] = $row ['ns_public'];
				$arrListNews ['data'] [$id] ['private'] = $row ['ns_private'];
				$arrListNews ['data'] [$id] ['sender'] = $row ['ns_sender'];
				$arrListNews ['data'] [$id] ['receiver'] = $row ['ns_receiver'];
				$arrListNews ['data'] [$id] ['status'] = $row ['ns_status'];
				$arrListNews ['data'] [$id] ['userread'] = $row ['ns_user_read'];
			}
			
			// cho "<pre>";
			// rint_r ( $arrListNews );
			
			return $arrListNews;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
}

