<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "atminfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$atm_delete = NULL; // Initialize page object first

class catm_delete extends catm {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'atm';

	// Page object name
	var $PageObjName = 'atm_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (atm)
		if (!isset($GLOBALS["atm"]) || get_class($GLOBALS["atm"]) == "catm") {
			$GLOBALS["atm"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["atm"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'atm', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("atmlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->c_bank->SetVisibility();
		$this->c_bmi->SetVisibility();
		$this->atmid->SetVisibility();
		$this->n_atm->SetVisibility();
		$this->lokasi->SetVisibility();
		$this->kotaid->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $atm;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($atm);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("atmlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in atm class, atminfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("atmlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->c_bank->setDbValue($row['c_bank']);
		$this->c_cabang->setDbValue($row['c_cabang']);
		$this->c_bmi->setDbValue($row['c_bmi']);
		$this->atmid->setDbValue($row['atmid']);
		$this->n_atm->setDbValue($row['n_atm']);
		$this->lokasi->setDbValue($row['lokasi']);
		$this->lokasi_sebelum->setDbValue($row['lokasi_sebelum']);
		$this->areaid->setDbValue($row['areaid']);
		$this->kotaid->setDbValue($row['kotaid']);
		$this->latitude->setDbValue($row['latitude']);
		$this->lontitude->setDbValue($row['lontitude']);
		$this->c_cabang_bmi->setDbValue($row['c_cabang_bmi']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['c_bank'] = NULL;
		$row['c_cabang'] = NULL;
		$row['c_bmi'] = NULL;
		$row['atmid'] = NULL;
		$row['n_atm'] = NULL;
		$row['lokasi'] = NULL;
		$row['lokasi_sebelum'] = NULL;
		$row['areaid'] = NULL;
		$row['kotaid'] = NULL;
		$row['latitude'] = NULL;
		$row['lontitude'] = NULL;
		$row['c_cabang_bmi'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->c_bank->DbValue = $row['c_bank'];
		$this->c_cabang->DbValue = $row['c_cabang'];
		$this->c_bmi->DbValue = $row['c_bmi'];
		$this->atmid->DbValue = $row['atmid'];
		$this->n_atm->DbValue = $row['n_atm'];
		$this->lokasi->DbValue = $row['lokasi'];
		$this->lokasi_sebelum->DbValue = $row['lokasi_sebelum'];
		$this->areaid->DbValue = $row['areaid'];
		$this->kotaid->DbValue = $row['kotaid'];
		$this->latitude->DbValue = $row['latitude'];
		$this->lontitude->DbValue = $row['lontitude'];
		$this->c_cabang_bmi->DbValue = $row['c_cabang_bmi'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// c_bank
			$this->c_bank->LinkCustomAttributes = "";
			$this->c_bank->HrefValue = "";
			$this->c_bank->TooltipValue = "";

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

			// kotaid
			$this->kotaid->LinkCustomAttributes = "";
			$this->kotaid->HrefValue = "";
			$this->kotaid->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['atmid'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("atmlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($atm_delete)) $atm_delete = new catm_delete();

// Page init
$atm_delete->Page_Init();

// Page main
$atm_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atm_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fatmdelete = new ew_Form("fatmdelete", "delete");

// Form_CustomValidate event
fatmdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fatmdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fatmdelete.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_c_bank","x_n_bank","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fatmdelete.Lists["x_c_bank"].Data = "<?php echo $atm_delete->c_bank->LookupFilterQuery(FALSE, "delete") ?>";
fatmdelete.Lists["x_kotaid"] = {"LinkField":"x_kabupatenid","Ajax":true,"AutoFill":false,"DisplayFields":["x_lokasi_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"kabupaten"};
fatmdelete.Lists["x_kotaid"].Data = "<?php echo $atm_delete->kotaid->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $atm_delete->ShowPageHeader(); ?>
<?php
$atm_delete->ShowMessage();
?>
<form name="fatmdelete" id="fatmdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($atm_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $atm_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="atm">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($atm_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($atm->c_bank->Visible) { // c_bank ?>
		<th class="<?php echo $atm->c_bank->HeaderCellClass() ?>"><span id="elh_atm_c_bank" class="atm_c_bank"><?php echo $atm->c_bank->FldCaption() ?></span></th>
<?php } ?>
<?php if ($atm->c_bmi->Visible) { // c_bmi ?>
		<th class="<?php echo $atm->c_bmi->HeaderCellClass() ?>"><span id="elh_atm_c_bmi" class="atm_c_bmi"><?php echo $atm->c_bmi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($atm->atmid->Visible) { // atmid ?>
		<th class="<?php echo $atm->atmid->HeaderCellClass() ?>"><span id="elh_atm_atmid" class="atm_atmid"><?php echo $atm->atmid->FldCaption() ?></span></th>
<?php } ?>
<?php if ($atm->n_atm->Visible) { // n_atm ?>
		<th class="<?php echo $atm->n_atm->HeaderCellClass() ?>"><span id="elh_atm_n_atm" class="atm_n_atm"><?php echo $atm->n_atm->FldCaption() ?></span></th>
<?php } ?>
<?php if ($atm->lokasi->Visible) { // lokasi ?>
		<th class="<?php echo $atm->lokasi->HeaderCellClass() ?>"><span id="elh_atm_lokasi" class="atm_lokasi"><?php echo $atm->lokasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($atm->kotaid->Visible) { // kotaid ?>
		<th class="<?php echo $atm->kotaid->HeaderCellClass() ?>"><span id="elh_atm_kotaid" class="atm_kotaid"><?php echo $atm->kotaid->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$atm_delete->RecCnt = 0;
$i = 0;
while (!$atm_delete->Recordset->EOF) {
	$atm_delete->RecCnt++;
	$atm_delete->RowCnt++;

	// Set row properties
	$atm->ResetAttrs();
	$atm->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$atm_delete->LoadRowValues($atm_delete->Recordset);

	// Render row
	$atm_delete->RenderRow();
?>
	<tr<?php echo $atm->RowAttributes() ?>>
<?php if ($atm->c_bank->Visible) { // c_bank ?>
		<td<?php echo $atm->c_bank->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_c_bank" class="atm_c_bank">
<span<?php echo $atm->c_bank->ViewAttributes() ?>>
<?php echo $atm->c_bank->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($atm->c_bmi->Visible) { // c_bmi ?>
		<td<?php echo $atm->c_bmi->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_c_bmi" class="atm_c_bmi">
<span<?php echo $atm->c_bmi->ViewAttributes() ?>>
<?php echo $atm->c_bmi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($atm->atmid->Visible) { // atmid ?>
		<td<?php echo $atm->atmid->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_atmid" class="atm_atmid">
<span<?php echo $atm->atmid->ViewAttributes() ?>>
<?php echo $atm->atmid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($atm->n_atm->Visible) { // n_atm ?>
		<td<?php echo $atm->n_atm->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_n_atm" class="atm_n_atm">
<span<?php echo $atm->n_atm->ViewAttributes() ?>>
<?php echo $atm->n_atm->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($atm->lokasi->Visible) { // lokasi ?>
		<td<?php echo $atm->lokasi->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_lokasi" class="atm_lokasi">
<span<?php echo $atm->lokasi->ViewAttributes() ?>>
<?php echo $atm->lokasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($atm->kotaid->Visible) { // kotaid ?>
		<td<?php echo $atm->kotaid->CellAttributes() ?>>
<span id="el<?php echo $atm_delete->RowCnt ?>_atm_kotaid" class="atm_kotaid">
<span<?php echo $atm->kotaid->ViewAttributes() ?>>
<?php echo $atm->kotaid->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$atm_delete->Recordset->MoveNext();
}
$atm_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $atm_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fatmdelete.Init();
</script>
<?php
$atm_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$atm_delete->Page_Terminate();
?>
