<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "jalurinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "jalur_detilgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$jalur_add = NULL; // Initialize page object first

class cjalur_add extends cjalur {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'jalur';

	// Page object name
	var $PageObjName = 'jalur_add';

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

		// Table object (jalur)
		if (!isset($GLOBALS["jalur"]) || get_class($GLOBALS["jalur"]) == "cjalur") {
			$GLOBALS["jalur"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jalur"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jalur', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jalurlist.php"));
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
		$this->c_jalur->SetVisibility();
		$this->nik->SetVisibility();

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

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("jalur_detil", $DetailTblVar)) {

					// Process auto fill for detail table 'jalur_detil'
					if (preg_match('/^fjalur_detil(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["jalur_detil_grid"])) $GLOBALS["jalur_detil_grid"] = new cjalur_detil_grid;
						$GLOBALS["jalur_detil_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
			}
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
		global $EW_EXPORT, $jalur;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jalur);
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
					if ($pageName == "jalurview.php")
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
			if (@$_GET["c_jalur"] != "") {
				$this->c_jalur->setQueryStringValue($_GET["c_jalur"]);
				$this->setKey("c_jalur", $this->c_jalur->CurrentValue); // Set up key
			} else {
				$this->setKey("c_jalur", ""); // Clear key
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

		// Set up detail parameters
		$this->SetupDetailParms();

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
					$this->Page_Terminate("jalurlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "jalurlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "jalurview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetupDetailParms();
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
		$this->c_jalur->CurrentValue = NULL;
		$this->c_jalur->OldValue = $this->c_jalur->CurrentValue;
		$this->nik->CurrentValue = NULL;
		$this->nik->OldValue = $this->nik->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->c_jalur->FldIsDetailKey) {
			$this->c_jalur->setFormValue($objForm->GetValue("x_c_jalur"));
		}
		if (!$this->nik->FldIsDetailKey) {
			$this->nik->setFormValue($objForm->GetValue("x_nik"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->c_jalur->CurrentValue = $this->c_jalur->FormValue;
		$this->nik->CurrentValue = $this->nik->FormValue;
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
		$this->c_jalur->setDbValue($row['c_jalur']);
		$this->nik->setDbValue($row['nik']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['c_jalur'] = $this->c_jalur->CurrentValue;
		$row['nik'] = $this->nik->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->c_jalur->DbValue = $row['c_jalur'];
		$this->nik->DbValue = $row['nik'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("c_jalur")) <> "")
			$this->c_jalur->CurrentValue = $this->getKey("c_jalur"); // c_jalur
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
		// c_jalur
		// nik

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// c_jalur
		$this->c_jalur->ViewValue = $this->c_jalur->CurrentValue;
		$this->c_jalur->ViewCustomAttributes = "";

		// nik
		if (strval($this->nik->CurrentValue) <> "") {
			$sFilterWrk = "`nik`" . ew_SearchString("=", $this->nik->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `nik`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `karyawan`";
		$sWhereWrk = "";
		$this->nik->LookupFilters = array("dx1" => '`nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->nik->ViewValue = $this->nik->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nik->ViewValue = $this->nik->CurrentValue;
			}
		} else {
			$this->nik->ViewValue = NULL;
		}
		$this->nik->ViewCustomAttributes = "";

			// c_jalur
			$this->c_jalur->LinkCustomAttributes = "";
			$this->c_jalur->HrefValue = "";
			$this->c_jalur->TooltipValue = "";

			// nik
			$this->nik->LinkCustomAttributes = "";
			$this->nik->HrefValue = "";
			$this->nik->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// c_jalur
			$this->c_jalur->EditAttrs["class"] = "form-control";
			$this->c_jalur->EditCustomAttributes = "";
			$this->c_jalur->EditValue = ew_HtmlEncode($this->c_jalur->CurrentValue);
			$this->c_jalur->PlaceHolder = ew_RemoveHtml($this->c_jalur->FldCaption());

			// nik
			$this->nik->EditCustomAttributes = "";
			if (trim(strval($this->nik->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`nik`" . ew_SearchString("=", $this->nik->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `nik`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `karyawan`";
			$sWhereWrk = "";
			$this->nik->LookupFilters = array("dx1" => '`nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->nik->ViewValue = $this->nik->DisplayValue($arwrk);
			} else {
				$this->nik->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->nik->EditValue = $arwrk;

			// Add refer script
			// c_jalur

			$this->c_jalur->LinkCustomAttributes = "";
			$this->c_jalur->HrefValue = "";

			// nik
			$this->nik->LinkCustomAttributes = "";
			$this->nik->HrefValue = "";
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
		if (!$this->c_jalur->FldIsDetailKey && !is_null($this->c_jalur->FormValue) && $this->c_jalur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->c_jalur->FldCaption(), $this->c_jalur->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("jalur_detil", $DetailTblVar) && $GLOBALS["jalur_detil"]->DetailAdd) {
			if (!isset($GLOBALS["jalur_detil_grid"])) $GLOBALS["jalur_detil_grid"] = new cjalur_detil_grid(); // get detail page object
			$GLOBALS["jalur_detil_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// c_jalur
		$this->c_jalur->SetDbValueDef($rsnew, $this->c_jalur->CurrentValue, "", FALSE);

		// nik
		$this->nik->SetDbValueDef($rsnew, $this->nik->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['c_jalur']) == "") {
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("jalur_detil", $DetailTblVar) && $GLOBALS["jalur_detil"]->DetailAdd) {
				$GLOBALS["jalur_detil"]->c_jalur->setSessionValue($this->c_jalur->CurrentValue); // Set master key
				$GLOBALS["jalur_detil"]->nik->setSessionValue($this->nik->CurrentValue); // Set master key
				if (!isset($GLOBALS["jalur_detil_grid"])) $GLOBALS["jalur_detil_grid"] = new cjalur_detil_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "jalur_detil"); // Load user level of detail table
				$AddRow = $GLOBALS["jalur_detil_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["jalur_detil"]->nik->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("jalur_detil", $DetailTblVar)) {
				if (!isset($GLOBALS["jalur_detil_grid"]))
					$GLOBALS["jalur_detil_grid"] = new cjalur_detil_grid;
				if ($GLOBALS["jalur_detil_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["jalur_detil_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["jalur_detil_grid"]->CurrentMode = "add";
					$GLOBALS["jalur_detil_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["jalur_detil_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["jalur_detil_grid"]->setStartRecordNumber(1);
					$GLOBALS["jalur_detil_grid"]->c_jalur->FldIsDetailKey = TRUE;
					$GLOBALS["jalur_detil_grid"]->c_jalur->CurrentValue = $this->c_jalur->CurrentValue;
					$GLOBALS["jalur_detil_grid"]->c_jalur->setSessionValue($GLOBALS["jalur_detil_grid"]->c_jalur->CurrentValue);
					$GLOBALS["jalur_detil_grid"]->nik->FldIsDetailKey = TRUE;
					$GLOBALS["jalur_detil_grid"]->nik->CurrentValue = $this->nik->CurrentValue;
					$GLOBALS["jalur_detil_grid"]->nik->setSessionValue($GLOBALS["jalur_detil_grid"]->nik->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jalurlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_nik":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `nik` AS `LinkFld`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `karyawan`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`nik` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($jalur_add)) $jalur_add = new cjalur_add();

// Page init
$jalur_add->Page_Init();

// Page main
$jalur_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jalur_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fjaluradd = new ew_Form("fjaluradd", "add");

// Validate form
fjaluradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_c_jalur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jalur->c_jalur->FldCaption(), $jalur->c_jalur->ReqErrMsg)) ?>");

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
fjaluradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fjaluradd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fjaluradd.Lists["x_nik"] = {"LinkField":"x_nik","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"karyawan"};
fjaluradd.Lists["x_nik"].Data = "<?php echo $jalur_add->nik->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $jalur_add->ShowPageHeader(); ?>
<?php
$jalur_add->ShowMessage();
?>
<form name="fjaluradd" id="fjaluradd" class="<?php echo $jalur_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jalur_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jalur_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jalur">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($jalur_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($jalur->c_jalur->Visible) { // c_jalur ?>
	<div id="r_c_jalur" class="form-group">
		<label id="elh_jalur_c_jalur" for="x_c_jalur" class="<?php echo $jalur_add->LeftColumnClass ?>"><?php echo $jalur->c_jalur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $jalur_add->RightColumnClass ?>"><div<?php echo $jalur->c_jalur->CellAttributes() ?>>
<span id="el_jalur_c_jalur">
<input type="text" data-table="jalur" data-field="x_c_jalur" name="x_c_jalur" id="x_c_jalur" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($jalur->c_jalur->getPlaceHolder()) ?>" value="<?php echo $jalur->c_jalur->EditValue ?>"<?php echo $jalur->c_jalur->EditAttributes() ?>>
</span>
<?php echo $jalur->c_jalur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jalur->nik->Visible) { // nik ?>
	<div id="r_nik" class="form-group">
		<label id="elh_jalur_nik" for="x_nik" class="<?php echo $jalur_add->LeftColumnClass ?>"><?php echo $jalur->nik->FldCaption() ?></label>
		<div class="<?php echo $jalur_add->RightColumnClass ?>"><div<?php echo $jalur->nik->CellAttributes() ?>>
<span id="el_jalur_nik">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_nik"><?php echo (strval($jalur->nik->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur->nik->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur->nik->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_nik',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur->nik->ReadOnly || $jalur->nik->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur" data-field="x_nik" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur->nik->DisplayValueSeparatorAttribute() ?>" name="x_nik" id="x_nik" value="<?php echo $jalur->nik->CurrentValue ?>"<?php echo $jalur->nik->EditAttributes() ?>>
</span>
<?php echo $jalur->nik->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("jalur_detil", explode(",", $jalur->getCurrentDetailTable())) && $jalur_detil->DetailAdd) {
?>
<?php if ($jalur->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("jalur_detil", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "jalur_detilgrid.php" ?>
<?php } ?>
<?php if (!$jalur_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $jalur_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jalur_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fjaluradd.Init();
</script>
<?php
$jalur_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jalur_add->Page_Terminate();
?>
