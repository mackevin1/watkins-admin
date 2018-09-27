<?php

class KS_Search {

	protected $arraySearchFields = array ();
	protected $searchSqlWhere = '';
	protected $searchRowCount = 0;
	protected $searchRecordsPerPage = 1000;
	protected $searchSortOrder = 'ASC';
	protected $searchSortField = '';
	protected $searchPage = 0;
	protected $searchQueryString = '';

	protected $searchSQL = '';
	protected $searchTotalSQL = '';
	protected $searchBinding = array ();

	private $searchStringPage = 'Halaman';
	private $searchStringDisplaying = 'Dipaparkan';
	private $searchStringOf = 'drpd.';
	private $searchStringRecord = 'rekod';
	private $searchStringAllRecord = 'Semua';

	private $searchIconUpHighlight = '';
	private $searchIconUp = '';
	private $searchIconDownHighlight = '';
	private $searchIconDown = '';

	protected $baseFilename = '';
	protected $iconPath = ''; //deprecated.. dont use this property .. used to be../ks_images/icons_arrow/';

	private $currentPageIndex = 0;
	private $prevPageIndex = 0;

	/**
	 * This method perform search based on this object.
	 *
	 * @param none
	 * @return array $class
	 */
	public function initSearch() {

		try {
			global $ks_db;
			global $ks_log;

			$this->baseFilename = basename ( $_SERVER ['SCRIPT_FILENAME'] );

			//store found objects as an array collection of objects
			$arrSearchResults = array ();

			//store found records as an array collection of objects, in this case, array
			$arrSearchResults = array ();

			$searchClause = '';
			
			//if sqlWhere clause is specified, use it
			if (trim ( $this->searchSQL ) != '') {
				$sql = $this->searchSQL;
			} else if (trim ( $this->searchSqlWhere ) != '') {

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
				//$arrIgnoreValues [] = '';
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
						$this->searchBinding [] = $searchValue;
					}
				}

				$sql = "SELECT * FROM " . $this->sqlTable;
				$sql .= " WHERE $searchClause";
			}

			/**
			 * This properties will be used by descendents in their search() method
			 */
			//we need to know in total, how many (before we limit the return by LIMIT )??
			$this->searchTotalSQL = $sql;

			if (trim ( $this->searchSortField )) {
				$sql .= " ORDER BY {$this->searchSortField} {$this->searchSortOrder} ";
			}

			$pageStart = $this->searchPage * $this->searchRecordsPerPage;
			if ($this->searchRecordsPerPage <= $pageStart) {
				//$pageStart = 0;
			}
			$sql .= " LIMIT $pageStart, {$this->searchRecordsPerPage}";

			/**
			 * This properties will be used by descendents in their search() method
			 */
			$this->searchSQL = $sql;

			$stmtTotal = $ks_db->query ( $this->searchTotalSQL, $this->searchBinding );

			//count how many rows found
			//$this->searchRowCount = $stmtTotal->rowCount ();


			while ( true == ($row = $stmtTotal->fetch ()) ) {
				$this->searchRowCount += 1;
			}

		} catch ( Exception $e ) {
			$ks_log->info ( 'Fatal Error: ' . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage () );
			$ks_log->info ( '<br>SQL Statement: ' . $sql );
			echo "Fatal Error: " . __CLASS__ . '::' . __METHOD__ . '. ' . $e->getMessage ();
			echo "SQL Statement: " . $sql;
		}
	}

	/**
	 * This function performs initiation, calculating all the values after properties are set.
	 * For example, $currentPageIndex = $page * $resultsPerPage;
	 * $prevPageIndex = (int) ($page - 1) * $resultsPerPage;
	 *
	 */
	private function init() {
		$this->currentPageIndex = $this->searchPage * $this->searchRecordsPerPage;
		$this->prevPageIndex = ( int ) ($this->searchPage - 1) * $this->searchRecordsPerPage;

		//To be save,let's append & in front of Querystring .. as this may mess up the URL
		if (! preg_match ( "/^&/", $this->searchQueryString )) {
			$this->searchQueryString = "&" . $this->searchQueryString;
		}
	}

	/**
	 * This funtion generates the arrow up and down
	 * @param $sortField: What field to sort
	 * @param $sortOrder: What order to use
	 * @param $sURL: What URL to navigate when clicked. It must not contain $sSortOrder
	 */
	public function displaySearchColumnHeader($sortField, $sHeadingText) {

		$this->init ();

		$strReturn = $sHeadingText;

		$strReturnDesc = "<a href=\"$this->baseFilename?sSortField=$sortField&sSortOrder=DESC" . $this->searchQueryString . "\">";
		$strReturnDesc .= '<img src="' . $this->iconPath . 'down.gif" alt="Sort Descending" border="0" />';
		$strReturnDesc .= '</a>';

		$strReturnDescDark = '<img src="' . $this->iconPath . 'down_dark.gif" alt="Currently Sorted in Descending Order" border="0" />';

		$strReturnAsc = "<a href=\"$this->baseFilename?sSortField=$sortField&sSortOrder=ASC" . $this->searchQueryString . "\">";
		$strReturnAsc .= '<img src="' . $this->iconPath . 'up.gif" alt="Sort Ascending" border="0" />';
		$strReturnAsc .= '</a>';

		$strReturnAscDark = '<img src="' . $this->iconPath . 'up_dark.gif" alt="Currently Sorted in Ascending Order" border="0" />';

		/*If sorting by other field, show both up&down icons */
		if ($sortField != $this->searchSortField) {

			$strReturn = $sHeadingText . "&nbsp;" . $strReturnAsc . "&nbsp;" . $strReturnDesc;

		} else { /*However, if current field is being sorted, only show inverse icons*/

			if ($this->searchSortOrder == 'ASC') {
				$strReturn = $sHeadingText . "&nbsp;" . $strReturnAscDark . "&nbsp;" . $strReturnDesc;
			} else {
				$strReturn = $sHeadingText . "&nbsp;" . $strReturnAsc . "&nbsp;" . $strReturnDescDark;
			}

		}

		return $strReturn;

	}

	public function displaySearchNavigatorMessage() {

		$this->init ();

		$currentCounter = $this->currentPageIndex + 1;
		$minCounter = ($currentCounter < $this->searchRowCount) ? $currentCounter : $this->searchRowCount;
		$maxCounter = ($this->searchRowCount < ($this->currentPageIndex + $this->searchRecordsPerPage)) ? 
				$this->searchRowCount : ($this->currentPageIndex + $this->searchRecordsPerPage);

		$strReturn = $this->searchStringDisplaying . " <strong>$minCounter</strong> - <strong>$maxCounter</strong> ";
		$strReturn .= $this->searchStringOf . " <strong>". number_format( $this->searchRowCount) . "</strong> " . $this->searchStringRecord;

		return $strReturn;
	}

	/**
	 * This function displays the navigator dropdown.
	 */
	public function displaySearchNavigatorDropdown() {
		$this->init ();

		//now build number of result pages
		$strPage = "";

		//to handle all records
		if ($this->searchRecordsPerPage > 0) {

			$strPage .= $this->searchStringPage . " ";
			$strPage .= "\n<select name=\"pageNavigator\" class=\"form-control\" style='width:auto;display:inline;' 
					onChange=\"location.href=this.value;\">";

			for($i = 0; $i < ceil ( $this->searchRowCount / $this->searchRecordsPerPage ); $i ++) {

				$strSelected = ($i == $this->searchPage) ? 'selected' : '';
				$strPage .= "\n\t<option value=\"" . $this->baseFilename . "?sPage=$i&sRecordsPerPage=" . $this->getSearchRecordsPerPage ();
				$strPage .= "&sSortField={$this->searchSortField}&sSortOrder={$this->searchSortOrder}" . $this->searchQueryString;
				$strPage .= "\" $strSelected>" . ($i + 1) . "</option>";
			}

			$strPage .= "\n</select>";
		}

		return $strPage;
	}

	/**
	 * This function generates the prev button, if required by logic.
	 */
	public function displaySearchPrevButton() {

		$this->init ();

		$strPrevButton = "";
		if ($this->currentPageIndex > $this->prevPageIndex && ($this->searchPage > 0)) {
			$strPrevButton = "<input type=\"button\" name=\"btnPrev\" value=\"&lt;&lt;\" class=\"btn btn-default\"";
			$strPrevButton .= "onClick=\"location.href='" . $this->baseFilename . "?sPage=" . ( int ) ($this->searchPage - 1) . "&sRecordsPerPage=";
			$strPrevButton .= $this->getSearchRecordsPerPage () . "&sSortField={$this->sortField}&sSortOrder={$this->sortOrder}&";
			$strPrevButton .= $this->searchQueryString . "';\">";
		} else {
			$strPrevButton = "";
		}

		return $strPrevButton;
	}

	/**
	 * This function generates the next button, if required by logic
	 */
	public function displaySearchNextButton() {

		$this->init ();

		$strNextButton = "";

		if ($this->searchRecordsPerPage > 0) {

			if (ceil ( $this->searchRowCount / $this->searchRecordsPerPage ) > ($this->searchPage + 1)) {
				
				$strNextButton = "<input type=\"button\" name=\"btnNext\" value=\"&gt;&gt;\" class=\"btn btn-default\"";
				$strNextButton .= "	onClick=\"location.href='" . $this->baseFilename . "?sPage=" . ($this->searchPage + 1);
				$strNextButton .= "&sRecordsPerPage=" . $this->getSearchRecordsPerPage () . "&sSortField={$this->sortField}&sSortOrder={$this->sortOrder}&";
				$strNextButton .= $this->searchQueryString . "';\">";
			}
		}

		return $strNextButton;
	}

	public function displaySearchOptionRecordsPerPage($sRecordsPerPage) {

		$strReturn = "\n<select name=\"sRecordsPerPage\" class=\"form-control\" ";
		$strReturn .= "onChange=\"location.href='" . $this->baseFilename . "?sPage=" . $this->getPage ();
		$strReturn .= "&sSortField={$this->sortField}&sSortOrder={$this->sortOrder}&" . $this->searchQueryString . "&sRecordsPerPage=' + this.value;\">";

		$arrOptions = array (5, 10, 20, 25, 50, 100, "All" );

		if (! ( int ) $sRecordsPerPage) {
			$sRecordsPerPage = 1000; //default to 1000.. previously 10
		}

		foreach ( $arrOptions as $curOption ) {

			$selected = ($sRecordsPerPage == $curOption) ? "selected" : "";

			$strReturn .= "\n<option value=\"$curOption\" $selected>$curOption</option>";
		}

		$strReturn .= "\n</select>";

		return $strReturn;
	}

	/**
	 * @return int
	 */
	public function getSearchPage() {
		return $this->searchPage;
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
	 * @return array
	 */
	public function getArraySearchFields() {
		return $this->arraySearchFields;
	}

	/**
	 * @param array $arraySearchFields
	 */
	public function setArraySearchFields($arraySearchFields) {
		$this->arraySearchFields [] = $arraySearchFields;
	}

	/**
	 * @param int $searchPage
	 */
	public function setSearchPage($searchPage) {
		$this->searchPage = $searchPage;
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
	 * @return unknown
	 */
	public function getSearchQueryString() {
		return $this->searchQueryString;
	}

	/**
	 * @param unknown_type $searchQueryString
	 */
	public function setSearchQueryString($searchQueryString) {
		$this->searchQueryString = $searchQueryString;
	}
	/**
	 * @return unknown
	 */
	public function getSearchSQL() {
		return $this->searchSQL;
	}

	/**
	 * @param unknown_type $searchSQL
	 */
	public function setSearchSQL($searchSQL) {
		$this->searchSQL = $searchSQL;
	}

	/**
	 * @param unknown_type $baseFilename
	 */
	public function setBaseFilename($baseFilename) {
		$this->baseFilename = $baseFilename;
	}

	/**
	 * @param unknown_type $iconPath
	 */
	public function setSearchIconPath($iconPath) {
		$this->iconPath = $iconPath;
	}

}

?>