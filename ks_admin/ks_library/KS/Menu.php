<?php

/*****************************************************************
 Table Definition. Use the following SQL to (re)create the table.

 CREATE TABLE `ks_menu` (
 `menu_id` int(11) unsigned NOT NULL auto_increment,
 `menu_name` varchar(255) NOT NULL default '',
 `menu_path` varchar(255) default '',
 `menu_jscode` text,
 `menu_option` longtext,
 PRIMARY KEY  (`menu_id`)
 ) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1

 *****************************************************************/

require_once 'KS/Search.php';

class KS_Menu extends KS_Search {

	protected $sqlTable = 'ks_menu';

	protected $id;
	protected $name;
	protected $path;
	protected $jscode;
	protected $option;

	// $this->search() related properties..
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'menu_id';

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
					
				$sql = "SELECT * FROM $this->sqlTable WHERE menu_id = ?";

				$stmt = $ks_db->query ( $sql, $this->id );

				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {

					$this->id = $row ['menu_id'];
					$this->name = $row ['menu_name'];
					$this->path = $row ['menu_path'];
					$this->jscode = $row ['menu_jscode'];
					$this->option = $row ['menu_option'];

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

			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE menu_id = ?";

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

			if (isset ( $this->name )) {
				$insertCols .= "menu_name, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->name;
			}
			if (isset ( $this->path )) {
				$insertCols .= "menu_path, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->path;
			}
			if (isset ( $this->jscode )) {
				$insertCols .= "menu_jscode, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->jscode;
			}
			if (isset ( $this->option )) {
				$insertCols .= "menu_option, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->option;
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

			if (isset ( $this->name )) {
				$sql .= "menu_name = ?, ";
				$arrBindings[] = $this->name;
			}
			if (isset ( $this->path )) {
				$sql .= "menu_path = ?, ";
				$arrBindings[] = $this->path;
			}
			if (isset ( $this->jscode )) {
				$sql .= "menu_jscode = ?, ";
				$arrBindings[] = $this->jscode;
			}
			if (isset ( $this->option )) {
				$sql .= "menu_option = ?, ";
				$arrBindings[] = $this->option;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE menu_id = ?";
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
			$sql .= " WHERE menu_id = ?";

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

			$arrSearchResults = array ();

			parent::initSearch ();

			$stmt = $ks_db->query ( $this->searchSQL, $this->searchBinding );

			//record is found, associate columns to the object properties
			while ( true == ($row = $stmt->fetch ()) ) {

				$objResult = new KS_Menu ( );

				$objResult->id = $row ['menu_id'];
				$objResult->name = $row ['menu_name'];
				$objResult->path = $row ['menu_path'];
				$objResult->jscode = $row ['menu_jscode'];
				$objResult->option = $row ['menu_option'];

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
	 * @return int(11) unsigned
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return varchar(255)
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return varchar(255)
	 */
	public function getPath(){
		return $this->path;
	}

	/**
	 * @return text
	 */
	public function getJscode(){
		return $this->jscode;
	}

	/**
	 * @return text
	 */
	public function getOption(){
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
	 * @param varchar(255) name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param varchar(255) path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * @param text jscode
	 */
	public function setJscode($jscode) {
		$this->jscode = $jscode;
	}

	/**
	 * @param text option
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

	/**
	 * This method returns all menu in the specified order
	 *
	 */
	static public function listMenu($orderField = '', $orderType = '', $startPage = 0, $recordPerPage = 'All') {

		try {
			global $ks_db;
			global $ks_log;

			$arrTables = array ();

			if (! isset ( $orderField ) or ($orderField == '')) {
				$orderField = 'menu_name';
			}

			switch ($orderType) {
				case "DESC" :
					$orderType = 'DESC';
					break;
				case "ASC" :
				default :
					$orderType = 'ASC';
			}

			if ($recordPerPage == 'All') {
				$strLimit = '';
			} else {
				if ($startPage > 0) {
					$nextStart = ($startPage * $recordPerPage);
					$strLimit = "LIMIT $nextStart,$recordPerPage";
				} else {
					$strLimit = "LIMIT 0,$recordPerPage";
				}
			}

			$sqlTotal = "SELECT COUNT(*) AS totalTables FROM ks_menu ORDER BY $orderField $orderType";
			$stmtTotal = $ks_db->query ( $sqlTotal );

			$rowCount = 0;
			//count how many rows found
			while ( true == ($rowTotal = $stmtTotal->fetch ()) ) {
				$rowCount = $rowTotal ['totalTables'];
			}
			$arrTables ['total'] = $rowCount;

			$sql = "SELECT * FROM ks_menu ORDER BY $orderField $orderType $strLimit";
			$stmt = $ks_db->query ( $sql );

			//record is found, associate columns to the object properties
			$i = 0;
			while ( true == ($row = $stmt->fetch ()) ) {

				$arrTables ['data'] [$i] ['id'] = $row ['menu_id'];
				$arrTables ['data'] [$i] ['name'] = $row ['menu_name'];
				$arrTables ['data'] [$i] ['path'] = $row ['menu_path'];
				$arrTables ['data'] [$i] ['jscode'] = $row ['menu_jscode'];
				$arrTables ['data'] [$i] ['option'] = $row ['menu_option'];
				$i ++;
			}

			return $arrTables;

		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: KS_Menu::listMenu. ' . $e->getMessage () );
			echo "Fatal Error:  KS_Menu::listMenu." . $e->getMessage ();
			echo "<br/>SQL Statement: " . $sql;
		}
	}

	/**
	 * This method returns all menu item
	 * order
	 *
	 */
	static public function listItemsByMenu($menuid, $setlanguage = 'BI', $orderType = 'ASC', $startPage = '0', $recordPerPage = 'All') {
		try {
			global $ks_db;
			global $ks_log;

			$arrFields = array ();

			//bylanguange
			$orderField = 'mi_label';

			if (! isset ( $menuid )) {
				echo "Fatal Error: (KS_Menu::listItemsByMenu(\$menuid))  menu is not set for the object! Please do \$arrFields = KS_Menu::listItemsByMenu(\$menuid));";
				exit ();
			}

			if (! isset ( $orderField )) {
				$orderField = 'mi_label';
			}

			switch ($orderType) {
				case "DESC" :
					break;
				case "ASC" :
				default :
					$orderType = 'ASC';
			}

			if ($recordPerPage == 'All') {
				$strLimit = '';
			} else {
				if ($startPage > 0) {
					$nextStart = ($startPage * $recordPerPage);
					$strLimit = "LIMIT $nextStart,$recordPerPage";
				} else {
					$strLimit = "LIMIT 0,$recordPerPage";
				}
			}

			$sqlTotal = "SELECT COUNT(*) as totalRows FROM ks_menuitem WHERE mi_menuid = ?";
			$result = $ks_db->query ( $sqlTotal, array ($menuid ) );

			// count how many rows found
			$totalRows = $result->fetchColumn ();
			$arrFields ['total'] = $totalRows;

			$sql = "SELECT * FROM ks_menuitem WHERE mi_menuid = ? ORDER BY $orderField ASC $strLimit";
			$stmt = $ks_db->query ( $sql, array ($menuid ) );

			// record is found, associate columns to the object properties
			$i = 0;

			$arrFields ['data'] = array ();

			while ( true == ($row = $stmt->fetch ()) ) {

				//bylanguange
				$arrFields ['data'] [$i] ['tablename'] = $row ['mi_label'];
				$arrFields ['data'] [$i] ['itemid'] = $row ['mi_id'];
				$i ++;
			}

			return $arrFields;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( 'SQL Statement: ' . $sql );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

}

