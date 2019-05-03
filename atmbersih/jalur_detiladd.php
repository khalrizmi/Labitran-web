<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "jalur_detilinfo.php" ?>
<?php include_once "jalurinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$jalur_detil_add = NULL; // Initialize page object first

class cjalur_detil_add extends cjalur_detil {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'jalur_detil';

	// Page object name
	var $PageObjName = 'jalur_detil_add';

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

		// Table object (jalur_detil)
		if (!isset($GLOBALS["jalur_detil"]) || get_class($GLOBALS["jalur_detil"]) == "cjalur_detil") {
			$GLOBALS["jalur_detil"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jalur_detil"];
		}

		// Table object (jalur)
		if (!isset($GLOBALS['jalur'])) $GLOBALS['jalur'] = new cjalur();

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jalur_detil', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jalur_detillist.php"));
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
		$this->c_bank->SetVisibility();
		$this->atmid->SetVisibility();

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
		global $EW_EXPORT, $jalur_detil;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jalur_detil);
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
					if ($pageName == "jalur_detilview.php")
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

		// Set up master/detail parameters
		$this->SetupMasterParms();

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
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
					$this->Page_Terminate("jalur_detillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "jalur_detillist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "jalur_detilview.php")
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
		$this->c_jalur->CurrentValue = NULL;
		$this->c_jalur->OldValue = $this->c_jalur->CurrentValue;
		$this->c_bank->CurrentValue = NULL;
		$this->c_bank->OldValue = $this->c_bank->CurrentValue;
		$this->atmid->CurrentValue = NULL;
		$this->atmid->OldValue = $this->atmid->CurrentValue;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
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
		if (!$this->c_bank->FldIsDetailKey) {
			$this->c_bank->setFormValue($objForm->GetValue("x_c_bank"));
		}
		if (!$this->atmid->FldIsDetailKey) {
			$this->atmid->setFormValue($objForm->GetValue("x_atmid"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->c_jalur->CurrentValue = $this->c_jalur->FormValue;
		$this->c_bank->CurrentValue = $this->c_bank->FormValue;
		$this->atmid->CurrentValue = $this->atmid->FormValue;
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
		$this->c_bank->setDbValue($row['c_bank']);
		$this->atmid->setDbValue($row['atmid']);
		$this->id->setDbValue($row['id']);
		$this->nik->setDbValue($row['nik']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['c_jalur'] = $this->c_jalur->CurrentValue;
		$row['c_bank'] = $this->c_bank->CurrentValue;
		$row['atmid'] = $this->atmid->CurrentValue;
		$row['id'] = $this->id->CurrentValue;
		$row['nik'] = $this->nik->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->c_jalur->DbValue = $row['c_jalur'];
		$this->c_bank->DbValue = $row['c_bank'];
		$this->atmid->DbValue = $row['atmid'];
		$this->id->DbValue = $row['id'];
		$this->nik->DbValue = $row['nik'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// c_bank
		// atmid
		// id
		// nik

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// c_jalur
		$this->c_jalur->ViewValue = $this->c_jalur->CurrentValue;
		$this->c_jalur->ViewCustomAttributes = "";

		// c_bank
		if (strval($this->c_bank->CurrentValue) <> "") {
			$sFilterWrk = "`c_bank`" . ew_SearchString("=", $this->c_bank->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `c_bank`, `n_bank` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
		$sWhereWrk = "";
		$this->c_bank->LookupFilters = array("dx1" => '`n_bank`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->c_bank->ViewValue = $this->c_bank->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->c_bank->ViewValue = $this->c_bank->CurrentValue;
			}
		} else {
			$this->c_bank->ViewValue = NULL;
		}
		$this->c_bank->ViewCustomAttributes = "";

		// atmid
		if (strval($this->atmid->CurrentValue) <> "") {
			$sFilterWrk = "`atmid`" . ew_SearchString("=", $this->atmid->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `atmid`, `atmid` AS `DispFld`, `n_atm` AS `Disp2Fld`, `lokasi` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `atm`";
		$sWhereWrk = "";
		$this->atmid->LookupFilters = array("dx1" => '`atmid`', "dx2" => '`n_atm`', "dx3" => '`lokasi`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->atmid, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->atmid->ViewValue = $this->atmid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->atmid->ViewValue = $this->atmid->CurrentValue;
			}
		} else {
			$this->atmid->ViewValue = NULL;
		}
		$this->atmid->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nik
		$this->nik->ViewValue = $this->nik->CurrentValue;
		$this->nik->ViewCustomAttributes = "";

			// c_jalur
			$this->c_jalur->LinkCustomAttributes = "";
			$this->c_jalur->HrefValue = "";
			$this->c_jalur->TooltipValue = "";

			// c_bank
			$this->c_bank->LinkCustomAttributes = "";
			$this->c_bank->HrefValue = "";
			$this->c_bank->TooltipValue = "";

			// atmid
			$this->atmid->LinkCustomAttributes = "";
			$this->atmid->HrefValue = "";
			$this->atmid->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// c_jalur
			$this->c_jalur->EditAttrs["class"] = "form-control";
			$this->c_jalur->EditCustomAttributes = "";
			if ($this->c_jalur->getSessionValue() <> "") {
				$this->c_jalur->CurrentValue = $this->c_jalur->getSessionValue();
			$this->c_jalur->ViewValue = $this->c_jalur->CurrentValue;
			$this->c_jalur->ViewCustomAttributes = "";
			} else {
			$this->c_jalur->EditValue = ew_HtmlEncode($this->c_jalur->CurrentValue);
			$this->c_jalur->PlaceHolder = ew_RemoveHtml($this->c_jalur->FldCaption());
			}

			// c_bank
			$this->c_bank->EditCustomAttributes = "";
			if (trim(strval($this->c_bank->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`c_bank`" . ew_SearchString("=", $this->c_bank->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `c_bank`, `n_bank` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bank`";
			$sWhereWrk = "";
			$this->c_bank->LookupFilters = array("dx1" => '`n_bank`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->c_bank->ViewValue = $this->c_bank->DisplayValue($arwrk);
			} else {
				$this->c_bank->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->c_bank->EditValue = $arwrk;

			// atmid
			$this->atmid->EditCustomAttributes = "";
			if (trim(strval($this->atmid->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`atmid`" . ew_SearchString("=", $this->atmid->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `atmid`, `atmid` AS `DispFld`, `n_atm` AS `Disp2Fld`, `lokasi` AS `Disp3Fld`, '' AS `Disp4Fld`, `c_bank` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `atm`";
			$sWhereWrk = "";
			$this->atmid->LookupFilters = array("dx1" => '`atmid`', "dx2" => '`n_atm`', "dx3" => '`lokasi`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->atmid, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->atmid->ViewValue = $this->atmid->DisplayValue($arwrk);
			} else {
				$this->atmid->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->atmid->EditValue = $arwrk;

			// Add refer script
			// c_jalur

			$this->c_jalur->LinkCustomAttributes = "";
			$this->c_jalur->HrefValue = "";

			// c_bank
			$this->c_bank->LinkCustomAttributes = "";
			$this->c_bank->HrefValue = "";

			// atmid
			$this->atmid->LinkCustomAttributes = "";
			$this->atmid->HrefValue = "";
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

		// Check referential integrity for master table 'jalur'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_jalur();
		if (strval($this->c_jalur->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@c_jalur@", ew_AdjustSql($this->c_jalur->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($this->nik->getSessionValue() <> "") {
			$sMasterFilter = str_replace("@nik@", ew_AdjustSql($this->nik->getSessionValue(), "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["jalur"])) $GLOBALS["jalur"] = new cjalur();
			$rsmaster = $GLOBALS["jalur"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "jalur", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// c_jalur
		$this->c_jalur->SetDbValueDef($rsnew, $this->c_jalur->CurrentValue, NULL, FALSE);

		// c_bank
		$this->c_bank->SetDbValueDef($rsnew, $this->c_bank->CurrentValue, NULL, FALSE);

		// atmid
		$this->atmid->SetDbValueDef($rsnew, $this->atmid->CurrentValue, NULL, FALSE);

		// nik
		if ($this->nik->getSessionValue() <> "") {
			$rsnew['nik'] = $this->nik->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "jalur") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_c_jalur"] <> "") {
					$GLOBALS["jalur"]->c_jalur->setQueryStringValue($_GET["fk_c_jalur"]);
					$this->c_jalur->setQueryStringValue($GLOBALS["jalur"]->c_jalur->QueryStringValue);
					$this->c_jalur->setSessionValue($this->c_jalur->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
				if (@$_GET["fk_nik"] <> "") {
					$GLOBALS["jalur"]->nik->setQueryStringValue($_GET["fk_nik"]);
					$this->nik->setQueryStringValue($GLOBALS["jalur"]->nik->QueryStringValue);
					$this->nik->setSessionValue($this->nik->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "jalur") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_c_jalur"] <> "") {
					$GLOBALS["jalur"]->c_jalur->setFormValue($_POST["fk_c_jalur"]);
					$this->c_jalur->setFormValue($GLOBALS["jalur"]->c_jalur->FormValue);
					$this->c_jalur->setSessionValue($this->c_jalur->FormValue);
				} else {
					$bValidMaster = FALSE;
				}
				if (@$_POST["fk_nik"] <> "") {
					$GLOBALS["jalur"]->nik->setFormValue($_POST["fk_nik"]);
					$this->nik->setFormValue($GLOBALS["jalur"]->nik->FormValue);
					$this->nik->setSessionValue($this->nik->FormValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "jalur") {
				if ($this->c_jalur->CurrentValue == "") $this->c_jalur->setSessionValue("");
				if ($this->nik->CurrentValue == "") $this->nik->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jalur_detillist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk = "SELECT `c_bank` AS `LinkFld`, `n_bank` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`n_bank`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`c_bank` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->c_bank, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_atmid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `atmid` AS `LinkFld`, `atmid` AS `DispFld`, `n_atm` AS `Disp2Fld`, `lokasi` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `atm`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`atmid`', "dx2" => '`n_atm`', "dx3" => '`lokasi`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`atmid` IN ({filter_value})', "t0" => "200", "fn0" => "", "f1" => '`c_bank` IN ({filter_value})', "t1" => "200", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->atmid, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($jalur_detil_add)) $jalur_detil_add = new cjalur_detil_add();

// Page init
$jalur_detil_add->Page_Init();

// Page main
$jalur_detil_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jalur_detil_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fjalur_detiladd = new ew_Form("fjalur_detiladd", "add");

// Validate form
fjalur_detiladd.Validate = function() {
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
fjalur_detiladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fjalur_detiladd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fjalur_detiladd.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_n_bank","","",""],"ParentFields":[],"ChildFields":["x_atmid"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fjalur_detiladd.Lists["x_c_bank"].Data = "<?php echo $jalur_detil_add->c_bank->LookupFilterQuery(FALSE, "add") ?>";
fjalur_detiladd.Lists["x_atmid"] = {"LinkField":"x_atmid","Ajax":true,"AutoFill":false,"DisplayFields":["x_atmid","x_n_atm","x_lokasi",""],"ParentFields":["x_c_bank"],"ChildFields":[],"FilterFields":["x_c_bank"],"Options":[],"Template":"","LinkTable":"atm"};
fjalur_detiladd.Lists["x_atmid"].Data = "<?php echo $jalur_detil_add->atmid->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $jalur_detil_add->ShowPageHeader(); ?>
<?php
$jalur_detil_add->ShowMessage();
?>
<form name="fjalur_detiladd" id="fjalur_detiladd" class="<?php echo $jalur_detil_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jalur_detil_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jalur_detil_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jalur_detil">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($jalur_detil_add->IsModal) ?>">
<?php if ($jalur_detil->getCurrentMasterTable() == "jalur") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="jalur">
<input type="hidden" name="fk_c_jalur" value="<?php echo $jalur_detil->c_jalur->getSessionValue() ?>">
<input type="hidden" name="fk_nik" value="<?php echo $jalur_detil->nik->getSessionValue() ?>">
<?php } ?>
<div class="ewAddDiv"><!-- page* -->
<?php if ($jalur_detil->c_jalur->getSessionValue() <> "") { ?>
<input type="hidden" id="x_c_jalur" name="x_c_jalur" value="<?php echo ew_HtmlEncode($jalur_detil->c_jalur->CurrentValue) ?>">
<?php } else { ?>
<span id="el_jalur_detil_c_jalur">
<input type="hidden" data-table="jalur_detil" data-field="x_c_jalur" name="x_c_jalur" id="x_c_jalur" value="<?php echo ew_HtmlEncode($jalur_detil->c_jalur->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($jalur_detil->c_bank->Visible) { // c_bank ?>
	<div id="r_c_bank" class="form-group">
		<label id="elh_jalur_detil_c_bank" for="x_c_bank" class="<?php echo $jalur_detil_add->LeftColumnClass ?>"><?php echo $jalur_detil->c_bank->FldCaption() ?></label>
		<div class="<?php echo $jalur_detil_add->RightColumnClass ?>"><div<?php echo $jalur_detil->c_bank->CellAttributes() ?>>
<span id="el_jalur_detil_c_bank">
<?php $jalur_detil->c_bank->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$jalur_detil->c_bank->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_c_bank"><?php echo (strval($jalur_detil->c_bank->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->c_bank->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->c_bank->ReadOnly || $jalur_detil->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->c_bank->DisplayValueSeparatorAttribute() ?>" name="x_c_bank" id="x_c_bank" value="<?php echo $jalur_detil->c_bank->CurrentValue ?>"<?php echo $jalur_detil->c_bank->EditAttributes() ?>>
</span>
<?php echo $jalur_detil->c_bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jalur_detil->atmid->Visible) { // atmid ?>
	<div id="r_atmid" class="form-group">
		<label id="elh_jalur_detil_atmid" for="x_atmid" class="<?php echo $jalur_detil_add->LeftColumnClass ?>"><?php echo $jalur_detil->atmid->FldCaption() ?></label>
		<div class="<?php echo $jalur_detil_add->RightColumnClass ?>"><div<?php echo $jalur_detil->atmid->CellAttributes() ?>>
<span id="el_jalur_detil_atmid">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_atmid"><?php echo (strval($jalur_detil->atmid->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->atmid->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->atmid->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_atmid',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->atmid->ReadOnly || $jalur_detil->atmid->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->atmid->DisplayValueSeparatorAttribute() ?>" name="x_atmid" id="x_atmid" value="<?php echo $jalur_detil->atmid->CurrentValue ?>"<?php echo $jalur_detil->atmid->EditAttributes() ?>>
</span>
<?php echo $jalur_detil->atmid->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (strval($jalur_detil->nik->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_nik" id="x_nik" value="<?php echo ew_HtmlEncode(strval($jalur_detil->nik->getSessionValue())) ?>">
<?php } ?>
<?php if (!$jalur_detil_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $jalur_detil_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jalur_detil_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fjalur_detiladd.Init();
</script>
<?php
$jalur_detil_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jalur_detil_add->Page_Terminate();
?>
