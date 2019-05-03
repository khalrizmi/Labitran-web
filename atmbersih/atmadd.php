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

$atm_add = NULL; // Initialize page object first

class catm_add extends catm {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'atm';

	// Page object name
	var $PageObjName = 'atm_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->c_bank->SetVisibility();
		$this->c_cabang->SetVisibility();
		$this->c_bmi->SetVisibility();
		$this->atmid->SetVisibility();
		$this->n_atm->SetVisibility();
		$this->lokasi->SetVisibility();
		$this->lokasi_sebelum->SetVisibility();
		$this->kotaid->SetVisibility();
		$this->latitude->SetVisibility();
		$this->lontitude->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "atmview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["atmid"] != "") {
				$this->atmid->setQueryStringValue($_GET["atmid"]);
				$this->setKey("atmid", $this->atmid->CurrentValue); // Set up key
			} else {
				$this->setKey("atmid", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("atmlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "atmlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "atmview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->c_bank->CurrentValue = NULL;
		$this->c_bank->OldValue = $this->c_bank->CurrentValue;
		$this->c_cabang->CurrentValue = NULL;
		$this->c_cabang->OldValue = $this->c_cabang->CurrentValue;
		$this->c_bmi->CurrentValue = NULL;
		$this->c_bmi->OldValue = $this->c_bmi->CurrentValue;
		$this->atmid->CurrentValue = NULL;
		$this->atmid->OldValue = $this->atmid->CurrentValue;
		$this->n_atm->CurrentValue = NULL;
		$this->n_atm->OldValue = $this->n_atm->CurrentValue;
		$this->lokasi->CurrentValue = NULL;
		$this->lokasi->OldValue = $this->lokasi->CurrentValue;
		$this->lokasi_sebelum->CurrentValue = NULL;
		$this->lokasi_sebelum->OldValue = $this->lokasi_sebelum->CurrentValue;
		$this->areaid->CurrentValue = NULL;
		$this->areaid->OldValue = $this->areaid->CurrentValue;
		$this->kotaid->CurrentValue = NULL;
		$this->kotaid->OldValue = $this->kotaid->CurrentValue;
		$this->latitude->CurrentValue = NULL;
		$this->latitude->OldValue = $this->latitude->CurrentValue;
		$this->lontitude->CurrentValue = NULL;
		$this->lontitude->OldValue = $this->lontitude->CurrentValue;
		$this->c_cabang_bmi->CurrentValue = NULL;
		$this->c_cabang_bmi->OldValue = $this->c_cabang_bmi->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->c_bank->FldIsDetailKey) {
			$this->c_bank->setFormValue($objForm->GetValue("x_c_bank"));
		}
		if (!$this->c_cabang->FldIsDetailKey) {
			$this->c_cabang->setFormValue($objForm->GetValue("x_c_cabang"));
		}
		if (!$this->c_bmi->FldIsDetailKey) {
			$this->c_bmi->setFormValue($objForm->GetValue("x_c_bmi"));
		}
		if (!$this->atmid->FldIsDetailKey) {
			$this->atmid->setFormValue($objForm->GetValue("x_atmid"));
		}
		if (!$this->n_atm->FldIsDetailKey) {
			$this->n_atm->setFormValue($objForm->GetValue("x_n_atm"));
		}
		if (!$this->lokasi->FldIsDetailKey) {
			$this->lokasi->setFormValue($objForm->GetValue("x_lokasi"));
		}
		if (!$this->lokasi_sebelum->FldIsDetailKey) {
			$this->lokasi_sebelum->setFormValue($objForm->GetValue("x_lokasi_sebelum"));
		}
		if (!$this->kotaid->FldIsDetailKey) {
			$this->kotaid->setFormValue($objForm->GetValue("x_kotaid"));
		}
		if (!$this->latitude->FldIsDetailKey) {
			$this->latitude->setFormValue($objForm->GetValue("x_latitude"));
		}
		if (!$this->lontitude->FldIsDetailKey) {
			$this->lontitude->setFormValue($objForm->GetValue("x_lontitude"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->c_bank->CurrentValue = $this->c_bank->FormValue;
		$this->c_cabang->CurrentValue = $this->c_cabang->FormValue;
		$this->c_bmi->CurrentValue = $this->c_bmi->FormValue;
		$this->atmid->CurrentValue = $this->atmid->FormValue;
		$this->n_atm->CurrentValue = $this->n_atm->FormValue;
		$this->lokasi->CurrentValue = $this->lokasi->FormValue;
		$this->lokasi_sebelum->CurrentValue = $this->lokasi_sebelum->FormValue;
		$this->kotaid->CurrentValue = $this->kotaid->FormValue;
		$this->latitude->CurrentValue = $this->latitude->FormValue;
		$this->lontitude->CurrentValue = $this->lontitude->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['c_bank'] = $this->c_bank->CurrentValue;
		$row['c_cabang'] = $this->c_cabang->CurrentValue;
		$row['c_bmi'] = $this->c_bmi->CurrentValue;
		$row['atmid'] = $this->atmid->CurrentValue;
		$row['n_atm'] = $this->n_atm->CurrentValue;
		$row['lokasi'] = $this->lokasi->CurrentValue;
		$row['lokasi_sebelum'] = $this->lokasi_sebelum->CurrentValue;
		$row['areaid'] = $this->areaid->CurrentValue;
		$row['kotaid'] = $this->kotaid->CurrentValue;
		$row['latitude'] = $this->latitude->CurrentValue;
		$row['lontitude'] = $this->lontitude->CurrentValue;
		$row['c_cabang_bmi'] = $this->c_cabang_bmi->CurrentValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("atmid")) <> "")
			$this->atmid->CurrentValue = $this->getKey("atmid"); // atmid
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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
		// kotaid
		// latitude
		// lontitude
		// c_cabang_bmi

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// c_bank
			$this->c_bank->EditCustomAttributes = "";
			if (trim(strval($this->c_bank->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`c_bank`" . ew_SearchString("=", $this->c_bank->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `c_bank`, `c_bank` AS `DispFld`, `n_bank` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bank`";
			$sWhereWrk = "";
			$this->c_bank->LookupFilters = array("dx1" => '`c_bank`', "dx2" => '`n_bank`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->c_bank->ViewValue = $this->c_bank->DisplayValue($arwrk);
			} else {
				$this->c_bank->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->c_bank->EditValue = $arwrk;

			// c_cabang
			$this->c_cabang->EditAttrs["class"] = "form-control";
			$this->c_cabang->EditCustomAttributes = "";
			$this->c_cabang->EditValue = ew_HtmlEncode($this->c_cabang->CurrentValue);
			$this->c_cabang->PlaceHolder = ew_RemoveHtml($this->c_cabang->FldCaption());

			// c_bmi
			$this->c_bmi->EditAttrs["class"] = "form-control";
			$this->c_bmi->EditCustomAttributes = "";
			$this->c_bmi->EditValue = ew_HtmlEncode($this->c_bmi->CurrentValue);
			$this->c_bmi->PlaceHolder = ew_RemoveHtml($this->c_bmi->FldCaption());

			// atmid
			$this->atmid->EditAttrs["class"] = "form-control";
			$this->atmid->EditCustomAttributes = "";
			$this->atmid->EditValue = ew_HtmlEncode($this->atmid->CurrentValue);
			$this->atmid->PlaceHolder = ew_RemoveHtml($this->atmid->FldCaption());

			// n_atm
			$this->n_atm->EditAttrs["class"] = "form-control";
			$this->n_atm->EditCustomAttributes = "";
			$this->n_atm->EditValue = ew_HtmlEncode($this->n_atm->CurrentValue);
			$this->n_atm->PlaceHolder = ew_RemoveHtml($this->n_atm->FldCaption());

			// lokasi
			$this->lokasi->EditAttrs["class"] = "form-control";
			$this->lokasi->EditCustomAttributes = "";
			$this->lokasi->EditValue = ew_HtmlEncode($this->lokasi->CurrentValue);
			$this->lokasi->PlaceHolder = ew_RemoveHtml($this->lokasi->FldCaption());

			// lokasi_sebelum
			$this->lokasi_sebelum->EditAttrs["class"] = "form-control";
			$this->lokasi_sebelum->EditCustomAttributes = "";
			$this->lokasi_sebelum->EditValue = ew_HtmlEncode($this->lokasi_sebelum->CurrentValue);
			$this->lokasi_sebelum->PlaceHolder = ew_RemoveHtml($this->lokasi_sebelum->FldCaption());

			// kotaid
			$this->kotaid->EditCustomAttributes = "";
			if (trim(strval($this->kotaid->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`kabupatenid`" . ew_SearchString("=", $this->kotaid->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `kabupatenid`, `lokasi_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `kabupaten`";
			$sWhereWrk = "";
			$this->kotaid->LookupFilters = array("dx1" => '`lokasi_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->kotaid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->kotaid->ViewValue = $this->kotaid->DisplayValue($arwrk);
			} else {
				$this->kotaid->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->kotaid->EditValue = $arwrk;

			// latitude
			$this->latitude->EditAttrs["class"] = "form-control";
			$this->latitude->EditCustomAttributes = "";
			$this->latitude->EditValue = ew_HtmlEncode($this->latitude->CurrentValue);
			$this->latitude->PlaceHolder = ew_RemoveHtml($this->latitude->FldCaption());

			// lontitude
			$this->lontitude->EditAttrs["class"] = "form-control";
			$this->lontitude->EditCustomAttributes = "";
			$this->lontitude->EditValue = ew_HtmlEncode($this->lontitude->CurrentValue);
			$this->lontitude->PlaceHolder = ew_RemoveHtml($this->lontitude->FldCaption());

			// Add refer script
			// c_bank

			$this->c_bank->LinkCustomAttributes = "";
			$this->c_bank->HrefValue = "";

			// c_cabang
			$this->c_cabang->LinkCustomAttributes = "";
			$this->c_cabang->HrefValue = "";

			// c_bmi
			$this->c_bmi->LinkCustomAttributes = "";
			$this->c_bmi->HrefValue = "";

			// atmid
			$this->atmid->LinkCustomAttributes = "";
			$this->atmid->HrefValue = "";

			// n_atm
			$this->n_atm->LinkCustomAttributes = "";
			$this->n_atm->HrefValue = "";

			// lokasi
			$this->lokasi->LinkCustomAttributes = "";
			$this->lokasi->HrefValue = "";

			// lokasi_sebelum
			$this->lokasi_sebelum->LinkCustomAttributes = "";
			$this->lokasi_sebelum->HrefValue = "";

			// kotaid
			$this->kotaid->LinkCustomAttributes = "";
			$this->kotaid->HrefValue = "";

			// latitude
			$this->latitude->LinkCustomAttributes = "";
			$this->latitude->HrefValue = "";

			// lontitude
			$this->lontitude->LinkCustomAttributes = "";
			$this->lontitude->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->atmid->FldIsDetailKey && !is_null($this->atmid->FormValue) && $this->atmid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->atmid->FldCaption(), $this->atmid->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// c_bank
		$this->c_bank->SetDbValueDef($rsnew, $this->c_bank->CurrentValue, NULL, FALSE);

		// c_cabang
		$this->c_cabang->SetDbValueDef($rsnew, $this->c_cabang->CurrentValue, NULL, FALSE);

		// c_bmi
		$this->c_bmi->SetDbValueDef($rsnew, $this->c_bmi->CurrentValue, NULL, FALSE);

		// atmid
		$this->atmid->SetDbValueDef($rsnew, $this->atmid->CurrentValue, "", FALSE);

		// n_atm
		$this->n_atm->SetDbValueDef($rsnew, $this->n_atm->CurrentValue, NULL, FALSE);

		// lokasi
		$this->lokasi->SetDbValueDef($rsnew, $this->lokasi->CurrentValue, NULL, FALSE);

		// lokasi_sebelum
		$this->lokasi_sebelum->SetDbValueDef($rsnew, $this->lokasi_sebelum->CurrentValue, NULL, FALSE);

		// kotaid
		$this->kotaid->SetDbValueDef($rsnew, $this->kotaid->CurrentValue, NULL, FALSE);

		// latitude
		$this->latitude->SetDbValueDef($rsnew, $this->latitude->CurrentValue, NULL, FALSE);

		// lontitude
		$this->lontitude->SetDbValueDef($rsnew, $this->lontitude->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['atmid']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("atmlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_c_bank":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `c_bank` AS `LinkFld`, `c_bank` AS `DispFld`, `n_bank` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`c_bank`', "dx2" => '`n_bank`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`c_bank` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_kotaid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kabupatenid` AS `LinkFld`, `lokasi_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `kabupaten`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`lokasi_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`kabupatenid` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->kotaid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($atm_add)) $atm_add = new catm_add();

// Page init
$atm_add->Page_Init();

// Page main
$atm_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atm_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fatmadd = new ew_Form("fatmadd", "add");

// Validate form
fatmadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_atmid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $atm->atmid->FldCaption(), $atm->atmid->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fatmadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fatmadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fatmadd.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_c_bank","x_n_bank","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fatmadd.Lists["x_c_bank"].Data = "<?php echo $atm_add->c_bank->LookupFilterQuery(FALSE, "add") ?>";
fatmadd.Lists["x_kotaid"] = {"LinkField":"x_kabupatenid","Ajax":true,"AutoFill":false,"DisplayFields":["x_lokasi_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"kabupaten"};
fatmadd.Lists["x_kotaid"].Data = "<?php echo $atm_add->kotaid->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $atm_add->ShowPageHeader(); ?>
<?php
$atm_add->ShowMessage();
?>
<form name="fatmadd" id="fatmadd" class="<?php echo $atm_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($atm_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $atm_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="atm">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($atm_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($atm->c_bank->Visible) { // c_bank ?>
	<div id="r_c_bank" class="form-group">
		<label id="elh_atm_c_bank" for="x_c_bank" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->c_bank->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->c_bank->CellAttributes() ?>>
<span id="el_atm_c_bank">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_c_bank"><?php echo (strval($atm->c_bank->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $atm->c_bank->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($atm->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($atm->c_bank->ReadOnly || $atm->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="atm" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $atm->c_bank->DisplayValueSeparatorAttribute() ?>" name="x_c_bank" id="x_c_bank" value="<?php echo $atm->c_bank->CurrentValue ?>"<?php echo $atm->c_bank->EditAttributes() ?>>
</span>
<?php echo $atm->c_bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->c_cabang->Visible) { // c_cabang ?>
	<div id="r_c_cabang" class="form-group">
		<label id="elh_atm_c_cabang" for="x_c_cabang" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->c_cabang->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->c_cabang->CellAttributes() ?>>
<span id="el_atm_c_cabang">
<input type="text" data-table="atm" data-field="x_c_cabang" name="x_c_cabang" id="x_c_cabang" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($atm->c_cabang->getPlaceHolder()) ?>" value="<?php echo $atm->c_cabang->EditValue ?>"<?php echo $atm->c_cabang->EditAttributes() ?>>
</span>
<?php echo $atm->c_cabang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->c_bmi->Visible) { // c_bmi ?>
	<div id="r_c_bmi" class="form-group">
		<label id="elh_atm_c_bmi" for="x_c_bmi" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->c_bmi->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->c_bmi->CellAttributes() ?>>
<span id="el_atm_c_bmi">
<input type="text" data-table="atm" data-field="x_c_bmi" name="x_c_bmi" id="x_c_bmi" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($atm->c_bmi->getPlaceHolder()) ?>" value="<?php echo $atm->c_bmi->EditValue ?>"<?php echo $atm->c_bmi->EditAttributes() ?>>
</span>
<?php echo $atm->c_bmi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->atmid->Visible) { // atmid ?>
	<div id="r_atmid" class="form-group">
		<label id="elh_atm_atmid" for="x_atmid" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->atmid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->atmid->CellAttributes() ?>>
<span id="el_atm_atmid">
<input type="text" data-table="atm" data-field="x_atmid" name="x_atmid" id="x_atmid" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($atm->atmid->getPlaceHolder()) ?>" value="<?php echo $atm->atmid->EditValue ?>"<?php echo $atm->atmid->EditAttributes() ?>>
</span>
<?php echo $atm->atmid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->n_atm->Visible) { // n_atm ?>
	<div id="r_n_atm" class="form-group">
		<label id="elh_atm_n_atm" for="x_n_atm" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->n_atm->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->n_atm->CellAttributes() ?>>
<span id="el_atm_n_atm">
<input type="text" data-table="atm" data-field="x_n_atm" name="x_n_atm" id="x_n_atm" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($atm->n_atm->getPlaceHolder()) ?>" value="<?php echo $atm->n_atm->EditValue ?>"<?php echo $atm->n_atm->EditAttributes() ?>>
</span>
<?php echo $atm->n_atm->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->lokasi->Visible) { // lokasi ?>
	<div id="r_lokasi" class="form-group">
		<label id="elh_atm_lokasi" for="x_lokasi" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->lokasi->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->lokasi->CellAttributes() ?>>
<span id="el_atm_lokasi">
<input type="text" data-table="atm" data-field="x_lokasi" name="x_lokasi" id="x_lokasi" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($atm->lokasi->getPlaceHolder()) ?>" value="<?php echo $atm->lokasi->EditValue ?>"<?php echo $atm->lokasi->EditAttributes() ?>>
</span>
<?php echo $atm->lokasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->lokasi_sebelum->Visible) { // lokasi_sebelum ?>
	<div id="r_lokasi_sebelum" class="form-group">
		<label id="elh_atm_lokasi_sebelum" for="x_lokasi_sebelum" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->lokasi_sebelum->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->lokasi_sebelum->CellAttributes() ?>>
<span id="el_atm_lokasi_sebelum">
<input type="text" data-table="atm" data-field="x_lokasi_sebelum" name="x_lokasi_sebelum" id="x_lokasi_sebelum" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($atm->lokasi_sebelum->getPlaceHolder()) ?>" value="<?php echo $atm->lokasi_sebelum->EditValue ?>"<?php echo $atm->lokasi_sebelum->EditAttributes() ?>>
</span>
<?php echo $atm->lokasi_sebelum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->kotaid->Visible) { // kotaid ?>
	<div id="r_kotaid" class="form-group">
		<label id="elh_atm_kotaid" for="x_kotaid" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->kotaid->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->kotaid->CellAttributes() ?>>
<span id="el_atm_kotaid">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_kotaid"><?php echo (strval($atm->kotaid->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $atm->kotaid->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($atm->kotaid->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_kotaid',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($atm->kotaid->ReadOnly || $atm->kotaid->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="atm" data-field="x_kotaid" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $atm->kotaid->DisplayValueSeparatorAttribute() ?>" name="x_kotaid" id="x_kotaid" value="<?php echo $atm->kotaid->CurrentValue ?>"<?php echo $atm->kotaid->EditAttributes() ?>>
</span>
<?php echo $atm->kotaid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->latitude->Visible) { // latitude ?>
	<div id="r_latitude" class="form-group">
		<label id="elh_atm_latitude" for="x_latitude" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->latitude->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->latitude->CellAttributes() ?>>
<span id="el_atm_latitude">
<input type="text" data-table="atm" data-field="x_latitude" name="x_latitude" id="x_latitude" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($atm->latitude->getPlaceHolder()) ?>" value="<?php echo $atm->latitude->EditValue ?>"<?php echo $atm->latitude->EditAttributes() ?>>
</span>
<?php echo $atm->latitude->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($atm->lontitude->Visible) { // lontitude ?>
	<div id="r_lontitude" class="form-group">
		<label id="elh_atm_lontitude" for="x_lontitude" class="<?php echo $atm_add->LeftColumnClass ?>"><?php echo $atm->lontitude->FldCaption() ?></label>
		<div class="<?php echo $atm_add->RightColumnClass ?>"><div<?php echo $atm->lontitude->CellAttributes() ?>>
<span id="el_atm_lontitude">
<input type="text" data-table="atm" data-field="x_lontitude" name="x_lontitude" id="x_lontitude" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($atm->lontitude->getPlaceHolder()) ?>" value="<?php echo $atm->lontitude->EditValue ?>"<?php echo $atm->lontitude->EditAttributes() ?>>
</span>
<?php echo $atm->lontitude->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$atm_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $atm_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $atm_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fatmadd.Init();
</script>
<?php
$atm_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$atm_add->Page_Terminate();
?>
