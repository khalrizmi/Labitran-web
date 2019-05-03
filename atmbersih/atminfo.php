<?php

// Global variable for table object
$atm = NULL;

//
// Table class for atm
//
class catm extends cTable {
	var $c_bank;
	var $c_cabang;
	var $c_bmi;
	var $atmid;
	var $n_atm;
	var $lokasi;
	var $lokasi_sebelum;
	var $areaid;
	var $kotaid;
	var $latitude;
	var $lontitude;
	var $c_cabang_bmi;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'atm';
		$this->TableName = 'atm';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`atm`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// c_bank
		$this->c_bank = new cField('atm', 'atm', 'x_c_bank', 'c_bank', '`c_bank`', '`c_bank`', 200, -1, FALSE, '`c_bank`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->c_bank->Sortable = TRUE; // Allow sort
		$this->c_bank->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->c_bank->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['c_bank'] = &$this->c_bank;

		// c_cabang
		$this->c_cabang = new cField('atm', 'atm', 'x_c_cabang', 'c_cabang', '`c_cabang`', '`c_cabang`', 200, -1, FALSE, '`c_cabang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->c_cabang->Sortable = TRUE; // Allow sort
		$this->fields['c_cabang'] = &$this->c_cabang;

		// c_bmi
		$this->c_bmi = new cField('atm', 'atm', 'x_c_bmi', 'c_bmi', '`c_bmi`', '`c_bmi`', 200, -1, FALSE, '`c_bmi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->c_bmi->Sortable = TRUE; // Allow sort
		$this->fields['c_bmi'] = &$this->c_bmi;

		// atmid
		$this->atmid = new cField('atm', 'atm', 'x_atmid', 'atmid', '`atmid`', '`atmid`', 200, -1, FALSE, '`atmid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->atmid->Sortable = TRUE; // Allow sort
		$this->fields['atmid'] = &$this->atmid;

		// n_atm
		$this->n_atm = new cField('atm', 'atm', 'x_n_atm', 'n_atm', '`n_atm`', '`n_atm`', 200, -1, FALSE, '`n_atm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->n_atm->Sortable = TRUE; // Allow sort
		$this->fields['n_atm'] = &$this->n_atm;

		// lokasi
		$this->lokasi = new cField('atm', 'atm', 'x_lokasi', 'lokasi', '`lokasi`', '`lokasi`', 200, -1, FALSE, '`lokasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lokasi->Sortable = TRUE; // Allow sort
		$this->fields['lokasi'] = &$this->lokasi;

		// lokasi_sebelum
		$this->lokasi_sebelum = new cField('atm', 'atm', 'x_lokasi_sebelum', 'lokasi_sebelum', '`lokasi_sebelum`', '`lokasi_sebelum`', 200, -1, FALSE, '`lokasi_sebelum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lokasi_sebelum->Sortable = TRUE; // Allow sort
		$this->fields['lokasi_sebelum'] = &$this->lokasi_sebelum;

		// areaid
		$this->areaid = new cField('atm', 'atm', 'x_areaid', 'areaid', '`areaid`', '`areaid`', 3, -1, FALSE, '`areaid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->areaid->Sortable = FALSE; // Allow sort
		$this->areaid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['areaid'] = &$this->areaid;

		// kotaid
		$this->kotaid = new cField('atm', 'atm', 'x_kotaid', 'kotaid', '`kotaid`', '`kotaid`', 3, -1, FALSE, '`kotaid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->kotaid->Sortable = TRUE; // Allow sort
		$this->kotaid->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->kotaid->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->kotaid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kotaid'] = &$this->kotaid;

		// latitude
		$this->latitude = new cField('atm', 'atm', 'x_latitude', 'latitude', '`latitude`', '`latitude`', 200, -1, FALSE, '`latitude`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->latitude->Sortable = TRUE; // Allow sort
		$this->fields['latitude'] = &$this->latitude;

		// lontitude
		$this->lontitude = new cField('atm', 'atm', 'x_lontitude', 'lontitude', '`lontitude`', '`lontitude`', 200, -1, FALSE, '`lontitude`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->lontitude->Sortable = TRUE; // Allow sort
		$this->fields['lontitude'] = &$this->lontitude;

		// c_cabang_bmi
		$this->c_cabang_bmi = new cField('atm', 'atm', 'x_c_cabang_bmi', 'c_cabang_bmi', '`c_cabang_bmi`', '`c_cabang_bmi`', 200, -1, FALSE, '`c_cabang_bmi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->c_cabang_bmi->Sortable = FALSE; // Allow sort
		$this->fields['c_cabang_bmi'] = &$this->c_cabang_bmi;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`atm`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('atmid', $rs))
				ew_AddFilter($where, ew_QuotedName('atmid', $this->DBID) . '=' . ew_QuotedValue($rs['atmid'], $this->atmid->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`atmid` = '@atmid@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (is_null($this->atmid->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@atmid@", ew_AdjustSql($this->atmid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "atmlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "atmview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "atmedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "atmadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "atmlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("atmview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("atmview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "atmadd.php?" . $this->UrlParm($parm);
		else
			$url = "atmadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("atmedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("atmadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("atmdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "atmid:" . ew_VarToJson($this->atmid->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->atmid->CurrentValue)) {
			$sUrl .= "atmid=" . urlencode($this->atmid->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["atmid"]))
				$arKeys[] = $_POST["atmid"];
			elseif (isset($_GET["atmid"]))
				$arKeys[] = $_GET["atmid"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->atmid->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->c_bank->setDbValue($rs->fields('c_bank'));
		$this->c_cabang->setDbValue($rs->fields('c_cabang'));
		$this->c_bmi->setDbValue($rs->fields('c_bmi'));
		$this->atmid->setDbValue($rs->fields('atmid'));
		$this->n_atm->setDbValue($rs->fields('n_atm'));
		$this->lokasi->setDbValue($rs->fields('lokasi'));
		$this->lokasi_sebelum->setDbValue($rs->fields('lokasi_sebelum'));
		$this->areaid->setDbValue($rs->fields('areaid'));
		$this->kotaid->setDbValue($rs->fields('kotaid'));
		$this->latitude->setDbValue($rs->fields('latitude'));
		$this->lontitude->setDbValue($rs->fields('lontitude'));
		$this->c_cabang_bmi->setDbValue($rs->fields('c_cabang_bmi'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// c_bank
		// c_cabang
		// c_bmi
		// atmid
		// n_atm
		// lokasi
		// lokasi_sebelum
		// areaid

		$this->areaid->CellCssStyle = "white-space: nowrap;";

		// kotaid
		// latitude
		// lontitude
		// c_cabang_bmi

		$this->c_cabang_bmi->CellCssStyle = "white-space: nowrap;";

		// c_bank
		if (strval($this->c_bank->CurrentValue) <> "") {
			$sFilterWrk = "`c_bank`" . ew_SearchString("=", $this->c_bank->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `c_bank`, `c_bank` AS `DispFld`, `n_bank` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
		$sWhereWrk = "";
		$this->c_bank->LookupFilters = array("dx1" => '`c_bank`', "dx2" => '`n_bank`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->c_bank->ViewValue = $this->c_bank->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->c_bank->ViewValue = $this->c_bank->CurrentValue;
			}
		} else {
			$this->c_bank->ViewValue = NULL;
		}
		$this->c_bank->ViewCustomAttributes = "";

		// c_cabang
		$this->c_cabang->ViewValue = $this->c_cabang->CurrentValue;
		$this->c_cabang->ViewCustomAttributes = "";

		// c_bmi
		$this->c_bmi->ViewValue = $this->c_bmi->CurrentValue;
		$this->c_bmi->ViewCustomAttributes = "";

		// atmid
		$this->atmid->ViewValue = $this->atmid->CurrentValue;
		$this->atmid->ViewCustomAttributes = "";

		// n_atm
		$this->n_atm->ViewValue = $this->n_atm->CurrentValue;
		$this->n_atm->ViewCustomAttributes = "";

		// lokasi
		$this->lokasi->ViewValue = $this->lokasi->CurrentValue;
		$this->lokasi->ViewCustomAttributes = "";

		// lokasi_sebelum
		$this->lokasi_sebelum->ViewValue = $this->lokasi_sebelum->CurrentValue;
		$this->lokasi_sebelum->ViewCustomAttributes = "";

		// areaid
		$this->areaid->ViewValue = $this->areaid->CurrentValue;
		if (strval($this->areaid->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->areaid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `n_area` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `area`";
		$sWhereWrk = "";
		$this->areaid->LookupFilters = array("dx1" => '`n_area`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->areaid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->areaid->ViewValue = $this->areaid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->areaid->ViewValue = $this->areaid->CurrentValue;
			}
		} else {
			$this->areaid->ViewValue = NULL;
		}
		$this->areaid->ViewCustomAttributes = "";

		// kotaid
		if (strval($this->kotaid->CurrentValue) <> "") {
			$sFilterWrk = "`kabupatenid`" . ew_SearchString("=", $this->kotaid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `kabupatenid`, `lokasi_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `kabupaten`";
		$sWhereWrk = "";
		$this->kotaid->LookupFilters = array("dx1" => '`lokasi_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->kotaid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->kotaid->ViewValue = $this->kotaid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->kotaid->ViewValue = $this->kotaid->CurrentValue;
			}
		} else {
			$this->kotaid->ViewValue = NULL;
		}
		$this->kotaid->ViewCustomAttributes = "";

		// latitude
		$this->latitude->ViewValue = $this->latitude->CurrentValue;
		$this->latitude->ViewCustomAttributes = "";

		// lontitude
		$this->lontitude->ViewValue = $this->lontitude->CurrentValue;
		$this->lontitude->ViewCustomAttributes = "";

		// c_cabang_bmi
		$this->c_cabang_bmi->ViewValue = $this->c_cabang_bmi->CurrentValue;
		$this->c_cabang_bmi->ViewCustomAttributes = "";

		// c_bank
		$this->c_bank->LinkCustomAttributes = "";
		$this->c_bank->HrefValue = "";
		$this->c_bank->TooltipValue = "";

		// c_cabang
		$this->c_cabang->LinkCustomAttributes = "";
		$this->c_cabang->HrefValue = "";
		$this->c_cabang->TooltipValue = "";

		// c_bmi
		$this->c_bmi->LinkCustomAttributes = "";
		$this->c_bmi->HrefValue = "";
		$this->c_bmi->TooltipValue = "";

		// atmid
		$this->atmid->LinkCustomAttributes = "";
		$this->atmid->HrefValue = "";
		$this->atmid->TooltipValue = "";

		// n_atm
		$this->n_atm->LinkCustomAttributes = "";
		$this->n_atm->HrefValue = "";
		$this->n_atm->TooltipValue = "";

		// lokasi
		$this->lokasi->LinkCustomAttributes = "";
		$this->lokasi->HrefValue = "";
		$this->lokasi->TooltipValue = "";

		// lokasi_sebelum
		$this->lokasi_sebelum->LinkCustomAttributes = "";
		$this->lokasi_sebelum->HrefValue = "";
		$this->lokasi_sebelum->TooltipValue = "";

		// areaid
		$this->areaid->LinkCustomAttributes = "";
		$this->areaid->HrefValue = "";
		$this->areaid->TooltipValue = "";

		// kotaid
		$this->kotaid->LinkCustomAttributes = "";
		$this->kotaid->HrefValue = "";
		$this->kotaid->TooltipValue = "";

		// latitude
		$this->latitude->LinkCustomAttributes = "";
		$this->latitude->HrefValue = "";
		$this->latitude->TooltipValue = "";

		// lontitude
		$this->lontitude->LinkCustomAttributes = "";
		$this->lontitude->HrefValue = "";
		$this->lontitude->TooltipValue = "";

		// c_cabang_bmi
		$this->c_cabang_bmi->LinkCustomAttributes = "";
		$this->c_cabang_bmi->HrefValue = "";
		$this->c_cabang_bmi->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// c_bank
		$this->c_bank->EditAttrs["class"] = "form-control";
		$this->c_bank->EditCustomAttributes = "";

		// c_cabang
		$this->c_cabang->EditAttrs["class"] = "form-control";
		$this->c_cabang->EditCustomAttributes = "";
		$this->c_cabang->EditValue = $this->c_cabang->CurrentValue;
		$this->c_cabang->PlaceHolder = ew_RemoveHtml($this->c_cabang->FldCaption());

		// c_bmi
		$this->c_bmi->EditAttrs["class"] = "form-control";
		$this->c_bmi->EditCustomAttributes = "";
		$this->c_bmi->EditValue = $this->c_bmi->CurrentValue;
		$this->c_bmi->PlaceHolder = ew_RemoveHtml($this->c_bmi->FldCaption());

		// atmid
		$this->atmid->EditAttrs["class"] = "form-control";
		$this->atmid->EditCustomAttributes = "";
		$this->atmid->EditValue = $this->atmid->CurrentValue;
		$this->atmid->ViewCustomAttributes = "";

		// n_atm
		$this->n_atm->EditAttrs["class"] = "form-control";
		$this->n_atm->EditCustomAttributes = "";
		$this->n_atm->EditValue = $this->n_atm->CurrentValue;
		$this->n_atm->PlaceHolder = ew_RemoveHtml($this->n_atm->FldCaption());

		// lokasi
		$this->lokasi->EditAttrs["class"] = "form-control";
		$this->lokasi->EditCustomAttributes = "";
		$this->lokasi->EditValue = $this->lokasi->CurrentValue;
		$this->lokasi->PlaceHolder = ew_RemoveHtml($this->lokasi->FldCaption());

		// lokasi_sebelum
		$this->lokasi_sebelum->EditAttrs["class"] = "form-control";
		$this->lokasi_sebelum->EditCustomAttributes = "";
		$this->lokasi_sebelum->EditValue = $this->lokasi_sebelum->CurrentValue;
		$this->lokasi_sebelum->PlaceHolder = ew_RemoveHtml($this->lokasi_sebelum->FldCaption());

		// areaid
		$this->areaid->EditAttrs["class"] = "form-control";
		$this->areaid->EditCustomAttributes = "";
		$this->areaid->EditValue = $this->areaid->CurrentValue;
		$this->areaid->PlaceHolder = ew_RemoveHtml($this->areaid->FldCaption());

		// kotaid
		$this->kotaid->EditAttrs["class"] = "form-control";
		$this->kotaid->EditCustomAttributes = "";

		// latitude
		$this->latitude->EditAttrs["class"] = "form-control";
		$this->latitude->EditCustomAttributes = "";
		$this->latitude->EditValue = $this->latitude->CurrentValue;
		$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

		// lontitude
		$this->lontitude->EditAttrs["class"] = "form-control";
		$this->lontitude->EditCustomAttributes = "";
		$this->lontitude->EditValue = $this->lontitude->CurrentValue;
		$this->lontitude->PlaceHolder = ew_RemoveHtml($this->lontitude->FldCaption());

		// c_cabang_bmi
		$this->c_cabang_bmi->EditAttrs["class"] = "form-control";
		$this->c_cabang_bmi->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->c_bank->Exportable) $Doc->ExportCaption($this->c_bank);
					if ($this->c_cabang->Exportable) $Doc->ExportCaption($this->c_cabang);
					if ($this->c_bmi->Exportable) $Doc->ExportCaption($this->c_bmi);
					if ($this->atmid->Exportable) $Doc->ExportCaption($this->atmid);
					if ($this->n_atm->Exportable) $Doc->ExportCaption($this->n_atm);
					if ($this->lokasi->Exportable) $Doc->ExportCaption($this->lokasi);
					if ($this->lokasi_sebelum->Exportable) $Doc->ExportCaption($this->lokasi_sebelum);
					if ($this->kotaid->Exportable) $Doc->ExportCaption($this->kotaid);
					if ($this->latitude->Exportable) $Doc->ExportCaption($this->latitude);
					if ($this->lontitude->Exportable) $Doc->ExportCaption($this->lontitude);
				} else {
					if ($this->c_bank->Exportable) $Doc->ExportCaption($this->c_bank);
					if ($this->c_cabang->Exportable) $Doc->ExportCaption($this->c_cabang);
					if ($this->c_bmi->Exportable) $Doc->ExportCaption($this->c_bmi);
					if ($this->atmid->Exportable) $Doc->ExportCaption($this->atmid);
					if ($this->n_atm->Exportable) $Doc->ExportCaption($this->n_atm);
					if ($this->lokasi->Exportable) $Doc->ExportCaption($this->lokasi);
					if ($this->lokasi_sebelum->Exportable) $Doc->ExportCaption($this->lokasi_sebelum);
					if ($this->kotaid->Exportable) $Doc->ExportCaption($this->kotaid);
					if ($this->latitude->Exportable) $Doc->ExportCaption($this->latitude);
					if ($this->lontitude->Exportable) $Doc->ExportCaption($this->lontitude);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->c_bank->Exportable) $Doc->ExportField($this->c_bank);
						if ($this->c_cabang->Exportable) $Doc->ExportField($this->c_cabang);
						if ($this->c_bmi->Exportable) $Doc->ExportField($this->c_bmi);
						if ($this->atmid->Exportable) $Doc->ExportField($this->atmid);
						if ($this->n_atm->Exportable) $Doc->ExportField($this->n_atm);
						if ($this->lokasi->Exportable) $Doc->ExportField($this->lokasi);
						if ($this->lokasi_sebelum->Exportable) $Doc->ExportField($this->lokasi_sebelum);
						if ($this->kotaid->Exportable) $Doc->ExportField($this->kotaid);
						if ($this->latitude->Exportable) $Doc->ExportField($this->latitude);
						if ($this->lontitude->Exportable) $Doc->ExportField($this->lontitude);
					} else {
						if ($this->c_bank->Exportable) $Doc->ExportField($this->c_bank);
						if ($this->c_cabang->Exportable) $Doc->ExportField($this->c_cabang);
						if ($this->c_bmi->Exportable) $Doc->ExportField($this->c_bmi);
						if ($this->atmid->Exportable) $Doc->ExportField($this->atmid);
						if ($this->n_atm->Exportable) $Doc->ExportField($this->n_atm);
						if ($this->lokasi->Exportable) $Doc->ExportField($this->lokasi);
						if ($this->lokasi_sebelum->Exportable) $Doc->ExportField($this->lokasi_sebelum);
						if ($this->kotaid->Exportable) $Doc->ExportField($this->kotaid);
						if ($this->latitude->Exportable) $Doc->ExportField($this->latitude);
						if ($this->lontitude->Exportable) $Doc->ExportField($this->lontitude);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
