<?php

/*****************************************************************
 Table Definition. Use the following SQL to (re)create the table.

 CREATE TABLE `t_option` (
 `option_code` varchar(64) NOT NULL,
 `option_desc` text,
 `option_group` varchar(64) DEFAULT NULL,
 `option_value` longtext,
 `option_readonly` smallint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`option_code`)
 ) ENGINE=MyISAM COMMENT='Store options either in string, numbers or serialized'

 *****************************************************************/
require_once 'KS/Search.php';
class KS_Option extends KS_Search {
	protected $sqlTable = 'ks_option';
	protected $code;
	protected $desc;
	protected $group;
	protected $value;
	protected $readonly;
	
	// $this->search() related properties..
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'option_code';
	
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
	 * This method returns a single record based on primary key option_code
	 *
	 * @param
	 *        	none
	 * @return true
	 */
	public function select() {
		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->code )) {
				echo "Fatal Error: Primary key Code not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// count how many rows found
			if ($this->exists ()) {
				
				$sql = "SELECT * FROM $this->sqlTable WHERE option_code = ? ";
				
				$stmt = $ks_db->query ( $sql, array (
						$this->code 
				) );
				
				// record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->code = $row ['option_code'];
					$this->desc = $row ['option_desc'];
					$this->group = $row ['option_group'];
					$this->value = $row ['option_value'];
					$this->readonly = $row ['option_readonly'];
				}
			} else {
				echo "No record found with primary key (Code) from table ($this->sqlTable).";
			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method checks if a row exists based on the supplied primary key option_code
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
			
			if (! isset ( $this->code )) {
				echo "Fatal Error: Primary key Code not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE option_code = ? ";
			
			// count how many rows found
			$totalRow = $ks_db->fetchOne ( $sql, array (
					$this->code 
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
				
			if (isset ( $this->code )) {
				$insertCols .= "option_code, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->code;
			}
			if (isset ( $this->desc )) {
				$insertCols .= "option_desc, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->desc;
			}
			if (isset ( $this->group )) {
				$insertCols .= "option_group, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->group;
			}
			if (isset ( $this->value )) {
				$insertCols .= "option_value, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->value;
			}
			if (isset ( $this->readonly )) {
				$insertCols .= "option_readonly, ";
				$insertVals .= "?, ";
				$arrBindings [] = $this->readonly;
			}
			
			// remove trailing commas
			$insertCols = preg_replace ( "/, $/", "", $insertCols );
			$insertVals = preg_replace ( "/, $/", "", $insertVals );
			
			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";
			
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
	 * This method updates a single record, based on primary key option_code
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
			
			if (! isset ( $this->code )) {
				echo "Fatal Error: Primary key Code not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// check if record exists
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with primary key (Code) from table ($this->sqlTable).";
				exit ();
			}
			
			$sql = "UPDATE $this->sqlTable SET ";
			
			if (isset ( $this->code )) {
				$sql .= "option_code = ?, ";
				$arrBindings [] = $this->code;
			}
			if (isset ( $this->desc )) {
				$sql .= "option_desc = ?, ";
				$arrBindings [] = $this->desc;
			}
			if (isset ( $this->group )) {
				$sql .= "option_group = ?, ";
				$arrBindings [] = $this->group;
			}
			if (isset ( $this->value )) {
				$sql .= "option_value = ?, ";
				$arrBindings [] = $this->value;
			}
			if (isset ( $this->readonly )) {
				$sql .= "option_readonly = ?, ";
				$arrBindings [] = $this->readonly;
			}
			
			$sql = preg_replace ( '/, $/', '', $sql );
			$sql .= " WHERE option_code = ? ";
			
			$arrBindings [] = $this->code;
			
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
	 * This method deletes a single record, based on primary key option_code
	 *
	 * @param
	 *        	none
	 */
	public function delete() {
		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->code )) {
				echo "Fatal Error: Primary key Code not set for the object! ";
				echo "Please do necessary \$objA->setPrimary (\$primaryCol); in: " . __METHOD__;
				exit ();
			}
			
			// check if record exists
			if (! $this->exists ()) {
				echo "Fatal Error: No record found with primary key (Code) from table ($this->sqlTable).";
				exit ();
			}
			
			$ks_db->beginTransaction ();
			
			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE option_code = ? ";
			
			$ks_db->query ( $sql, $this->code );
			
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
				
				$objResult = new KS_Option ();
				
				$objResult->code = $row ['option_code'];
				$objResult->desc = $row ['option_desc'];
				$objResult->group = $row ['option_group'];
				$objResult->value = $row ['option_value'];
				$objResult->readonly = $row ['option_readonly'];
				
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
	 * @return varchar(64)
	 */
	public function getCode() {
		return $this->code;
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
	 * @return varchar(64)
	 */
	public function getGroup() {
		return $this->group;
	}
	
	/**
	 *
	 * @return longtext
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 *
	 * @return smallint(1)
	 */
	public function getReadonly() {
		return $this->readonly;
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
	 *        	varchar(64) code
	 */
	public function setCode($code) {
		$this->code = $code;
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
	 *        	varchar(64) group
	 */
	public function setGroup($group) {
		$this->group = $group;
	}
	
	/**
	 *
	 * @param
	 *        	longtext value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 *
	 * @param
	 *        	smallint(1) readonly
	 */
	public function setReadonly($readonly) {
		$this->readonly = $readonly;
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
	 * This method returns Option value for specified code.
	 * This method also automatically unserialize the value.
	 * If unserializable, this method will just return the actual value.
	 *
	 * @param $code: Code
	 *        	of the option to be retrieved
	 * @return $value: Value in option_value column, either alphanumeric or
	 *         serialized
	 */
	public static function get($code) {
		try {
			global $ks_log;
			global $ks_db;
			
			$sql = "SELECT option_value FROM ks_option WHERE option_code = ?";
			
			$arrBindings = array ();
			$arrBindings [] = $code;
			
			$value = $ks_db->fetchOne ( $sql, $arrBindings );
			
			// we automatically unserialize
			if (unserialize ( $value )) {
				$value = unserialize ( $value );
			}
			return $value;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns list of group option.
	 */
	public static function getGroupList() {
		try {
			global $ks_log;
			global $ks_db;
			$value = array ();
			
			$sql = "SELECT option_group,option_code FROM ks_option WHERE option_group IS NOT NULL GROUP BY option_group";
			
			$value = $ks_db->fetchAll ( $sql );
			
			return $value;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns list of option based on group.
	 */
	public static function getOptionList($group) {
		try {
			global $ks_log;
			global $ks_db;
			$value = array ();
			
			$sql = "SELECT * FROM ks_option WHERE option_group = '$group'";
			
			$value = $ks_db->fetchAll ( $sql );
			
			return $value;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns list of option based on group.
	 */
	public static function getOptionListEmail($group, $method) {
		try {
			global $ks_log;
			global $ks_db;
			$value = array ();
			
			$sql = "SELECT * FROM ks_option WHERE option_group = '$group' AND option_code LIKE '$method%' ";
			
			$value = $ks_db->fetchAll ( $sql );
			
			return $value;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns SystemSetting value for specified code.
	 *
	 * @param $code: must
	 *        	exist in ks_option
	 * @return $value: whatever value in SQL table ks_option.set_value
	 */
	public static function getOptionValue($code) {
		try {
			global $ks_log;
			global $ks_db;
			
			$sql = "SELECT option_value FROM ks_option ";
			$sql .= " WHERE option_code = ?";
			
			$arrBindings = array ();
			$arrBindings [] = $code;
			
			$value = $ks_db->fetchOne ( $sql, $arrBindings );
			
			// we automatically unserialize
			if (@unserialize ( $value )) {
				$value = @unserialize ( $value );
			}
			
			return $value;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns tab array
	 *
	 * @param
	 *        	$main_table
	 * @return $arrayTab
	 */
	public static function getArrayTab($main_table) {
		try {
			global $ks_log;
			global $ks_db;
			
			if ($main_table == '') {
				$main_table = 't_user';
			}
			$objOption = new KS_Option ();
			$objOption->setCode ( "tab_wizard" );
			if (! $objOption->exists ()) {
				$objOption->insert ();
			}
			$objOption->select ();
			$serarray = $objOption->getValue ();
			$arrayTab = array ();
			$arrayTab = unserialize ( $serarray ); // unserialize
			if (! $serarray) {
				$arrayTab [$main_table] ['topsection'] ['name'] = "Top Section";
				$arrayTab [$main_table] ['topsection'] ['type'] = 1;
				$arrayTab [$main_table] ['topsection'] ['content'] = "Top Section Content";
				$nametab1 = "tab" . 1;
				$arrayTab [$main_table] [$nametab1] ['name'] = "Tab 1";
				$arrayTab [$main_table] [$nametab1] ['type'] = 1;
				$arrayTab [$main_table] [$nametab1] ['content'] = "Tab 1 Content";
			}
			if (count ( $arrayTab [$main_table] ) == 0) {
				$arrayTab [$main_table] ['topsection'] ['name'] = "Top Section";
				$arrayTab [$main_table] ['topsection'] ['type'] = 1;
				$arrayTab [$main_table] ['topsection'] ['content'] = "Top Section Content";
				$nametab1 = "tab" . 1;
				$arrayTab [$main_table] [$nametab1] ['name'] = "Tab 1";
				$arrayTab [$main_table] [$nametab1] ['type'] = 1;
				$arrayTab [$main_table] [$nametab1] ['content'] = "Tab 1 Content";
			}
			
			return $arrayTab;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns new tab name
	 *
	 * @param $main_table ,
	 *        	$namenewtab
	 * @return $nametabname
	 */
	public static function getNewTabName($main_table, $namenewtab) {
		try {
			global $ks_log;
			global $ks_db;
			
			$arrayTab = KS_Option::getArrayTab ( $main_table );
			
			if (array_key_exists ( $namenewtab, $arrayTab [$main_table] )) {
				
				$getcurtot = explode ( 'tab', $namenewtab );
				$curtot = $getcurtot [1] + 1;
				$namenewtab = "tab" . $curtot;
				return KS_Option::getNewTabName ( $main_table, $namenewtab );
			} else {
				
				return $namenewtab;
			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns Code Generator for tab wizard.
	 *
	 * @param
	 *        	$main_table
	 * @return $value: tab wizard code
	 */
	public static function getPassinId($main_table) {
		try {
			global $ks_log;
			global $ks_db;
			
			$sqlPrimary11 = "SHOW KEYS FROM $main_table WHERE Key_name ='PRIMARY'";
			$stmtPrimary22 = $ks_db->query ( $sqlPrimary11 );
			while ( true == ($rowPrimary1 = $stmtPrimary22->fetch ()) ) {
				$rowPrimary = $rowPrimary1 ['Column_name'];
				$varid = "$" . $rowPrimary;
				$getpassid .= "\n$varid = KS_Filter::inputSanitize (" . '$_GET' . " ['$rowPrimary']);";
			}
			
			return $getpassid;
			
			return $getpassid;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	
	/**
	 * This method returns Code Generator for tab wizard.
	 *
	 * @param
	 *        	$main_table
	 * @return $value: tab wizard code
	 */
	public static function getCodeTabWizard($main_table) {
		try {
			global $ks_log;
			global $ks_db;
			
			$arrayTab = KS_Option::getArrayTab ( $main_table );
			$getPassinId = KS_Option::getPassinId ( $main_table );
			$toptype = $arrayTab [$main_table] ['topsection'] ['type'];
			$topcontent = $arrayTab [$main_table] ['topsection'] ['content'];
			$filename = $arrayTab [$main_table] ['topsection'] ['filename'];
			$filedir = $arrayTab [$main_table] ['topsection'] ['fileurl'];
			
			switch ($toptype) {
				case 1 :
					$contenttop = $topcontent;
					break;
				case 2 :
					$contenttop = "<iframe frameborder=\"0\" seamless=\"seamless\" width=\"98%\" height=\"auto\" src=\"" . $topcontent . "\"></iframe>";
					break;
				case 3 :
					$getfid = explode ( "(", $topcontent ); // 1(add)
					$fid = $getfid [0]; // get fid
					$getfilenamewdir = explode ( ".php", $filename ); // user_tab.php
					$filenamewdir = $getfilenamewdir [0]; // user_tab
					$getfilename = explode ( "_tab", $filenamewdir ); // user_tab
					$nameform = $getfilename [0]; // user
					if ($getfilename [0] == '') {
						$nameform = $filenamewdir;
					}
					$filenameform = $nameform . '_' . 'topsection' . 'form.php';
					$fileurl = explode ( KSCONFIG_ABSPATH, $filedir ); 
					$filelocation = $fileurl [1];
					$filedirlocal = KSCONFIG_URL . $filelocation; 
					$urlseparator = "/";
					$filedirname = $filedirlocal . $urlseparator . $filenameform;
					$gettype = explode ( ")", $getfid [1] );
					$formtype = $gettype [0];
					if ($formtype != 'add') {
						$sqlPrimary11 = "SHOW KEYS FROM $main_table WHERE Key_name ='PRIMARY'";
						$stmtPrimary22 = $ks_db->query ( $sqlPrimary11 );
						if (count ( $stmtPrimary22 ) != 0) {
							$passid = "?";
						}
						while ( true == ($rowPrimary1 = $stmtPrimary22->fetch ()) ) {
							$rowPrimary = $rowPrimary1 ['Column_name'];
							$varid = "$" . $rowPrimary;
							$passid .= "&$rowPrimary=<?php echo $varid;?>";
						}
					}
					$contenttop .= " <iframe width=\"100%\" id=\"topsection_iframe\" onLoad=\"calcHeight();\"
					src=\"" . $filedirname . $passid . "\" scrolling=\"NO\" frameborder=\"0\" height=\"1\"></iframe>";
					$contenttop .= "<script>
					function calcHeight(){
						var the_height=
						document.getElementById('topsection_iframe').contentWindow.
						document.body.scrollHeight;
						document.getElementById('topsection_iframe').height= the_height;
					}
					</script>";
					break;
				case 4 :
					$lid = $topcontent; // get lid
					$getfilenamewdir = explode ( ".php", $filename ); // user_tab.php
					$filenamewdir = $getfilenamewdir [0]; // user_tab
					$getfilename = explode ( "_tab", $filenamewdir ); // user_tab
					$nameform = $getfilename [0]; // user
					if ($getfilename [0] == '') {
						$nameform = $filenamewdir;
					}
					$filenameform = $nameform . '_' . 'topsection' . 'list.php';
					$fileurl = explode ( KSCONFIG_ABSPATH, $filedir );
					$filelocation = $fileurl [1];
					$filedirlocal = KSCONFIG_URL . $filelocation; 
					$urlseparator = "/";
					$filedirname = $filedirlocal . $urlseparator . $filenameform;
					$sqlPrimary11 = "SHOW KEYS FROM $main_table WHERE Key_name ='PRIMARY'";
					$stmtPrimary22 = $ks_db->query ( $sqlPrimary11 );
					if (count ( $stmtPrimary22 ) != 0) {
						$passid = "?";
					}
					while ( true == ($rowPrimary1 = $stmtPrimary22->fetch ()) ) {
						$rowPrimary = $rowPrimary1 ['Column_name'];
						$varid = "$" . $rowPrimary;
						$passid .= "&$rowPrimary=<?php echo $varid;?>";
					}
					$contenttop .= " <iframe width=\"100%\" id=\"topsection_iframe\" onLoad=\"calcHeight();\"
					src=\"" . $filedirname . $passid . "\" scrolling=\"NO\" frameborder=\"0\" height=\"1\"></iframe>";
					$contenttop .= "<script>
					function calcHeight(){
						var the_height=
						document.getElementById('topsection_iframe').contentWindow.
						document.body.scrollHeight;
						document.getElementById('topsection_iframe').height= the_height;
					}
					</script>";
					break;
			}
			
			$strOutput = "";
			$strOutput .= "<?php \n if (file_exists ('library.php')){\n include_once 'library.php'; \n} elseif (file_exists ('../library.php')){ \n include_once '../library.php'; \n }
			\n if (file_exists ('layout_header.php')){ \n include_once 'layout_header.php';\n } elseif (file_exists ('../layout_header.php')){\n include_once '../layout_header.php'; \n }
			\n//identify tab to be selected, if no set to 0 (first tab) \n" . '$tabId' . " = (int) \$_GET ['tabId'];";
			$strOutput .= $getPassinId;
			$strOutput .= "\n?>";
			$strOutput .= "\n<script type=\"text/javascript\" language=\"JavaScript\"> \n$(document).ready(function(){ \n	try { \n		 var " . '$tab1' . " = $(\"#divTabs\").tabs(); \n		 //get default tab to be selected \n		 var tabId = <?php echo " . '$tabId' . ";?>;";
			$strOutput .= "\n		 " . '$tab1' . ".tabs( \"option\", \"active\", tabId); \n	} catch(error) { \n		var msg = \"Fatal Error: \" + error.description; \n		alert(msg); \n	 } \n }); \n</script>";
			$strOutput .= "\n<div id=\"divTop\">";
			
			$strOutput .= $contenttop;
			$strOutput .= "\n</div>";
			$strOutput .= "\n<div id=\"divTabs\"> \n    <ul>";
			
			foreach ( $arrayTab [$main_table] as $keytab => $curTab ) {
				$name = $curTab ['name'];
				if ($name != 'Top Section') {
					$type = $curTab ['type'];
					$content = $curTab ['content'];
					
					switch ($type) {
						case 1 :
							$contenttab = $content;
							$divtab = "#div" . $keytab;
							break;
						case 2 :
							
							$newstrurl = str_replace ( "/", "\/", KSCONFIG_URL );
							$deliurl = "/^" . $newstrurl . "/";
							if (! preg_match ( $deliurl, $content )) { // External URL
								$divtab = "#div" . $keytab;
							} else {
								$divtab = $content;
							}
							break;
						case 3 :
							$contentdiv = "Form";
							$getfid = explode ( "(", $content ); // 1(add)
							$fid = $getfid [0]; // get fid
							$getfilenamewdir = explode ( ".php", $filename ); // user_tab.php
							$filenamewdir = $getfilenamewdir [0]; // user_tab
							$getfilename = explode ( "_tab", $filenamewdir ); // user_tab
							$nameform = $getfilename [0]; // user
							if ($getfilename [0] == '') {
								$nameform = $filenamewdir;
							}
							$filenameform = $nameform . '_' . $keytab . 'form.php';
							$fileurl = explode ( KSCONFIG_ABSPATH, $filedir ); 
							$filelocation = $fileurl [1]; 
							$filedirlocal = KSCONFIG_URL . $filelocation; 
							$urlseparator = "/";
							$gettype = explode ( ")", $getfid [1] );
							$formtype = $gettype [0];
							if ($formtype != 'add') {
								$sqlPrimary11 = "SHOW KEYS FROM $main_table WHERE Key_name ='PRIMARY'";
								$stmtPrimary22 = $ks_db->query ( $sqlPrimary11 );
								if (count ( $stmtPrimary22 ) != 0) {
									$passidtab = "?";
								}
								while ( true == ($rowPrimary1 = $stmtPrimary22->fetch ()) ) {
									$rowPrimary = $rowPrimary1 ['Column_name'];
									$varid = "$" . $rowPrimary;
									$passidtab .= "&$rowPrimary=<?php echo $varid;?>";
								}
							}
							$divtab = $filedirname . $passidtab;
							break;
						case 4 :
							$contentdiv = "List";
							$lid = $content; // get lid
							$getfilenamewdir = explode ( ".php", $filename ); // user_tab.php
							$filenamewdir = $getfilenamewdir [0]; // user_tab
							$getfilename = explode ( "_tab", $filenamewdir ); // user_tab
							$nameform = $getfilename [0]; // user
							if ($getfilename [0] == '') {
								$nameform = $filenamewdir;
							}
							$filenameform = $nameform . '_' . $keytab . 'list.php'; 
							$fileurl = explode ( KSCONFIG_ABSPATH, $filedir ); 
							$filelocation = $fileurl [1];
							$filedirlocal = KSCONFIG_URL . $filelocation; 
							$urlseparator = "/";
							$filedirname = $filedirlocal . $urlseparator . $filenameform; 
							$sqlPrimary11 = "SHOW KEYS FROM $main_table WHERE Key_name ='PRIMARY'";
							$stmtPrimary22 = $ks_db->query ( $sqlPrimary11 );
							if (count ( $stmtPrimary22 ) != 0) {
								$passidtab = "?";
							}
							while ( true == ($rowPrimary1 = $stmtPrimary22->fetch ()) ) {
								$rowPrimary = $rowPrimary1 ['Column_name'];
								$varid = "$" . $rowPrimary;
								$passidtab .= "&$rowPrimary=<?php echo $varid;?>";
							}
							$divtab = $filedirname . $passidtab;
							break;
					}
					
					$strOutput .= "\n<li><a href=\"$divtab\"><span>$name</span></a></li>";
				}
			}
			$strOutput .= "\n   </ul> \n";
			foreach ( $arrayTab [$main_table] as $keytab => $curTab ) {
				$name = $curTab ['name'];
				if ($name != 'Top Section') {
					$type = $curTab ['type'];
					$content = $curTab ['content'];
					
					switch ($type) {
						case 1 :
							$contenttab = "<div id=\"div$keytab\"> \n       $content \n    </div>";
							break;
						case 2 :
							$newstrurl = str_replace ( "/", "\/", KSCONFIG_URL );
							$deliurl = "/^" . $newstrurl . "/";
							if (! preg_match ( $deliurl, $content )) { // External URL
								$contenturl = "<iframe frameborder=\"0\" seamless=\"seamless\" width=\"98%\" height=\"500\" src=\"" . $content . "\"></iframe>";
								$contenttab = "<div id=\"div$keytab\"> \n       $contenturl \n    </div>";
							} else {
								$contenttab = "";
							}
							
							break;
						case 3 :
							$contenttab = "";
							break;
						case 4 :
							$contenttab = "";
							break;
						case 5 :
							$contenttab = "";
							break;
					}
					
					$strOutput .= $contenttab;
				}
			}
			$strOutput .= "\n</div>";
			return $strOutput;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
	public static function templatetheme() {
		try {
			
			global $ks_log;
			
			// body
			$bodybgcolort = "#e81f1f";
			$bodycolort = "#555555";
			$bodyfontfamilyt = "#Verdana, Arial, Helvetica, sans-serif";
			$bodyfontsizet = "12px";
			
			// navbar
			$navbgcolor1t = "#d9230f";
			$navbgcolor2t = "#ef2d18";
			$navfontcolort = "#ffffff";
			$navfontfamilyt = "#Verdana, Arial, Helvetica, sans-serif";
			$navfontsizet = "12px";
			
			// button
			// default
			$bdefbgcolor1t = "#e6e6e6";
			$bdefbgcolor2t = "#ffffff";
			$bdeffontcolort = "#333333";
			// primary
			$bpribgcolor1t = "#d9230f";
			$bpribgcolor2t = "#ef2913";
			$bprifontcolort = "#ffffff";
			// info
			$binfbgcolor1t = "#5bc0de";
			$binfbgcolor2t = "#70c8e2";
			$binffontcolort = "#ffffff";
			// success
			$bsucbgcolor1t = "#3d9400";
			$bsucbgcolor2t = "#48ae00";
			$bsucfontcolort = "#ffffff";
			// warning
			$bwarbgcolor1t = "#ffca27";
			$bwarbgcolor2t = "#ffd041";
			$bwarfontcolort = "#ffffff";
			// danger
			$bdanbgcolor1t = "#d9230f";
			$bdanbgcolor2t = "#ef2913";
			$bdanfontcolort = "#ffffff";
			// inverse
			$binvbgcolor1t = "#2c2c2c";
			$binvbgcolor2t = "#555555";
			$binvfontcolort = "#ffffff";
			// link
			$blinbgcolor1t = "#e6e6e6";
			$blinbgcolor2t = "#ffffff";
			$blinfontcolort = "#d9230f";
			
			// typhography
			$h1colort = "#999999";
			$h6colort = "#999999";
			$acolort = "#d9230f";
			
			// alerts
			// info
			$ainfbgcolort = "#d9edf7";
			$ainfbordercolort = "#bce8f1";
			$ainffontcolort = "#029acf";
			
			// success
			$asucbgcolort = "#dff0d8";
			$asucbordercolort = "#d6e9c6";
			$asucfontcolort = "#3d9400";
			
			// danger
			$adanbgcolort = "#f2dede";
			$adanbordercolort = "#eed3d7";
			$adanfontcolort = "#d9230f";
			
			// dashboard
			$dportletcolor1t = "#D9230F";
			$dportletcolor2t = "#EF2D18";
			$dportletfontcolort = "#ffffff";
			$dbordercolort = "#FF9933";
			
			for($i = 1; $i <= 3; $i ++) {
				$statustt = 0;
				if ($i == 1) {
					$statustt = 1;
				}
				$themetitlet = 'Theme' . $i;
				// body
				$themevalue ['theme'] [$i] ['themetitle'] = $themetitlet;
				$themevalue ['theme'] [$i] ['themetatus'] = $statustt;
				$themevalue ['theme'] [$i] ['bodybgcolor'] = $bodybgcolort;
				$themevalue ['theme'] [$i] ['bodycolor'] = $bodycolort;
				$themevalue ['theme'] [$i] ['bodyfontfamily'] = $bodyfontfamilyt;
				$themevalue ['theme'] [$i] ['bodyfontsize'] = $bodyfontsizet;
				
				// navbar
				$themevalue ['theme'] [$i] ['navbgcolor1'] = $navbgcolor1t;
				$themevalue ['theme'] [$i] ['navbgcolor2'] = $navbgcolor2t;
				$themevalue ['theme'] [$i] ['navfontcolor'] = $navfontcolort;
				$themevalue ['theme'] [$i] ['navfontfamily'] = $navfontfamilyt;
				$themevalue ['theme'] [$i] ['navfontsize'] = $navfontsizet;
				
				// button
				// default
				$themevalue ['theme'] [$i] ['bdefbgcolor1'] = $bdefbgcolor1t;
				$themevalue ['theme'] [$i] ['bdefbgcolor2'] = $bdefbgcolor2t;
				$themevalue ['theme'] [$i] ['bdeffontcolor'] = $bdeffontcolort;
				// primary
				$themevalue ['theme'] [$i] ['bpribgcolor1'] = $bpribgcolor1t;
				$themevalue ['theme'] [$i] ['bpribgcolor2'] = $bpribgcolor2t;
				$themevalue ['theme'] [$i] ['bprifontcolor'] = $bprifontcolort;
				// info
				$themevalue ['theme'] [$i] ['binfbgcolor1'] = $binfbgcolor1t;
				$themevalue ['theme'] [$i] ['binfbgcolor2'] = $binfbgcolor2t;
				$themevalue ['theme'] [$i] ['binffontcolor'] = $binffontcolort;
				// success
				$themevalue ['theme'] [$i] ['bsucbgcolor1'] = $bsucbgcolor1t;
				$themevalue ['theme'] [$i] ['bsucbgcolor2'] = $bsucbgcolor2t;
				$themevalue ['theme'] [$i] ['bsucfontcolor'] = $bsucfontcolort;
				// warning
				$themevalue ['theme'] [$i] ['bwarbgcolor1'] = $bwarbgcolor1t;
				$themevalue ['theme'] [$i] ['bwarbgcolor2'] = $bwarbgcolor2t;
				$themevalue ['theme'] [$i] ['bwarfontcolor'] = $bwarfontcolort;
				// danger
				$themevalue ['theme'] [$i] ['bdanbgcolor1'] = $bdanbgcolor1t;
				$themevalue ['theme'] [$i] ['bdanbgcolor2'] = $bdanbgcolor2t;
				$themevalue ['theme'] [$i] ['bdanfontcolor'] = $bdanfontcolort;
				// inverse
				$themevalue ['theme'] [$i] ['binvbgcolor1'] = $binvbgcolor1t;
				$themevalue ['theme'] [$i] ['binvbgcolor2'] = $binvbgcolor2t;
				$themevalue ['theme'] [$i] ['binvfontcolor'] = $binvfontcolort;
				// link
				$themevalue ['theme'] [$i] ['blinbgcolor1'] = $blinbgcolor1t;
				$themevalue ['theme'] [$i] ['blinbgcolor2'] = $blinbgcolor2t;
				$themevalue ['theme'] [$i] ['blinfontcolor'] = $blinfontcolort;
				
				// typhography
				$themevalue ['theme'] [$i] ['h1color'] = $h1colort;
				$themevalue ['theme'] [$i] ['h6color'] = $h6colort;
				$themevalue ['theme'] [$i] ['acolor'] = $acolort;
				
				// alerts
				// info
				$themevalue ['theme'] [$i] ['ainfbgcolor'] = $ainfbgcolort;
				$themevalue ['theme'] [$i] ['ainfbordercolor'] = $ainfbordercolort;
				$themevalue ['theme'] [$i] ['ainffontcolor'] = $ainffontcolort;
				
				// success
				$themevalue ['theme'] [$i] ['asucbgcolor'] = $asucbgcolort;
				$themevalue ['theme'] [$i] ['asucbordercolor'] = $asucbordercolort;
				$themevalue ['theme'] [$i] ['asucfontcolor'] = $asucfontcolort;
				
				// danger
				$themevalue ['theme'] [$i] ['adanbgcolor'] = $adanbgcolort;
				$themevalue ['theme'] [$i] ['adanbordercolor'] = $adanbordercolort;
				$themevalue ['theme'] [$i] ['adanfontcolor'] = $adanfontcolort;
				
				// dashboard
				$themevalue ['theme'] [$i] ['dportletcolor1'] = $dportletcolor1t;
				$themevalue ['theme'] [$i] ['dportletcolor2'] = $dportletcolor2t;
				$themevalue ['theme'] [$i] ['dportletfontcolor'] = $dportletfontcolort;
				$themevalue ['theme'] [$i] ['dbordercolor'] = $dbordercolort;
			}
			return $themevalue;
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}
	}
}

