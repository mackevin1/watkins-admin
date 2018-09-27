<?php

/*****************************************************************
 Table Definition. Use the following SQL to (re)create the table.

 CREATE TABLE `ks_dashboard` (
 `dsh_id` int(11) NOT NULL AUTO_INCREMENT,
 `dsh_title` varchar(32) DEFAULT NULL,
 `dsh_desc` varchar(255) DEFAULT NULL,
 `dsh_portlet` longtext,
 `dsh_hide` text,
 `dsh_created_by` varchar(25) DEFAULT NULL,
 `dsh_modified_by` varchar(25) DEFAULT NULL,
 `dsh_created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `dsh_modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`dsh_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1

 *****************************************************************/

require_once 'KS/Search.php';

class KS_Dashboard extends KS_Search {

	protected $sqlTable = 'ks_dashboard';

	protected $id;
	protected $title;
	protected $desc;
	protected $portlet;
	protected $hide;
	protected $createdBy;
	protected $modifiedBy;
	protected $createdDate;
	protected $modifiedDate;

	// $this->search() related properties..
	// note that this method search() is defined/inherited from KS_Search class
	protected $searchSortField = 'dsh_id';

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
	 * This method returns a single record based on primary key $dsh_id
	 *
	 * @param none
	 * @return true
	 */
	public function select(){

		try {
			global $ks_db;
			global $ks_log;

			if (! isset ( $this->id )) {
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$dsh_id); in: " . __METHOD__;
				exit ();
			}

			//count how many rows found
			if($this->exists ()) {

				$sql = "SELECT * FROM $this->sqlTable WHERE dsh_id = ?";

				$stmt = $ks_db->query ( $sql, $this->id );

				//record is found, associate columns to the object properties
				while ( true == ($row = $stmt->fetch ()) ) {

					$this->id = $row ['dsh_id'];
					$this->title = $row ['dsh_title'];
					$this->desc = $row ['dsh_desc'];
					$this->portlet = $row ['dsh_portlet'];
					$this->hide = $row ['dsh_hide'];
					$this->createdBy = $row ['dsh_created_by'];
					$this->modifiedBy = $row ['dsh_modified_by'];
					$this->createdDate = $row ['dsh_created_date'];
					$this->modifiedDate = $row ['dsh_modified_date'];

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
	 * This method check whether the supplied primary key $dsh_id exists or not
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
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$dsh_id); in: " . __METHOD__;
				exit ();
			}

			$sql = "SELECT COUNT(*) as totalRow FROM $this->sqlTable WHERE dsh_id = ?";

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
				
			if (isset ( $this->title )) {
				$insertCols .= "dsh_title, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->title;
			}
			if (isset ( $this->desc )) {
				$insertCols .= "dsh_desc, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->desc;
			}
			if (isset ( $this->portlet )) {
				$insertCols .= "dsh_portlet, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->portlet;
			}
			if (isset ( $this->hide )) {
				$insertCols .= "dsh_hide, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->hide;
			}
			if (isset ( $this->createdBy )) {
				$insertCols .= "dsh_created_by, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->createdBy;
			}
			if (isset ( $this->modifiedBy )) {
				$insertCols .= "dsh_modified_by, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->modifiedBy;
			}
			if (isset ( $this->createdDate )) {
				$insertCols .= "dsh_created_date, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->createdDate;
			}
			if (isset ( $this->modifiedDate )) {
				$insertCols .= "dsh_modified_date, ";
				$insertVals .= "?, ";
				$arrBindings[] = $this->modifiedDate;
			}

			//remove trailing commas
			$insertCols = preg_replace("/, $/", "", $insertCols);
			$insertVals = preg_replace("/, $/", "", $insertVals);

			$sql = "INSERT INTO $this->sqlTable ($insertCols)";
			$sql .= " VALUES ($insertVals)";

			$ks_db->query ( $sql, $arrBindings );

			//set the auto-increment property, only if the primary column is auto-increment
			$this->id = $ks_db->lastInsertId();

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
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$dsh_id); in: " . __METHOD__;
				exit ();
			}

			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with primary key of ($this->id)";
				exit ();
			}

			$sql = "UPDATE $this->sqlTable SET ";

			if (isset ( $this->title )) {
				$sql .= "dsh_title = ?, ";
				$arrBindings[] = $this->title;
			}
			if (isset ( $this->desc )) {
				$sql .= "dsh_desc = ?, ";
				$arrBindings[] = $this->desc;
			}
			if (isset ( $this->portlet )) {
				$sql .= "dsh_portlet = ?, ";
				$arrBindings[] = $this->portlet;
			}
			if (isset ( $this->hide )) {
				$sql .= "dsh_hide = ?, ";
				$arrBindings[] = $this->hide;
			}
			if (isset ( $this->createdBy )) {
				$sql .= "dsh_created_by = ?, ";
				$arrBindings[] = $this->createdBy;
			}
			if (isset ( $this->modifiedBy )) {
				$sql .= "dsh_modified_by = ?, ";
				$arrBindings[] = $this->modifiedBy;
			}
			if (isset ( $this->createdDate )) {
				$sql .= "dsh_created_date = ?, ";
				$arrBindings[] = $this->createdDate;
			}
			if (isset ( $this->modifiedDate )) {
				$sql .= "dsh_modified_date = ?, ";
				$arrBindings[] = $this->modifiedDate;
			}

			$sql = preg_replace ( '/, $/', '', $sql);
			$sql .= " WHERE dsh_id = ?";
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
				echo "Fatal Error: Id is not set for the object! Please do \$objA->setId(\$dsh_id); in: " . __METHOD__;
				exit ();
			}

			//check if record exists
			if(! $this->exists ()) {
				echo "Fatal Error: No record found with primary key of ($this->id)";
				exit ();
			}

			$ks_db->beginTransaction ();

			$sql = "DELETE FROM $this->sqlTable ";
			$sql .= " WHERE dsh_id = ?";

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

				$objResult = new KS_Dashboard ( );

				$objResult->id = $row ['dsh_id'];
				$objResult->title = $row ['dsh_title'];
				$objResult->desc = $row ['dsh_desc'];
				$objResult->portlet = $row ['dsh_portlet'];
				$objResult->hide = $row ['dsh_hide'];
				$objResult->createdBy = $row ['dsh_created_by'];
				$objResult->modifiedBy = $row ['dsh_modified_by'];
				$objResult->createdDate = $row ['dsh_created_date'];
				$objResult->modifiedDate = $row ['dsh_modified_date'];

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
	 * @return int(11)
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return varchar(32)
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 * @return varchar(255)
	 */
	public function getDesc(){
		return $this->desc;
	}

	/**
	 * @return longtext
	 */
	public function getPortlet(){
		return $this->portlet;
	}

	/**
	 * @return text
	 */
	public function getHide(){
		return $this->hide;
	}

	/**
	 * @return varchar(25)
	 */
	public function getCreatedBy(){
		return $this->createdBy;
	}

	/**
	 * @return varchar(25)
	 */
	public function getModifiedBy(){
		return $this->modifiedBy;
	}

	/**
	 * @return datetime
	 */
	public function getCreatedDate(){
		return $this->createdDate;
	}

	/**
	 * @return datetime
	 */
	public function getModifiedDate(){
		return $this->modifiedDate;
	}

	/**
	 * @return string
	 */
	public function getSearchSortField() {
		return $this->searchSortField;
	}

	/**
	 * @param int(11) id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param varchar(32) title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param varchar(255) desc
	 */
	public function setDesc($desc) {
		$this->desc = $desc;
	}

	/**
	 * @param longtext portlet
	 */
	public function setPortlet($portlet) {
		$this->portlet = $portlet;
	}

	/**
	 * @param text hide
	 */
	public function setHide($hide) {
		$this->hide = $hide;
	}

	/**
	 * @param varchar(25) createdBy
	 */
	public function setCreatedBy($createdBy) {
		$this->createdBy = $createdBy;
	}

	/**
	 * @param varchar(25) modifiedBy
	 */
	public function setModifiedBy($modifiedBy) {
		$this->modifiedBy = $modifiedBy;
	}

	/**
	 * @param datetime createdDate
	 */
	public function setCreatedDate($createdDate) {
		$this->createdDate = $createdDate;
	}

	/**
	 * @param datetime modifiedDate
	 */
	public function setModifiedDate($modifiedDate) {
		$this->modifiedDate = $modifiedDate;
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

	public static function updateportlets($parameterp , $content ,$bColumns , $closedBoxes, $titleValue) {

		try {
			global $ks_log;

			$arrparameterp = explode('_', $parameterp);

			$actionp = $arrparameterp[0];
			$did = $arrparameterp[1];
			$pid = $arrparameterp[2];
			$typec = $arrparameterp[3];
			$cp = $arrparameterp[4];//from control panel

			if (session_id () == '') {
				session_start ();
			}
			$ks_session = CUSTOM_User::getSessionData ();
			$usr_id = $ks_session ['USR_ID'];
			$usr_name = $ks_session ['USR_NAME'];

			$today = date ( "Y-m-d" );

			$objDashboard = new KS_Dashboard();
			$objDashboard->setId($did);
			$objDashboard->select();
			$arrportlets = unserialize($objDashboard->getPortlet());
			//print_r($arrportlets);
			//exit();

			$objUser = new CUSTOM_User();
			$objUser->setId($usr_id);
			if (! $objUser->exists ()) {
				echo "The user with id ($usr_id) does not exist.";
				exit ();
			}
			$objUser->select();
			$arrportletsuser = unserialize($objUser->getOption());
			if(count($arrportletsuser)== 0){
				$arrportletsuser = array();
			}

			if($actionp == 'positionp'){

				if(count($arrportlets['']) == 0){

					$exb = explode("col", $bColumns);
					$arrCols = array();
					foreach ($exb as $curexb){

						if ($curexb != ''){
							$exbColumns = explode(",", $curexb);
							$countb = (count($exbColumns)) - 1;
							$j=0;
							for ($i = 1; $i <= $countb; $i++) {
								if($exbColumns[$i] != ''){
									$arrCols['col'.$exbColumns[0]][++$j]= $exbColumns[$i];
								}
							}
						}
					}

					if($cp == 1){
						$objDashboard = new KS_Dashboard();
						$objDashboard->setId($did);
						if (! $objDashboard->exists ()) {
							echo "The dashboard with id ($did) does not exist.";
							exit ();
						}
						$objDashboard->setPortlet(serialize($arrCols));
						$objDashboard->setModifiedBy($usr_id);
						$objDashboard->setModifiedDate($today);
						$objDashboard->update();

						$arrportletsuser[$did]= $arrCols ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();

					}else{

						$arrportletsuser[$did]= $arrCols ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
					}

				}else{

					$arrclosedb = $arrportlets[''];
					$end = array(''=> $arrclosedb);

					$exb = explode("col", $bColumns);
					$arrCols = array();
					foreach ($exb as $curexb){

						if ($curexb != ''){
							$exbColumns = explode(",", $curexb);
							$countb = (count($exbColumns)) - 1;
							$j=0;
							for ($i = 1; $i <= $countb; $i++) {
								if($exbColumns[$i] != ''){
									$arrCols['col'.$exbColumns[0]][++$j]= $exbColumns[$i];
								}
							}
						}
					}

					$result = array_merge((array)$arrCols, (array)$end);

					if($cp == 1){
						$objDashboard = new KS_Dashboard();
						$objDashboard->setId($did);
						if (! $objDashboard->exists ()) {
							echo "The dashboard with id ($did) does not exist.";
							exit ();
						}
						$objDashboard->setPortlet(serialize($result));
						$objDashboard->setModifiedBy($usr_id);
						$objDashboard->setModifiedDate($today);
						$objDashboard->update();

						$arrportletsuser[$did]= $result ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();

					}else{

						$arrportletsuser[$did]= $result ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
					}

				}


			}elseif ($actionp == 'propertiesp'){

				$expid = explode("config", $pid);
				$pidid = $expid[1];

				$arrportlets[''][$pidid]['type']= $typec ;
				$arrportlets[''][$pidid]['content']= $content ;

				$objDashboard = new KS_Dashboard();
				$objDashboard->setId($did);
				if (! $objDashboard->exists ()) {
					echo "The dashboard with id ($did) does not exist.";
					exit ();
				}
				$objDashboard->setPortlet(serialize($arrportlets));
				$objDashboard->setModifiedBy($usr_id);
				$objDashboard->setModifiedDate($today);

				$objDashboard->update();

				$arrportletsuser[$did]= $arrportlets ;

				$objUser = new CUSTOM_User();
				$objUser->setId($usr_id);
				if (! $objUser->exists ()) {
					echo "The user with id ($did) does not exist.";
					exit ();
				}
				$objUser->setOption(serialize($arrportletsuser));
				$objUser->update();

			}elseif ($actionp == 'hidep'){


				if(count($arrportlets['']['hide']) == 0){
					$boxs = explode(",", $closedBoxes);
					$countbox = 0;
					for ($b = 1; $b <= count($boxs); $b++) {
						$countb = $countbox++;
						if($boxs[$countb] != ''){
							$arrportlets['']['hide'][$b]= $boxs[$countb];
						}
					}

					if($cp == 1){
						$objDashboard = new KS_Dashboard();
						$objDashboard->setId($did);
						if (! $objDashboard->exists ()) {
							echo "The dashboard with id ($did) does not exist.";
							exit ();
						}
						$objDashboard->setPortlet(serialize($arrportlets));
						$objDashboard->setModifiedBy($usr_id);
						$objDashboard->setModifiedDate($today);
						$objDashboard->update();
							
						$arrportletsuser[$did]= $arrportlets ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
							
					}else{

						$arrportletsuser[$did]= $arrportlets ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
					}
				}else{
					unset($arrportlets['']['hide']);
					$boxs = explode(",", $closedBoxes);
					$countbox = 0;
					for ($b = 1; $b <= count($boxs); $b++) {
						$countb = $countbox++;
						if($boxs[$countb] != ''){
							$arrportlets['']['hide'][$b]= $boxs[$countb];
						}
					}

					if($cp == 1){
						$objDashboard = new KS_Dashboard();
						$objDashboard->setId($did);
						if (! $objDashboard->exists ()) {
							echo "The dashboard with id ($did) does not exist.";
							exit ();
						}
						$objDashboard->setPortlet(serialize($arrportlets));
						$objDashboard->setModifiedBy($usr_id);
						$objDashboard->setModifiedDate($today);
						$objDashboard->update();
							
						$arrportletsuser[$did]= $arrportlets ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
							
					}else{

						$arrportletsuser[$did]= $arrportlets ;
						$objUser = new CUSTOM_User();
						$objUser->setId($usr_id);
						if (! $objUser->exists ()) {
							echo "The user with id ($did) does not exist.";
							exit ();
						}
						$objUser->setOption(serialize($arrportletsuser));
						$objUser->update();
					}
				}

			}elseif ($actionp == 'deletep'){

				$expid = explode("close", $pid);
				$pidid = $expid[1];
				
				/*ks_dasboard*/
				foreach($arrportlets as $curbColumns => $box ){
					foreach($box as $curbox){
						if ($curbox == $pidid) {
							$key = array_search($pidid, $box);
							unset($arrportlets[$curbColumns][$key]);//removed box['col1'] => Array([1] => box-1)

							if(count($arrportlets['']) != 0){
								if(count($arrportlets['']['hide']) != 0){
									$key1 = array_search($pidid, $arrportlets['']['hide']);
									unset($arrportlets['']['hide'][$key1]);//removed hide [] => Array(['hide'] => Array([1] => box-1 )
								}
								unset($arrportlets[''][$pidid]);//removed content [] => Array([box-1] => Array ([type] => HTML[content] =>-Welcome-

							}
						}else{

						}
					}
				}
				/*ks_user(usr_option)*/
				$arrportletsuserdelete = $arrportletsuser[$did];
				foreach($arrportletsuserdelete as $curbColumnsuser => $boxuser ){
					foreach($boxuser as $curboxuser){
						if ($curboxuser == $pidid) {
							$keyuser = array_search($pidid, $boxuser);
							unset($arrportletsuserdelete[$curbColumnsuser][$keyuser]);//removed box['col1'] => Array([1] => box-1)

							if(count($arrportletsuserdelete['']) != 0){
								if(count($arrportletsuserdelete['']['hide']) != 0){
									$key1user = array_search($pidid, $arrportletsuserdelete['']['hide']);
									unset($arrportletsuserdelete['']['hide'][$key1user]);//removed hide [] => Array(['hide'] => Array([1] => box-1 )
								}
								unset($arrportletsuserdelete[''][$pidid]);//removed content [] => Array([box-1] => Array ([type] => HTML[content] =>-Welcome-

							}
						}else{

						}
					}
				}

				if($cp == 1){//changes from control panel
					$objDashboard = new KS_Dashboard();
					$objDashboard->setId ( $did );
					if (! $objDashboard->exists ()) {
						echo "The dashboard with id ($did) does not exist.";
						exit ();
					}
					$objDashboard->setPortlet(serialize($arrportlets));
					$objDashboard->setModifiedBy($usr_id);
					$objDashboard->setModifiedDate($today);
					$objDashboard->update();

					$arrportletsuser[$did]= $arrportletsuserdelete ;
					$objUser = new CUSTOM_User();
					$objUser->setId($usr_id);
					if (! $objUser->exists ()) {
						echo "The user with id ($did) does not exist.";
						exit ();
					}
					$objUser->setOption(serialize($arrportletsuser));
					$objUser->update();

				}else{

					$arrportletsuser[$did]= $arrportlets ;

					$objUser = new CUSTOM_User();
					$objUser->setId($usr_id);
					if (! $objUser->exists ()) {
						echo "The user with id ($did) does not exist.";
						exit ();
					}
					$objUser->setOption(serialize($arrportletsuser));
					$objUser->update();
				}

			}elseif ($actionp == 'titlep'){

				$expid = explode("title", $pid);
				$pidid = $expid[1];

				$arrportlets[''][$pidid]['title']= $titleValue ;

				$objDashboard = new KS_Dashboard();
				$objDashboard->setId ( $did );
				if (! $objDashboard->exists ()) {
					echo "The dashboard with id ($did) does not exist.";
					exit ();
				}
				$objDashboard->setPortlet(serialize($arrportlets));
				$objDashboard->setModifiedBy($usr_id);
				$objDashboard->setModifiedDate($today);
				$objDashboard->update();

				$arrportletsuser[$did]= $arrportlets ;
				$objUser = new CUSTOM_User();
				$objUser->setId($usr_id);
				if (! $objUser->exists ()) {
					echo "The user with id ($did) does not exist.";
					exit ();
				}
				$objUser->setOption(serialize($arrportletsuser));
				$objUser->update();


			}elseif($actionp == 'addp'){

				$countCols = 0;
				$countBoxs = 0;
				if($arrportlets){
					foreach ( $arrportlets as $curbColumns => $box ) {
						if($curbColumns != ''){
							$countCols = ++$countCols;
							foreach ( $box as $curbBox) {
								$countBoxs = ++$countBoxs;
							}
						}
					}
				}

				$noCols = $countCols;
				$noPortlets = $countBoxs;

				$nonewPortlet = $noPortlets + 1;
				global $newPortlet;
				$newPortlet = 'box-'.$nonewPortlet;

				$existance = KS_Dashboard::checkexist($arrportlets, $newPortlet);

				$noarraycol = (count($arrportlets['col1'])) + 1;
				$arrportlets['col1'][$noarraycol]= $existance;
				//add title as Portlet #
				$typeceven = "HTML";
				$contenteven = "<i>Please put your html code here.</i>";
				$arrportno = explode("-",$existance);
				$portno = $arrportno[1];
				$arrportlets[''][$existance]['type']= $typeceven ;
				$arrportlets[''][$existance]['content']= $contenteven ;
				$arrportlets[''][$existance]['title']= "Portlet ".$portno ;
				$newPortlet = $existance;

				$objDashboard = new KS_Dashboard();
				$objDashboard->setId ( $did );
				if (! $objDashboard->exists ()) {
					echo "The dashboard with id ($did) does not exist.";
					exit ();
				}
				$objDashboard->setPortlet(serialize($arrportlets));
				$objDashboard->setModifiedBy($usr_id);
				$objDashboard->setModifiedDate($today);
				$objDashboard->update();


				$arrportletsuser[$did]= $arrportlets ;
				$objUser = new CUSTOM_User();
				$objUser->setId($usr_id);
				if (! $objUser->exists ()) {
					echo "The user with id ($did) does not exist.";
					exit ();
				}
				$objUser->setOption(serialize($arrportletsuser));
				$objUser->update();

			}
		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
		}

	}

	public static function checkexist($arrportlets,$newPortlet) {

		foreach($arrportlets as $curbColumns => $box ){
			foreach($box as $curbox){
				if ($curbox == $newPortlet) {

					$expid = explode("-", $newPortlet);
					$pidid = $expid[1];
					$nonewPortlet = $pidid + 1;
					$newPortlet = 'box-'.$nonewPortlet;
					$existance = KS_Dashboard::checkexist($arrportlets, $newPortlet);
				}else{
					$existance = $newPortlet;
				}
			}
		}

		return $existance;

	}

	public static function display($did) {

		$ks_session = CUSTOM_User::getSessionData ();
		$usr_id = $ks_session ['USR_ID'];
		$usr_name = $ks_session ['USR_NAME'];

		$objUser = new CUSTOM_User();
		$objUser->setId($usr_id);
		if (! $objUser->exists ()) {
			echo "The User with id ($usr_id) does not exist.";
			exit ();
		}
		$objUser->select ();
		$ksoption = $objUser->getOption();

		if($ksoption != ''){
			$bColumns = unserialize($ksoption);

			if(@count($bColumns[$did])!= 0 ){
				$bColumns = $bColumns[$did];
				$exist = 1;
			}else{

				$objDashboard = new KS_Dashboard ();
				$objDashboard->setId ( $did );
				if ($objDashboard->exists ()) {
					$objDashboard->select ();
					$bColumns = unserialize($objDashboard->getPortlet());
					$exist = 1;
				}else{
					echo "The dashboard with id ($did) does not exist.";
					exit ();
				}

			}
		}else{

			$objDashboard = new KS_Dashboard ();
			$objDashboard->setId ( $did );
			if ($objDashboard->exists ()) {
				//echo "The dashboard with id ($did) does not exist.";
				//exit ();
				$objDashboard->select ();
				$bColumns = unserialize($objDashboard->getPortlet());
				$exist = 1;
			}

		}

		if ($exist == 1) {
			$countCols = 0;
			$countBoxs = 0;
			if($bColumns){
				foreach ( $bColumns as $curbColumns => $box ) {
					if($curbColumns != ''){
						$countCols = ++$countCols;
						foreach ( $box as $curbBox) {
							$countBoxs = ++$countBoxs;
						}
					}
				}
			}

			$noCols = $countCols;
			$noPortlets = $countBoxs;
			$span = 12 / $noCols;

			$arrhidebox = array();
			if(isset ($bColumns['']['hide'])) {
				$arrhidebox = $bColumns['']['hide'];
			}

			echo "<input type=\"hidden\" value=\"".KSCONFIG_URL."\" name=\"hiddenurl\" id=\"hiddenurl\" />";
			echo "<input type=\"hidden\" value=\"".$did."\" name=\"did\" id=\"did\" />";
			echo "<div class=\"row\" id=\"rowfluid\">";

			foreach ( $bColumns as $curbColumns => $box ) {
				if ($curbColumns != ''){

					if($noCols == 5){
						if($curbColumns == 'col1' || $curbColumns == 'col5' ){
							$span = 3;
						}else{
							$span = (int)(12 / $noCols);
						}
					}

					echo "<div class=\"col-12 col-sm-".$span." col-lg-".$span." column ui-sortable\" id=\"".$curbColumns."\">";
					foreach ( $box as $curbBox) {

						$classboxheader = "box-header round-top";
						$classicon = "glyphicon glyphicon-minus";
						$classboxcontainer = "box-container-toggle";

						if((count($arrhidebox)) > 0){
							if (in_array($curbBox, $arrhidebox)) {

								$classboxheader = "box-header round-all";
								$classicon = "glyphicon glyphicon-plus";
								$classboxcontainer = "box-container-toggle box-container-closed";

							}
						}

						echo "<div class=\"box\" id=\"".$curbBox."\">";
						echo "<h4 class=\"".$classboxheader."\">";
						if(($bColumns[''][$curbBox]['title']) != ''){ echo $bColumns[''][$curbBox]['title'];}else{echo $curbBox;}
						//echo "<a class=\"box-btn\" title=\"close\" id=\"close ".$curbBox."\"><i class=\"icon-remove\"></i></a>";
						echo "<a class=\"box-btn\" title=\"toggle\"><i class=\"".$classicon."\"></i></a></h4>";
						echo "<div class=\"".$classboxcontainer."\">
					<div class=\"box-content form-box\">
					<div align=\"center\">";
						$typec = $bColumns[''][$curbBox]['type'];
						if ( $typec == 'URL'){

							$contenturl = $bColumns[''][$curbBox]['content'];
							$newstrurl = str_replace("/","\/",KSCONFIG_URL);
							$deliurl = "/^".$newstrurl."/";
							if (preg_match($deliurl, $contenturl )){
								$urlinex = "internal";
								$arrurl = explode(KSCONFIG_URL,$contenturl);
								$contenturlin = $arrurl[1];
								$contentp =  "<iframe frameborder=\"0\" seamless=\"seamless\" width=\"98%\" height=\"300\" src=\"".KSCONFIG_URL."/".$contenturlin."\"></iframe>";
							}else{
								$urlinex = "external";
								$contenturlex = $contenturl;
								$contentp =  "<iframe frameborder=\"0\" seamless=\"seamless\" width=\"98%\" height=\"300\" src=\"".$contenturlex."\"></iframe>";
							}
							echo $contentp;
						}else if($typec == 'HTML'){
							echo $contentp = $bColumns[''][$curbBox]['content'];
						}else{
							echo $contentp = $bColumns[''][$curbBox]['content'];
						}
						echo "</div>
</div>
</div>
</div>";

					}
					echo "</div>";
				}
			}
			echo "</div>";


		}
		echo "</div>";
	}
}

