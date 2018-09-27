<?php
require_once 'KS/Search.php';

/*****************************************************************
Table Definition. Use the following SQL to (re)create the table.

CREATE TABLE `ks_controlpanel_menu` (
  `cpm_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cpm_parentid` int(11) DEFAULT NULL,
  `cpm_label` varchar(100) DEFAULT NULL,
  `cpm_url` varchar(100) DEFAULT NULL,
  `cpm_image` varchar(150) DEFAULT NULL,
  `cpm_tooltip` varchar(100) DEFAULT NULL,
  `cpm_order` tinyint(1) DEFAULT NULL,
  `cpm_status` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`cpm_id`),
  KEY `cpm_parentid` (`cpm_parentid`),
  KEY `cpm_status` (`cpm_status`)
) ENGINE=InnoDB ;

*****************************************************************/

/*****************************************************************
Table Definition. Use the following SQL to (re)create the table.

CREATE TABLE `ks_controlpanel_menurole` (
  `cpmr_itemid` int(10) NOT NULL default '0',
  `cpmr_roleid` varchar(255) NOT NULL,
  PRIMARY KEY  (`cpmr_itemid`,`cpmr_roleid`)
) TYPE=MyISAM

*****************************************************************/

class KS_Cpmenu extends KS_Search {
	
	const MENU_NAVIGATIONMENU = 1;
	const MENU_OPTION = 1;
	const MENU_MENU = 5;
	const MENU_USERS = 7;
	const MENU_ACL = 8;
	const MENU_DASHBOARD = 11;
	const MENU_NEWS = 17;
	 
	protected $sqlTable = 'ks_controlpanel_menu';
	
	protected $id;
	protected $parentid;
	protected $label;
	protected $url;
	protected $image;
	protected $tooltip;
	protected $order;
	protected $status;
	
	// $this->search() related properties.. 
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'mi_id';
	
	//this public variable holds the search result in associative array
	//to use it, use: $['usr_email']
	public $searchResultAssociativeArray = '';
	
	public function __construct() {
		try {
			//global $ks_db;
			global $ks_log;
		
		} catch ( Exception $e ) {
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
	public function select() {
		
		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}
			
			//count how many rows found
			if ($this->exists ()) {
				
				$sql = "SELECT * FROM $this->sqlTable WHERE cpm_id = ?";
				
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['cpm_id'];
					$this->parentid = $row ['cpm_parentid'];
					$this->label = $row ['cpm_label'];
					$this->url = $row ['cpm_url'];
					$this->image = $row ['cpm_image'];
					$this->tooltip = $row ['cpm_tooltip'];
					$this->order = $row ['cpm_order'];
					$this->status = $row ['cpm_status'];
				
				}
			
			} else {
				echo "No record found with id ($this->id) from table ($this->sqlTable).";
			}
		
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
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
	public function exists() {
		
		try {
			global $ks_db;
			global $ks_log;
			
			$bReturn = false;
			
			if (! isset ( $this->id )) {
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$id); in: " . __METHOD__;
				exit ();
			}
			
			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE cpm_id = ?";
			
			//count how many rows found
			$totalRow = $ks_db->fetchOne ( $sql, $this->id );
			
			if ($totalRow > 0) {
				$bReturn = true;
			} else {
				$bReturn = false;
			}
			
			return $bReturn;
		
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
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
				
			if (isset ( $this->parentid )) {
				$insertCols .= "cpm_parentid, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->parentid;
			}
			if (isset ( $this->label )) {
				$insertCols .= "cpm_label, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->label;
			}
			if (isset ( $this->url )) {
				$insertCols .= "cpm_url, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->url;
			}
			if (isset ( $this->image )) {
				$insertCols .= "cpm_image, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->image;
			}
			if (isset ( $this->tooltip )) {
				$insertCols .= "cpm_tooltip, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->tooltip;
			}
			if (isset ( $this->order )) {
				$insertCols .= "cpm_order, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->order;
			}
			if (isset ( $this->status )) {
				$insertCols .= "cpm_status, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->status;
			}
			
			//remove trailing commas
			$insertCols = preg_replace ( "/, $/", "", $insertCols );
			$insertVals = preg_replace ( "/, $/", "", $insertVals );
			
			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";
			
			$ks_db->query ( $sql, $arrBindings );
			
			//set the id property
			$this->id = $ks_db->lastInsertId ();
			
			$ks_db->commit ();
		
		} catch ( Exception $e ) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
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
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with id of ($this->id)";
				exit ();
			}
			
			$sql = "UPDATE $this->sqlTable SET ";
			
			if (isset ( $this->label )) {
				$sql .= "cpm_label = ?, ";
				$arrBindings [] = $this->label;
			}
			if (isset ( $this->url )) {
				$sql .= "cpm_url = ?, ";
				$arrBindings [] = $this->url;
			}
			if (isset ( $this->image )) {
				$sql .= "cpm_image = ?, ";
				$arrBindings [] = $this->image;
			}
			if (isset ( $this->tooltip )) {
				$sql .= "cpm_tooltip = ?, ";
				$arrBindings [] = $this->tooltip;
			}
			if (isset ( $this->order )) {
				$sql .= "cpm_order = ?, ";
				$arrBindings [] = $this->order;
			}
			if (isset ( $this->status )) {
				$sql .= "cpm_status = ?, ";
				$arrBindings [] = $this->status;
			}
			
			$sql = preg_replace ( '/, $/', '', $sql );
			$sql .= " WHERE cpm_id = ?";
			$arrBindings [] = $this->id;
			
			$ks_db->query ( $sql, $arrBindings );
			
			$ks_db->commit ();
		
		} catch ( Exception $e ) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
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
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with id of ($this->id)";
				exit ();
			}
			
			$ks_db->beginTransaction ();
			
			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE cpm_id = ?";
			
			$ks_db->query ( $sql, $this->id );
			
			$ks_db->comcpmt ();
		
		} catch ( Exception $e ) {
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
			
			$arrSearchResults = array ();
			
			parent::initSearch ();
			
			$stmt = $ks_db->query ( $this->searchSQL, $this->searchBinding );
			
			//record is found, associate columns to the object properties
			while ( true == ($row = $stmt->fetch ()) ) {
				
				$objResult = new KS_Cpmenu ( );
				
				$objResult->id = $row ['cpm_id'];
				$objResult->menuid = $row ['cpm_parentid'];
				$objResult->label = $row ['cpm_label'];
				$objResult->url = $row ['cpm_url'];
				$objResult->image1 = $row ['cpm_image'];
				$objResult->tooltip = $row ['cpm_tooltip'];
				$objResult->order = $row ['cpm_order'];
				$objResult->status = $row ['cpm_status'];
				
				$arrSearchResults [] = $objResult;
				
				$objResult->searchResultAssociativeArray = $row;
			
			}
			
			return $arrSearchResults;
		
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * @return int(11) unsigned
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return int(11)
	 */
	public function getParentid() {
		return $this->parentid;
	}
	
	/**
	 * @return varchar(100)
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @return varchar(100)
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @return varchar(255)
	 */
	public function getImage() {
		return $this->image;
	}
	
	/**
	 * @return varchar(50)
	 */
	public function getTooltip() {
		return $this->tooltip;
	}
	
	/**
	 * @return int(3)
	 */
	public function getOrder() {
		return $this->order;
	}
	/**
	 * @return int(3)
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @return string
	 */
	public function getSearchSortField() {
		return $this->searchSortField;
	}
	
	/**
	 * @param int(11) unsigned id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @param int(11) menuid
	 */
	public function setParentid($parentid) {
		$this->parentid = $parentid;
	}
	
	/**
	 * @param varchar(100) label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * @param varchar(100) urlbm
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * @param varchar(255) image1
	 */
	public function setImage($image) {
		$this->image = $image;
	}
	
	/**
	 * @param varchar(50) tooltip
	 */
	public function setTooltip($tooltip) {
		$this->tooltip = $tooltip;
	}
	
	/**
	 * @param int(3) order
	 */
	public function setOrder($order) {
		$this->order = $order;
	}
	/**
	 * @param int(3) order
	 */
	public function setStatus($status) {
		$this->status = $status;
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