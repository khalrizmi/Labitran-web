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

$atm_list = NULL; // Initialize page object first

class catm_list extends catm {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'atm';

	// Page object name
	var $PageObjName = 'atm_list';

	// Grid form hidden field names
	var $FormName = 'fatmlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "atmadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "atmdelete.php";
		$this->MultiUpdateUrl = "atmupdate.php";

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fatmlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->atmid->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->c_bank->AdvancedSearch->ToJson(), ","); // Field c_bank
		$sFilterList = ew_Concat($sFilterList, $this->c_cabang->AdvancedSearch->ToJson(), ","); // Field c_cabang
		$sFilterList = ew_Concat($sFilterList, $this->c_bmi->AdvancedSearch->ToJson(), ","); // Field c_bmi
		$sFilterList = ew_Concat($sFilterList, $this->atmid->AdvancedSearch->ToJson(), ","); // Field atmid
		$sFilterList = ew_Concat($sFilterList, $this->n_atm->AdvancedSearch->ToJson(), ","); // Field n_atm
		$sFilterList = ew_Concat($sFilterList, $this->lokasi->AdvancedSearch->ToJson(), ","); // Field lokasi
		$sFilterList = ew_Concat($sFilterList, $this->lokasi_sebelum->AdvancedSearch->ToJson(), ","); // Field lokasi_sebelum
		$sFilterList = ew_Concat($sFilterList, $this->kotaid->AdvancedSearch->ToJson(), ","); // Field kotaid
		$sFilterList = ew_Concat($sFilterList, $this->latitude->AdvancedSearch->ToJson(), ","); // Field latitude
		$sFilterList = ew_Concat($sFilterList, $this->lontitude->AdvancedSearch->ToJson(), ","); // Field lontitude
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fatmlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field c_bank
		$this->c_bank->AdvancedSearch->SearchValue = @$filter["x_c_bank"];
		$this->c_bank->AdvancedSearch->SearchOperator = @$filter["z_c_bank"];
		$this->c_bank->AdvancedSearch->SearchCondition = @$filter["v_c_bank"];
		$this->c_bank->AdvancedSearch->SearchValue2 = @$filter["y_c_bank"];
		$this->c_bank->AdvancedSearch->SearchOperator2 = @$filter["w_c_bank"];
		$this->c_bank->AdvancedSearch->Save();

		// Field c_cabang
		$this->c_cabang->AdvancedSearch->SearchValue = @$filter["x_c_cabang"];
		$this->c_cabang->AdvancedSearch->SearchOperator = @$filter["z_c_cabang"];
		$this->c_cabang->AdvancedSearch->SearchCondition = @$filter["v_c_cabang"];
		$this->c_cabang->AdvancedSearch->SearchValue2 = @$filter["y_c_cabang"];
		$this->c_cabang->AdvancedSearch->SearchOperator2 = @$filter["w_c_cabang"];
		$this->c_cabang->AdvancedSearch->Save();

		// Field c_bmi
		$this->c_bmi->AdvancedSearch->SearchValue = @$filter["x_c_bmi"];
		$this->c_bmi->AdvancedSearch->SearchOperator = @$filter["z_c_bmi"];
		$this->c_bmi->AdvancedSearch->SearchCondition = @$filter["v_c_bmi"];
		$this->c_bmi->AdvancedSearch->SearchValue2 = @$filter["y_c_bmi"];
		$this->c_bmi->AdvancedSearch->SearchOperator2 = @$filter["w_c_bmi"];
		$this->c_bmi->AdvancedSearch->Save();

		// Field atmid
		$this->atmid->AdvancedSearch->SearchValue = @$filter["x_atmid"];
		$this->atmid->AdvancedSearch->SearchOperator = @$filter["z_atmid"];
		$this->atmid->AdvancedSearch->SearchCondition = @$filter["v_atmid"];
		$this->atmid->AdvancedSearch->SearchValue2 = @$filter["y_atmid"];
		$this->atmid->AdvancedSearch->SearchOperator2 = @$filter["w_atmid"];
		$this->atmid->AdvancedSearch->Save();

		// Field n_atm
		$this->n_atm->AdvancedSearch->SearchValue = @$filter["x_n_atm"];
		$this->n_atm->AdvancedSearch->SearchOperator = @$filter["z_n_atm"];
		$this->n_atm->AdvancedSearch->SearchCondition = @$filter["v_n_atm"];
		$this->n_atm->AdvancedSearch->SearchValue2 = @$filter["y_n_atm"];
		$this->n_atm->AdvancedSearch->SearchOperator2 = @$filter["w_n_atm"];
		$this->n_atm->AdvancedSearch->Save();

		// Field lokasi
		$this->lokasi->AdvancedSearch->SearchValue = @$filter["x_lokasi"];
		$this->lokasi->AdvancedSearch->SearchOperator = @$filter["z_lokasi"];
		$this->lokasi->AdvancedSearch->SearchCondition = @$filter["v_lokasi"];
		$this->lokasi->AdvancedSearch->SearchValue2 = @$filter["y_lokasi"];
		$this->lokasi->AdvancedSearch->SearchOperator2 = @$filter["w_lokasi"];
		$this->lokasi->AdvancedSearch->Save();

		// Field lokasi_sebelum
		$this->lokasi_sebelum->AdvancedSearch->SearchValue = @$filter["x_lokasi_sebelum"];
		$this->lokasi_sebelum->AdvancedSearch->SearchOperator = @$filter["z_lokasi_sebelum"];
		$this->lokasi_sebelum->AdvancedSearch->SearchCondition = @$filter["v_lokasi_sebelum"];
		$this->lokasi_sebelum->AdvancedSearch->SearchValue2 = @$filter["y_lokasi_sebelum"];
		$this->lokasi_sebelum->AdvancedSearch->SearchOperator2 = @$filter["w_lokasi_sebelum"];
		$this->lokasi_sebelum->AdvancedSearch->Save();

		// Field kotaid
		$this->kotaid->AdvancedSearch->SearchValue = @$filter["x_kotaid"];
		$this->kotaid->AdvancedSearch->SearchOperator = @$filter["z_kotaid"];
		$this->kotaid->AdvancedSearch->SearchCondition = @$filter["v_kotaid"];
		$this->kotaid->AdvancedSearch->SearchValue2 = @$filter["y_kotaid"];
		$this->kotaid->AdvancedSearch->SearchOperator2 = @$filter["w_kotaid"];
		$this->kotaid->AdvancedSearch->Save();

		// Field latitude
		$this->latitude->AdvancedSearch->SearchValue = @$filter["x_latitude"];
		$this->latitude->AdvancedSearch->SearchOperator = @$filter["z_latitude"];
		$this->latitude->AdvancedSearch->SearchCondition = @$filter["v_latitude"];
		$this->latitude->AdvancedSearch->SearchValue2 = @$filter["y_latitude"];
		$this->latitude->AdvancedSearch->SearchOperator2 = @$filter["w_latitude"];
		$this->latitude->AdvancedSearch->Save();

		// Field lontitude
		$this->lontitude->AdvancedSearch->SearchValue = @$filter["x_lontitude"];
		$this->lontitude->AdvancedSearch->SearchOperator = @$filter["z_lontitude"];
		$this->lontitude->AdvancedSearch->SearchCondition = @$filter["v_lontitude"];
		$this->lontitude->AdvancedSearch->SearchValue2 = @$filter["y_lontitude"];
		$this->lontitude->AdvancedSearch->SearchOperator2 = @$filter["w_lontitude"];
		$this->lontitude->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->c_bank, $Default, FALSE); // c_bank
		$this->BuildSearchSql($sWhere, $this->c_cabang, $Default, FALSE); // c_cabang
		$this->BuildSearchSql($sWhere, $this->c_bmi, $Default, FALSE); // c_bmi
		$this->BuildSearchSql($sWhere, $this->atmid, $Default, FALSE); // atmid
		$this->BuildSearchSql($sWhere, $this->n_atm, $Default, FALSE); // n_atm
		$this->BuildSearchSql($sWhere, $this->lokasi, $Default, FALSE); // lokasi
		$this->BuildSearchSql($sWhere, $this->lokasi_sebelum, $Default, FALSE); // lokasi_sebelum
		$this->BuildSearchSql($sWhere, $this->kotaid, $Default, FALSE); // kotaid
		$this->BuildSearchSql($sWhere, $this->latitude, $Default, FALSE); // latitude
		$this->BuildSearchSql($sWhere, $this->lontitude, $Default, FALSE); // lontitude

		// Set up search parm
		if (!$Default && $sWhere <> "" && in_array($this->Command, array("", "reset", "resetall"))) {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->c_bank->AdvancedSearch->Save(); // c_bank
			$this->c_cabang->AdvancedSearch->Save(); // c_cabang
			$this->c_bmi->AdvancedSearch->Save(); // c_bmi
			$this->atmid->AdvancedSearch->Save(); // atmid
			$this->n_atm->AdvancedSearch->Save(); // n_atm
			$this->lokasi->AdvancedSearch->Save(); // lokasi
			$this->lokasi_sebelum->AdvancedSearch->Save(); // lokasi_sebelum
			$this->kotaid->AdvancedSearch->Save(); // kotaid
			$this->latitude->AdvancedSearch->Save(); // latitude
			$this->lontitude->AdvancedSearch->Save(); // lontitude
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->c_cabang, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->c_bmi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->atmid, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->n_atm, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lokasi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lokasi_sebelum, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->latitude, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lontitude, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->c_bank->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->c_cabang->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->c_bmi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->atmid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->n_atm->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lokasi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lokasi_sebelum->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->kotaid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->latitude->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lontitude->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->c_bank->AdvancedSearch->UnsetSession();
		$this->c_cabang->AdvancedSearch->UnsetSession();
		$this->c_bmi->AdvancedSearch->UnsetSession();
		$this->atmid->AdvancedSearch->UnsetSession();
		$this->n_atm->AdvancedSearch->UnsetSession();
		$this->lokasi->AdvancedSearch->UnsetSession();
		$this->lokasi_sebelum->AdvancedSearch->UnsetSession();
		$this->kotaid->AdvancedSearch->UnsetSession();
		$this->latitude->AdvancedSearch->UnsetSession();
		$this->lontitude->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->c_bank->AdvancedSearch->Load();
		$this->c_cabang->AdvancedSearch->Load();
		$this->c_bmi->AdvancedSearch->Load();
		$this->atmid->AdvancedSearch->Load();
		$this->n_atm->AdvancedSearch->Load();
		$this->lokasi->AdvancedSearch->Load();
		$this->lokasi_sebelum->AdvancedSearch->Load();
		$this->kotaid->AdvancedSearch->Load();
		$this->latitude->AdvancedSearch->Load();
		$this->lontitude->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->c_bank); // c_bank
			$this->UpdateSort($this->c_bmi); // c_bmi
			$this->UpdateSort($this->atmid); // atmid
			$this->UpdateSort($this->n_atm); // n_atm
			$this->UpdateSort($this->lokasi); // lokasi
			$this->UpdateSort($this->kotaid); // kotaid
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->c_bank->setSort("");
				$this->c_bmi->setSort("");
				$this->atmid->setSort("");
				$this->n_atm->setSort("");
				$this->lokasi->setSort("");
				$this->kotaid->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->atmid->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fatmlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fatmlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fatmlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fatmlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// c_bank

		$this->c_bank->AdvancedSearch->SearchValue = @$_GET["x_c_bank"];
		if ($this->c_bank->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->c_bank->AdvancedSearch->SearchOperator = @$_GET["z_c_bank"];

		// c_cabang
		$this->c_cabang->AdvancedSearch->SearchValue = @$_GET["x_c_cabang"];
		if ($this->c_cabang->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->c_cabang->AdvancedSearch->SearchOperator = @$_GET["z_c_cabang"];

		// c_bmi
		$this->c_bmi->AdvancedSearch->SearchValue = @$_GET["x_c_bmi"];
		if ($this->c_bmi->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->c_bmi->AdvancedSearch->SearchOperator = @$_GET["z_c_bmi"];

		// atmid
		$this->atmid->AdvancedSearch->SearchValue = @$_GET["x_atmid"];
		if ($this->atmid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->atmid->AdvancedSearch->SearchOperator = @$_GET["z_atmid"];

		// n_atm
		$this->n_atm->AdvancedSearch->SearchValue = @$_GET["x_n_atm"];
		if ($this->n_atm->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->n_atm->AdvancedSearch->SearchOperator = @$_GET["z_n_atm"];

		// lokasi
		$this->lokasi->AdvancedSearch->SearchValue = @$_GET["x_lokasi"];
		if ($this->lokasi->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->lokasi->AdvancedSearch->SearchOperator = @$_GET["z_lokasi"];

		// lokasi_sebelum
		$this->lokasi_sebelum->AdvancedSearch->SearchValue = @$_GET["x_lokasi_sebelum"];
		if ($this->lokasi_sebelum->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->lokasi_sebelum->AdvancedSearch->SearchOperator = @$_GET["z_lokasi_sebelum"];

		// kotaid
		$this->kotaid->AdvancedSearch->SearchValue = @$_GET["x_kotaid"];
		if ($this->kotaid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->kotaid->AdvancedSearch->SearchOperator = @$_GET["z_kotaid"];

		// latitude
		$this->latitude->AdvancedSearch->SearchValue = @$_GET["x_latitude"];
		if ($this->latitude->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->latitude->AdvancedSearch->SearchOperator = @$_GET["z_latitude"];

		// lontitude
		$this->lontitude->AdvancedSearch->SearchValue = @$_GET["x_lontitude"];
		if ($this->lontitude->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->lontitude->AdvancedSearch->SearchOperator = @$_GET["z_lontitude"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// c_bank
			$this->c_bank->EditCustomAttributes = "";
			if (trim(strval($this->c_bank->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`c_bank`" . ew_SearchString("=", $this->c_bank->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
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
				$this->c_bank->AdvancedSearch->ViewValue = $this->c_bank->DisplayValue($arwrk);
			} else {
				$this->c_bank->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->c_bank->EditValue = $arwrk;

			// c_bmi
			$this->c_bmi->EditAttrs["class"] = "form-control";
			$this->c_bmi->EditCustomAttributes = "";
			$this->c_bmi->EditValue = ew_HtmlEncode($this->c_bmi->AdvancedSearch->SearchValue);
			$this->c_bmi->PlaceHolder = ew_RemoveHtml($this->c_bmi->FldCaption());

			// atmid
			$this->atmid->EditAttrs["class"] = "form-control";
			$this->atmid->EditCustomAttributes = "";
			$this->atmid->EditValue = ew_HtmlEncode($this->atmid->AdvancedSearch->SearchValue);
			$this->atmid->PlaceHolder = ew_RemoveHtml($this->atmid->FldCaption());

			// n_atm
			$this->n_atm->EditAttrs["class"] = "form-control";
			$this->n_atm->EditCustomAttributes = "";
			$this->n_atm->EditValue = ew_HtmlEncode($this->n_atm->AdvancedSearch->SearchValue);
			$this->n_atm->PlaceHolder = ew_RemoveHtml($this->n_atm->FldCaption());

			// lokasi
			$this->lokasi->EditAttrs["class"] = "form-control";
			$this->lokasi->EditCustomAttributes = "";
			$this->lokasi->EditValue = ew_HtmlEncode($this->lokasi->AdvancedSearch->SearchValue);
			$this->lokasi->PlaceHolder = ew_RemoveHtml($this->lokasi->FldCaption());

			// kotaid
			$this->kotaid->EditAttrs["class"] = "form-control";
			$this->kotaid->EditCustomAttributes = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->c_bank->AdvancedSearch->Load();
		$this->c_cabang->AdvancedSearch->Load();
		$this->c_bmi->AdvancedSearch->Load();
		$this->atmid->AdvancedSearch->Load();
		$this->n_atm->AdvancedSearch->Load();
		$this->lokasi->AdvancedSearch->Load();
		$this->lokasi_sebelum->AdvancedSearch->Load();
		$this->kotaid->AdvancedSearch->Load();
		$this->latitude->AdvancedSearch->Load();
		$this->lontitude->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_atm\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_atm',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fatmlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
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
			}
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($atm_list)) $atm_list = new catm_list();

// Page init
$atm_list->Page_Init();

// Page main
$atm_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atm_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($atm->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fatmlist = new ew_Form("fatmlist", "list");
fatmlist.FormKeyCountName = '<?php echo $atm_list->FormKeyCountName ?>';

// Form_CustomValidate event
fatmlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fatmlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fatmlist.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_c_bank","x_n_bank","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fatmlist.Lists["x_c_bank"].Data = "<?php echo $atm_list->c_bank->LookupFilterQuery(FALSE, "list") ?>";
fatmlist.Lists["x_kotaid"] = {"LinkField":"x_kabupatenid","Ajax":true,"AutoFill":false,"DisplayFields":["x_lokasi_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"kabupaten"};
fatmlist.Lists["x_kotaid"].Data = "<?php echo $atm_list->kotaid->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fatmlistsrch = new ew_Form("fatmlistsrch");

// Validate function for search
fatmlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fatmlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fatmlistsrch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fatmlistsrch.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_c_bank","x_n_bank","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fatmlistsrch.Lists["x_c_bank"].Data = "<?php echo $atm_list->c_bank->LookupFilterQuery(FALSE, "extbs") ?>";
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($atm->Export == "") { ?>
<div class="ewToolbar">
<?php if ($atm_list->TotalRecs > 0 && $atm_list->ExportOptions->Visible()) { ?>
<?php $atm_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($atm_list->SearchOptions->Visible()) { ?>
<?php $atm_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($atm_list->FilterOptions->Visible()) { ?>
<?php $atm_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $atm_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($atm_list->TotalRecs <= 0)
			$atm_list->TotalRecs = $atm->ListRecordCount();
	} else {
		if (!$atm_list->Recordset && ($atm_list->Recordset = $atm_list->LoadRecordset()))
			$atm_list->TotalRecs = $atm_list->Recordset->RecordCount();
	}
	$atm_list->StartRec = 1;
	if ($atm_list->DisplayRecs <= 0 || ($atm->Export <> "" && $atm->ExportAll)) // Display all records
		$atm_list->DisplayRecs = $atm_list->TotalRecs;
	if (!($atm->Export <> "" && $atm->ExportAll))
		$atm_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$atm_list->Recordset = $atm_list->LoadRecordset($atm_list->StartRec-1, $atm_list->DisplayRecs);

	// Set no record found message
	if ($atm->CurrentAction == "" && $atm_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$atm_list->setWarningMessage(ew_DeniedMsg());
		if ($atm_list->SearchWhere == "0=101")
			$atm_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$atm_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$atm_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($atm->Export == "" && $atm->CurrentAction == "") { ?>
<form name="fatmlistsrch" id="fatmlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($atm_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fatmlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="atm">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$atm_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$atm->RowType = EW_ROWTYPE_SEARCH;

// Render row
$atm->ResetAttrs();
$atm_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($atm->c_bank->Visible) { // c_bank ?>
	<div id="xsc_c_bank" class="ewCell form-group">
		<label for="x_c_bank" class="ewSearchCaption ewLabel"><?php echo $atm->c_bank->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_c_bank" id="z_c_bank" value="LIKE"></span>
		<span class="ewSearchField">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_c_bank"><?php echo (strval($atm->c_bank->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $atm->c_bank->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($atm->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($atm->c_bank->ReadOnly || $atm->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="atm" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $atm->c_bank->DisplayValueSeparatorAttribute() ?>" name="x_c_bank" id="x_c_bank" value="<?php echo $atm->c_bank->AdvancedSearch->SearchValue ?>"<?php echo $atm->c_bank->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($atm_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($atm_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $atm_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($atm_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($atm_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($atm_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($atm_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $atm_list->ShowPageHeader(); ?>
<?php
$atm_list->ShowMessage();
?>
<?php if ($atm_list->TotalRecs > 0 || $atm->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($atm_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> atm">
<?php if ($atm->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($atm->CurrentAction <> "gridadd" && $atm->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($atm_list->Pager)) $atm_list->Pager = new cPrevNextPager($atm_list->StartRec, $atm_list->DisplayRecs, $atm_list->TotalRecs, $atm_list->AutoHidePager) ?>
<?php if ($atm_list->Pager->RecordCount > 0 && $atm_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($atm_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($atm_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $atm_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($atm_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($atm_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $atm_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($atm_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $atm_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $atm_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $atm_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($atm_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fatmlist" id="fatmlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($atm_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $atm_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="atm">
<div id="gmp_atm" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($atm_list->TotalRecs > 0 || $atm->CurrentAction == "gridedit") { ?>
<table id="tbl_atmlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$atm_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$atm_list->RenderListOptions();

// Render list options (header, left)
$atm_list->ListOptions->Render("header", "left");
?>
<?php if ($atm->c_bank->Visible) { // c_bank ?>
	<?php if ($atm->SortUrl($atm->c_bank) == "") { ?>
		<th data-name="c_bank" class="<?php echo $atm->c_bank->HeaderCellClass() ?>"><div id="elh_atm_c_bank" class="atm_c_bank"><div class="ewTableHeaderCaption"><?php echo $atm->c_bank->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="c_bank" class="<?php echo $atm->c_bank->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->c_bank) ?>',1);"><div id="elh_atm_c_bank" class="atm_c_bank">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->c_bank->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atm->c_bank->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->c_bank->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($atm->c_bmi->Visible) { // c_bmi ?>
	<?php if ($atm->SortUrl($atm->c_bmi) == "") { ?>
		<th data-name="c_bmi" class="<?php echo $atm->c_bmi->HeaderCellClass() ?>"><div id="elh_atm_c_bmi" class="atm_c_bmi"><div class="ewTableHeaderCaption"><?php echo $atm->c_bmi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="c_bmi" class="<?php echo $atm->c_bmi->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->c_bmi) ?>',1);"><div id="elh_atm_c_bmi" class="atm_c_bmi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->c_bmi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($atm->c_bmi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->c_bmi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($atm->atmid->Visible) { // atmid ?>
	<?php if ($atm->SortUrl($atm->atmid) == "") { ?>
		<th data-name="atmid" class="<?php echo $atm->atmid->HeaderCellClass() ?>"><div id="elh_atm_atmid" class="atm_atmid"><div class="ewTableHeaderCaption"><?php echo $atm->atmid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atmid" class="<?php echo $atm->atmid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->atmid) ?>',1);"><div id="elh_atm_atmid" class="atm_atmid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->atmid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($atm->atmid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->atmid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($atm->n_atm->Visible) { // n_atm ?>
	<?php if ($atm->SortUrl($atm->n_atm) == "") { ?>
		<th data-name="n_atm" class="<?php echo $atm->n_atm->HeaderCellClass() ?>"><div id="elh_atm_n_atm" class="atm_n_atm"><div class="ewTableHeaderCaption"><?php echo $atm->n_atm->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="n_atm" class="<?php echo $atm->n_atm->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->n_atm) ?>',1);"><div id="elh_atm_n_atm" class="atm_n_atm">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->n_atm->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($atm->n_atm->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->n_atm->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($atm->lokasi->Visible) { // lokasi ?>
	<?php if ($atm->SortUrl($atm->lokasi) == "") { ?>
		<th data-name="lokasi" class="<?php echo $atm->lokasi->HeaderCellClass() ?>"><div id="elh_atm_lokasi" class="atm_lokasi"><div class="ewTableHeaderCaption"><?php echo $atm->lokasi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lokasi" class="<?php echo $atm->lokasi->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->lokasi) ?>',1);"><div id="elh_atm_lokasi" class="atm_lokasi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->lokasi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($atm->lokasi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->lokasi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($atm->kotaid->Visible) { // kotaid ?>
	<?php if ($atm->SortUrl($atm->kotaid) == "") { ?>
		<th data-name="kotaid" class="<?php echo $atm->kotaid->HeaderCellClass() ?>"><div id="elh_atm_kotaid" class="atm_kotaid"><div class="ewTableHeaderCaption"><?php echo $atm->kotaid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kotaid" class="<?php echo $atm->kotaid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atm->SortUrl($atm->kotaid) ?>',1);"><div id="elh_atm_kotaid" class="atm_kotaid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atm->kotaid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atm->kotaid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atm->kotaid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$atm_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($atm->ExportAll && $atm->Export <> "") {
	$atm_list->StopRec = $atm_list->TotalRecs;
} else {

	// Set the last record to display
	if ($atm_list->TotalRecs > $atm_list->StartRec + $atm_list->DisplayRecs - 1)
		$atm_list->StopRec = $atm_list->StartRec + $atm_list->DisplayRecs - 1;
	else
		$atm_list->StopRec = $atm_list->TotalRecs;
}
$atm_list->RecCnt = $atm_list->StartRec - 1;
if ($atm_list->Recordset && !$atm_list->Recordset->EOF) {
	$atm_list->Recordset->MoveFirst();
	$bSelectLimit = $atm_list->UseSelectLimit;
	if (!$bSelectLimit && $atm_list->StartRec > 1)
		$atm_list->Recordset->Move($atm_list->StartRec - 1);
} elseif (!$atm->AllowAddDeleteRow && $atm_list->StopRec == 0) {
	$atm_list->StopRec = $atm->GridAddRowCount;
}

// Initialize aggregate
$atm->RowType = EW_ROWTYPE_AGGREGATEINIT;
$atm->ResetAttrs();
$atm_list->RenderRow();
while ($atm_list->RecCnt < $atm_list->StopRec) {
	$atm_list->RecCnt++;
	if (intval($atm_list->RecCnt) >= intval($atm_list->StartRec)) {
		$atm_list->RowCnt++;

		// Set up key count
		$atm_list->KeyCount = $atm_list->RowIndex;

		// Init row class and style
		$atm->ResetAttrs();
		$atm->CssClass = "";
		if ($atm->CurrentAction == "gridadd") {
		} else {
			$atm_list->LoadRowValues($atm_list->Recordset); // Load row values
		}
		$atm->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$atm->RowAttrs = array_merge($atm->RowAttrs, array('data-rowindex'=>$atm_list->RowCnt, 'id'=>'r' . $atm_list->RowCnt . '_atm', 'data-rowtype'=>$atm->RowType));

		// Render row
		$atm_list->RenderRow();

		// Render list options
		$atm_list->RenderListOptions();
?>
	<tr<?php echo $atm->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atm_list->ListOptions->Render("body", "left", $atm_list->RowCnt);
?>
	<?php if ($atm->c_bank->Visible) { // c_bank ?>
		<td data-name="c_bank"<?php echo $atm->c_bank->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_c_bank" class="atm_c_bank">
<span<?php echo $atm->c_bank->ViewAttributes() ?>>
<?php echo $atm->c_bank->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($atm->c_bmi->Visible) { // c_bmi ?>
		<td data-name="c_bmi"<?php echo $atm->c_bmi->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_c_bmi" class="atm_c_bmi">
<span<?php echo $atm->c_bmi->ViewAttributes() ?>>
<?php echo $atm->c_bmi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($atm->atmid->Visible) { // atmid ?>
		<td data-name="atmid"<?php echo $atm->atmid->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_atmid" class="atm_atmid">
<span<?php echo $atm->atmid->ViewAttributes() ?>>
<?php echo $atm->atmid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($atm->n_atm->Visible) { // n_atm ?>
		<td data-name="n_atm"<?php echo $atm->n_atm->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_n_atm" class="atm_n_atm">
<span<?php echo $atm->n_atm->ViewAttributes() ?>>
<?php echo $atm->n_atm->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($atm->lokasi->Visible) { // lokasi ?>
		<td data-name="lokasi"<?php echo $atm->lokasi->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_lokasi" class="atm_lokasi">
<span<?php echo $atm->lokasi->ViewAttributes() ?>>
<?php echo $atm->lokasi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($atm->kotaid->Visible) { // kotaid ?>
		<td data-name="kotaid"<?php echo $atm->kotaid->CellAttributes() ?>>
<span id="el<?php echo $atm_list->RowCnt ?>_atm_kotaid" class="atm_kotaid">
<span<?php echo $atm->kotaid->ViewAttributes() ?>>
<?php echo $atm->kotaid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$atm_list->ListOptions->Render("body", "right", $atm_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($atm->CurrentAction <> "gridadd")
		$atm_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($atm->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($atm_list->Recordset)
	$atm_list->Recordset->Close();
?>
<?php if ($atm->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($atm->CurrentAction <> "gridadd" && $atm->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($atm_list->Pager)) $atm_list->Pager = new cPrevNextPager($atm_list->StartRec, $atm_list->DisplayRecs, $atm_list->TotalRecs, $atm_list->AutoHidePager) ?>
<?php if ($atm_list->Pager->RecordCount > 0 && $atm_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($atm_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($atm_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $atm_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($atm_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($atm_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $atm_list->PageUrl() ?>start=<?php echo $atm_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $atm_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($atm_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $atm_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $atm_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $atm_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($atm_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($atm_list->TotalRecs == 0 && $atm->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($atm_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($atm->Export == "") { ?>
<script type="text/javascript">
fatmlistsrch.FilterList = <?php echo $atm_list->GetFilterList() ?>;
fatmlistsrch.Init();
fatmlist.Init();
</script>
<?php } ?>
<?php
$atm_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($atm->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$atm_list->Page_Terminate();
?>
