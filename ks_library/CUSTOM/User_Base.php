<?php

/*****************************************************************
Table Definition. Use the following SQL to (re)create the table.

CREATE TABLE `t_user` (
  `usr_id` varchar(32) NOT NULL COMMENT 'Unique User ID',
  `usr_password` varchar(32) NOT NULL COMMENT 'User password',
  `usr_salt` varchar(6) DEFAULT NULL,
  `usr_name` varchar(255) DEFAULT NULL COMMENT 'User fullname',
  `usr_email` varchar(32) DEFAULT NULL COMMENT 'User email address',
  `usr_role` varchar(50) NOT NULL COMMENT 'User role',
  `usr_enabled` tinyint(1) DEFAULT '0' COMMENT 'Enable, 1 for enabled, 0 for disabled',
  `usr_lastlogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'User last login datetime',
  `usr_phone_office` varchar(20) DEFAULT NULL COMMENT 'Office phone',
  `usr_phone_mobile` varchar(20) DEFAULT NULL COMMENT 'Mobile phone',
  `usr_option` longtext,
  `usr_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date & time record created.',
  `usr_date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date & time record last modified.',
  `usr_userid_created` varchar(32) DEFAULT NULL COMMENT 'UserID who creates the record, from t_user.usr_userid',
  `usr_userid_modified` varchar(32) DEFAULT NULL COMMENT 'UserID who last modified the record, from t_user.usr_userid',
  PRIMARY KEY (`usr_id`)
) ENGINE=MyISAM COMMENT='System users';

*****************************************************************/

require_once 'KS/Search.php';
	
class CUSTOM_User_Base extends KS_Search { 

	protected $sqlTable = 't_user';
	
	protected $id;
	protected $password;
	protected $salt;
	protected $name;
	protected $email;
	protected $role;
	protected $enabled;
	protected $lastlogin;
	protected $phoneOffice;
	protected $phoneMobile;
	protected $option;
	protected $dateCreated;
	protected $dateModified;
	protected $useridCreated;
	protected $useridModified;

	// $this->search() related properties.. 
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'usr_id';

	//this public variable holds the search result in associative array
	//to use it, use: $['usr_email']
	public $searchResultAssociativeArray = '';
		

	public function __construct() {
		try {
			//global $ks_db;
			global $ks_log;
			
		}catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
		}
	}

	/**
	 * This method returns a single record based on primary key $usr_id
	 *
	 * @param none
	 * @return true
	 */
	public function select(){

		try {
			global $ks_db;
			global $ks_log;
			
			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$usr_id); in: " . __METHOD__;
				exit ();
			}

			//count how many rows found
			if($this->exists ()) {
			
				$sql = "SELECT * FROM $this->sqlTable WHERE usr_id = ?";
	
				$stmt = $ks_db->query ( $sql, $this->id );
				
				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {
					
					$this->id = $row ['usr_id'];
					$this->password = $row ['usr_password'];
					$this->salt = $row ['usr_salt'];
					$this->name = $row ['usr_name'];
					$this->email = $row ['usr_email'];
					$this->role = $row ['usr_role'];
					$this->enabled = $row ['usr_enabled'];
					$this->lastlogin = $row ['usr_lastlogin'];
					$this->phoneOffice = $row ['usr_phone_office'];
					$this->phoneMobile = $row ['usr_phone_mobile'];
					$this->option = $row ['usr_option'];
					$this->dateCreated = $row ['usr_date_created'];
					$this->dateModified = $row ['usr_date_modified'];
					$this->useridCreated = $row ['usr_userid_created'];
					$this->useridModified = $row ['usr_userid_modified'];

				}

			} else {
				echo "No record found with primary column ($this->id) from table ($this->sqlTable).";
			}	

		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}
	
	/**
	 * This method check whether the supplied primary key $usr_id exists or not
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
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$usr_id); in: " . __METHOD__;
				exit ();
			}
			
			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE usr_id = ?";

			//count how many rows found
			$totalRow = $ks_db->fetchOne ( $sql, $this->id );
			
			if ($totalRow > 0) {
				$bReturn = true;
			}else {
				$bReturn = false;
			}
			
			return $bReturn;
			
		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
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
			
			if (isset ( $this->id )) {
				$insertCols .= "usr_id, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->id;
			}
			if (isset ( $this->password )) {
				$insertCols .= "usr_password, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->password;
			}
			if (isset ( $this->salt )) {
				$insertCols .= "usr_salt, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->salt;
			}
			if (isset ( $this->name )) {
				$insertCols .= "usr_name, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->name;
			}
			if (isset ( $this->email )) {
				$insertCols .= "usr_email, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->email;
			}
			if (isset ( $this->role )) {
				$insertCols .= "usr_role, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->role;
			}
			if (isset ( $this->enabled )) {
				$insertCols .= "usr_enabled, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->enabled;
			}
			if (isset ( $this->lastlogin )) {
				$insertCols .= "usr_lastlogin, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->lastlogin;
			}
			if (isset ( $this->phoneOffice )) {
				$insertCols .= "usr_phone_office, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->phoneOffice;
			}
			if (isset ( $this->phoneMobile )) {
				$insertCols .= "usr_phone_mobile, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->phoneMobile;
			}
			if (isset ( $this->option )) {
				$insertCols .= "usr_option, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->option;
			}
			if (isset ( $this->dateCreated )) {
				$insertCols .= "usr_date_created, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->dateCreated;
			}
			if (isset ( $this->dateModified )) {
				$insertCols .= "usr_date_modified, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->dateModified;
			}
			if (isset ( $this->useridCreated )) {
				$insertCols .= "usr_userid_created, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->useridCreated;
			}
			if (isset ( $this->useridModified )) {
				$insertCols .= "usr_userid_modified, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->useridModified;
			}
			
			//remove trailing commas
			$insertCols = preg_replace("/, $/", "", $insertCols);
			$insertVals = preg_replace("/, $/", "", $insertVals);
			
			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";
			
			$ks_db->query ( $sql, $arrBindings );

			$ks_db->commit ();
			
		} catch(Exception $e) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This method updates a single record, based on primary column $id
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
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$usr_id); in: " . __METHOD__;
				exit ();
			}
			
			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with primary key of ($this->id)";
				exit ();
			}

			$sql = "UPDATE $this->sqlTable SET ";
			
			if (isset ( $this->id )) {
				$sql .= "usr_id = ?, ";
				$arrBindings[] = $this->id;
			}
			if (isset ( $this->password )) {
				$sql .= "usr_password = ?, ";
				$arrBindings[] = $this->password;
			}
			if (isset ( $this->salt )) {
				$sql .= "usr_salt = ?, ";
				$arrBindings[] = $this->salt;
			}
			if (isset ( $this->name )) {
				$sql .= "usr_name = ?, ";
				$arrBindings[] = $this->name;
			}
			if (isset ( $this->email )) {
				$sql .= "usr_email = ?, ";
				$arrBindings[] = $this->email;
			}
			if (isset ( $this->role )) {
				$sql .= "usr_role = ?, ";
				$arrBindings[] = $this->role;
			}
			if (isset ( $this->enabled )) {
				$sql .= "usr_enabled = ?, ";
				$arrBindings[] = $this->enabled;
			}
			if (isset ( $this->lastlogin )) {
				$sql .= "usr_lastlogin = ?, ";
				$arrBindings[] = $this->lastlogin;
			}
			if (isset ( $this->phoneOffice )) {
				$sql .= "usr_phone_office = ?, ";
				$arrBindings[] = $this->phoneOffice;
			}
			if (isset ( $this->phoneMobile )) {
				$sql .= "usr_phone_mobile = ?, ";
				$arrBindings[] = $this->phoneMobile;
			}
			if (isset ( $this->option )) {
				$sql .= "usr_option = ?, ";
				$arrBindings[] = $this->option;
			}
			if (isset ( $this->dateCreated )) {
				$sql .= "usr_date_created = ?, ";
				$arrBindings[] = $this->dateCreated;
			}
			if (isset ( $this->dateModified )) {
				$sql .= "usr_date_modified = ?, ";
				$arrBindings[] = $this->dateModified;
			}
			if (isset ( $this->useridCreated )) {
				$sql .= "usr_userid_created = ?, ";
				$arrBindings[] = $this->useridCreated;
			}
			if (isset ( $this->useridModified )) {
				$sql .= "usr_userid_modified = ?, ";
				$arrBindings[] = $this->useridModified;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE usr_id = ?";
			$arrBindings[] = $this->id;
			
			$ks_db->query ( $sql, $arrBindings );
	
			$ks_db->commit ();
			
		} catch(Exception $e) {
			$ks_db->rollBack ();
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql);
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This method deletes a single record, based on primary column $id
	 *
	 * @param none
	 */
	public function delete() {

		try {
			global $ks_db;
			global $ks_log;

			if (! isset ( $this->id )) { 
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$usr_id); in: " . __METHOD__;
				exit ();
			}
			
			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with primary key of ($this->id)";
				exit ();
			}

			$ks_db->beginTransaction ();

			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE usr_id = ?";

			$ks_db->query ( $sql, $this->id );

			$ks_db->commit ();

		} catch(Exception $e) {
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
	 * @param none
	 * @return array $className
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
				
				$objResult = new CUSTOM_User_Base ( );
				
				$objResult->id = $row ['usr_id'];
				$objResult->password = $row ['usr_password'];
				$objResult->salt = $row ['usr_salt'];
				$objResult->name = $row ['usr_name'];
				$objResult->email = $row ['usr_email'];
				$objResult->role = $row ['usr_role'];
				$objResult->enabled = $row ['usr_enabled'];
				$objResult->lastlogin = $row ['usr_lastlogin'];
				$objResult->phoneOffice = $row ['usr_phone_office'];
				$objResult->phoneMobile = $row ['usr_phone_mobile'];
				$objResult->option = $row ['usr_option'];
				$objResult->dateCreated = $row ['usr_date_created'];
				$objResult->dateModified = $row ['usr_date_modified'];
				$objResult->useridCreated = $row ['usr_userid_created'];
				$objResult->useridModified = $row ['usr_userid_modified'];
				
				$arrSearchResults [] = $objResult;
				
				$objResult->searchResultAssociativeArray = $row;
				
			}
			
			return $arrSearchResults;

		} catch(Exception $e) {
			$ks_log->info ( 'Fatal Error: ' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __METHOD__ . '. ' . $e->getMessage ();
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
	public function getPassword(){
		return $this->password;
	}
	
	/**
	 * @return varchar(6)
	 */
	public function getSalt(){
		return $this->salt;
	}

	/**
	 * @return varchar(255)
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return varchar(32)
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * @return varchar(50)
	 */
	public function getRole(){
		return $this->role;
	}

	/**
	 * @return smallint(1)
	 */
	public function getEnabled(){
		return $this->enabled;
	}

	/**
	 * @return datetime
	 */
	public function getLastlogin(){
		return $this->lastlogin;
	}

	/**
	 * @return varchar(20)
	 */
	public function getPhoneOffice(){
		return $this->phoneOffice;
	}

	/**
	 * @return varchar(20)
	 */
	public function getPhoneMobile(){
		return $this->phoneMobile;
	}

	/**
	 * @return longtext
	 */
	public function getOption(){
		return $this->option;
	}

	/**
	 * @return datetime
	 */
	public function getDateCreated(){
		return $this->dateCreated;
	}

	/**
	 * @return datetime
	 */
	public function getDateModified(){
		return $this->dateModified;
	}

	/**
	 * @return varchar(32)
	 */
	public function getUseridCreated(){
		return $this->useridCreated;
	}

	/**
	 * @return varchar(32)
	 */
	public function getUseridModified(){
		return $this->useridModified;
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
	 * @param varchar(32) password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param varchar(6) salt
	 */
	public function setSalt($salt) {
		$this->salt = $salt;
	}

	/**
	 * @param varchar(255) name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param varchar(32) email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param varchar(50) role
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * @param smallint(1) enabled
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	/**
	 * @param datetime lastlogin
	 */
	public function setLastlogin($lastlogin) {
		$this->lastlogin = $lastlogin;
	}

	/**
	 * @param varchar(20) phoneOffice
	 */
	public function setPhoneOffice($phoneOffice) {
		$this->phoneOffice = $phoneOffice;
	}

	/**
	 * @param varchar(20) phoneMobile
	 */
	public function setPhoneMobile($phoneMobile) {
		$this->phoneMobile = $phoneMobile;
	}

	/**
	 * @param longtext option
	 */
	public function setOption($option) {
		$this->option = $option;
	}

	/**
	 * @param datetime dateCreated
	 */
	public function setDateCreated($dateCreated) {
		$this->dateCreated = $dateCreated;
	}

	/**
	 * @param datetime dateModified
	 */
	public function setDateModified($dateModified) {
		$this->dateModified = $dateModified;
	}

	/**
	 * @param varchar(32) useridCreated
	 */
	public function setUseridCreated($useridCreated) {
		$this->useridCreated = $useridCreated;
	}

	/**
	 * @param varchar(32) useridModified
	 */
	public function setUseridModified($useridModified) {
		$this->useridModified = $useridModified;
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

