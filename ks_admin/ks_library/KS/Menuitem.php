<?php

/*****************************************************************
Table Definition. Use the following SQL to (re)create the table.

CREATE TABLE `ks_menuitem` (
  `mi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mi_menuid` int(11) NOT NULL,
  `mi_parentid` int(11) NOT NULL DEFAULT '0',
  `mi_notlogin` tinyint(1) NOT NULL DEFAULT '0',
  `mi_label` varchar(100) DEFAULT NULL,
  `mi_urltype` varchar(32) DEFAULT '',
  `mi_url` varchar(100) DEFAULT '',
  `mi_image1` varchar(255) DEFAULT '',
  `mi_image2` varchar(255) DEFAULT '',
  `mi_tooltip` varchar(50) DEFAULT NULL,
  `mi_roles` varchar(255) DEFAULT '',
  `mi_order` int(3) DEFAULT '0',
  `mi_option` longtext,
  PRIMARY KEY (`mi_id`)
) ENGINE=InnoDB ;

 *****************************************************************/

require_once 'KS/Search.php';

class KS_Menuitem extends KS_Search {
	
	protected $sqlTable = 'ks_menuitem';
	
	protected $id;
	protected $menuid;
	protected $parentid;
	protected $notlogin;
	protected $label;
	protected $urltype;
	protected $url;
	protected $image1;
	protected $image2;
	protected $tooltip;
	protected $roles;
	protected $order;
	protected $option;
	
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
				
				$sql = "SELECT * FROM $this->sqlTable WHERE mi_id = ?";
				
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['mi_id'];
					$this->menuid = $row ['mi_menuid'];
					$this->parentid = $row ['mi_parentid'];
					$this->notlogin = $row ['mi_notlogin'];
					$this->label = $row ['mi_label'];
					$this->urltype = $row ['mi_urltype'];
					$this->url = $row ['mi_url'];
					$this->image1 = $row ['mi_image1'];
					$this->image2 = $row ['mi_image2'];
					$this->tooltip = $row ['mi_tooltip'];
					$this->roles = $row ['mi_roles'];
					$this->order = $row ['mi_order'];
					$this->option = $row ['mi_option'];
				
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
			
			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE mi_id = ?";
			
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
				
			if (isset ( $this->menuid )) {
				$insertCols .= "mi_menuid, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->menuid;
			}
			if (isset ( $this->parentid )) {
				$insertCols .= "mi_parentid, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->parentid;
			}
			if (isset ( $this->notlogin )) {
				$insertCols .= "mi_notlogin, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->notlogin;
			}
			if (isset ( $this->label )) {
				$insertCols .= "mi_label, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->label;
			}
			if (isset ( $this->urltype )) {
				$insertCols .= "mi_urltype, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->urltype;
			}
			if (isset ( $this->url )) {
				$insertCols .= "mi_url, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->url;
			}
			if (isset ( $this->image1 )) {
				$insertCols .= "mi_image1, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->image1;
			}
			if (isset ( $this->image2 )) {
				$insertCols .= "mi_image2, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->image2;
			}
			if (isset ( $this->tooltip )) {
				$insertCols .= "mi_tooltip, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->tooltip;
			}
			if (isset ( $this->roles )) {
				$insertCols .= "mi_roles, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->roles;
			}
			if (isset ( $this->order )) {
				$insertCols .= "mi_order, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->order;
			}
			if (isset ( $this->option )) {
				$insertCols .= "mi_option, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->option;
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
			
			if (isset ( $this->menuid )) {
				$sql .= "mi_menuid = ?, ";
				$arrBindings [] = $this->menuid;
			}
			if (isset ( $this->parentid )) {
				$sql .= "mi_parentid = ?, ";
				$arrBindings [] = $this->parentid;
			}
			if (isset ( $this->notlogin )) {
				$sql .= "mi_notlogin = ?, ";
				$arrBindings [] = $this->notlogin;
			}
			if (isset ( $this->label )) {
				$sql .= "mi_label = ?, ";
				$arrBindings [] = $this->label;
			}
			if (isset ( $this->urltype )) {
				$sql .= "mi_urltype = ?, ";
				$arrBindings [] = $this->urltype;
			}
			if (isset ( $this->url )) {
				$sql .= "mi_url = ?, ";
				$arrBindings [] = $this->url;
			}
			if (isset ( $this->image1 )) {
				$sql .= "mi_image1 = ?, ";
				$arrBindings [] = $this->image1;
			}
			if (isset ( $this->image2 )) {
				$sql .= "mi_image2 = ?, ";
				$arrBindings [] = $this->image2;
			}
			if (isset ( $this->tooltip )) {
				$sql .= "mi_tooltip = ?, ";
				$arrBindings [] = $this->tooltip;
			}
			if (isset ( $this->roles )) {
				$sql .= "mi_roles = ?, ";
				$arrBindings [] = $this->roles;
			}
			if (isset ( $this->order )) {
				$sql .= "mi_order = ?, ";
				$arrBindings [] = $this->order;
			}
			if (isset ( $this->option )) {
				$sql .= "mi_option = ?, ";
				$arrBindings [] = $this->option;
			}
			
			$sql = preg_replace ( '/, $/', '', $sql );
			$sql .= " WHERE mi_id = ?";
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
			$sql .= " WHERE mi_id = ?";
			
			$ks_db->query ( $sql, $this->id );
			
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
				
				$objResult = new KS_Menuitem ();
				
				$objResult->id = $row ['mi_id'];
				$objResult->menuid = $row ['mi_menuid'];
				$objResult->parentid = $row ['mi_parentid'];
				$objResult->notlogin = $row ['mi_notlogin'];
				$objResult->label = $row ['mi_label'];
				$objResult->urltype = $row ['mi_urltype'];
				$objResult->url = $row ['mi_url'];
				$objResult->image1 = $row ['mi_image1'];
				$objResult->image2 = $row ['mi_image2'];
				$objResult->tooltip = $row ['mi_tooltip'];
				$objResult->roles = $row ['mi_roles'];
				$objResult->order = $row ['mi_order'];
				$objResult->option = $row ['mi_option'];
				
				$arrSearchResults [] = $objResult;
				
				$objResult->searchResultAssociativeArray = $row;
			
			}
			
			return $arrSearchResults;
		
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	public static function checkExistList($lid, $mode) {
		global $ks_db;
		
		if ($mode == 'custom') {
			$sql = "SELECT COUNT(*) FROM ks_menuitem WHERE mi_url LIKE '%$lid%'";
		} else {
			$sql = "SELECT COUNT(*) FROM ks_menuitem WHERE mi_url = '$lid'";
		}
		$total = ( int ) $ks_db->fetchOne ( $sql );
		
		return $total;
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
	public function getMenuid() {
		return $this->menuid;
	}
	
	/**
	 * @return int(11)
	 */
	public function getParentid() {
		return $this->parentid;
	}
	
	/**
	 * @return int(1)
	 */
	public function getNotlogin() {
		return $this->notlogin;
	}
	
	/**
	 * @return varchar(100)
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * @return varchar(32)
	 */
	public function getUrltype() {
		return $this->urltype;
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
	public function getImage1() {
		return $this->image1;
	}
	
	/**
	 * @return varchar(255)
	 */
	public function getImage2() {
		return $this->image2;
	}
	
	/**
	 * @return varchar(50)
	 */
	public function getTooltip() {
		return $this->tooltip;
	}
	
	/**
	 * @return varchar(255)
	 */
	public function getRoles() {
		return $this->roles;
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
	public function getOption() {
		return $this->option;
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
	public function setMenuid($menuid) {
		$this->menuid = $menuid;
	}
	
	/**
	 * @param int(11) menuid
	 */
	public function setParentid($parentid) {
		$this->parentid = $parentid;
	}
	
	/**
	 * @param int(1) notlogin
	 */
	public function setNotlogin($notlogin) {
		$this->notlogin = $notlogin;
	}
	
	/**
	 * @param varchar(100) label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * @param varchar(32) urltype
	 */
	public function setUrltype($urltype) {
		$this->urltype = $urltype;
	}
	
	/**
	 * @param varchar(100) url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
	
	/**
	 * @param varchar(255) image1
	 */
	public function setImage1($image1) {
		$this->image1 = $image1;
	}
	
	/**
	 * @param varchar(255) image2
	 */
	public function setImage2($image2) {
		$this->image2 = $image2;
	}
	
	/**
	 * @param varchar(50) tooltip
	 */
	public function setTooltip($tooltip) {
		$this->tooltip = $tooltip;
	}
	
	/**
	 * @param varchar(255) roles
	 */
	public function setRoles($roles) {
		$this->roles = $roles;
	}
	
	/**
	 * @param int(3) order
	 */
	public function setOrder($order) {
		$this->order = $order;
	}
	
	/**
	 * @param longtext mi_option
	 */
	public function setOption($option) {
		$this->option = $option;
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

