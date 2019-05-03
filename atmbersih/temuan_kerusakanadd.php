<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "temuan_kerusakaninfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "temuan_kerusakan_fotogridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$temuan_kerusakan_add = NULL; // Initialize page object first

class ctemuan_kerusakan_add extends ctemuan_kerusakan {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}';

	// Table name
	var $TableName = 'temuan_kerusakan';

	// Page object name
	var $PageObjName = 'temuan_kerusakan_add';

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

		// Table object (temuan_kerusakan)
		if (!isset($GLOBALS["temuan_kerusakan"]) || get_class($GLOBALS["temuan_kerusakan"]) == "ctemuan_kerusakan") {
			$GLOBALS["temuan_kerusakan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["temuan_kerusakan"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'temuan_kerusakan', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("temuan_kerusakanlist.php"));
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
		$this->nik->SetVisibility();
		$this->d_temuan->SetVisibility();
		$this->kerusakan->SetVisibility();
		$this->keterangan->SetVisibility();

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
				if (in_array("temuan_kerusakan_foto", $DetailTblVar)) {

					// Process auto fill for detail table 'temuan_kerusakan_foto'
					if (preg_match('/^ftemuan_kerusakan_foto(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["temuan_kerusakan_foto_grid"])) $GLOBALS["temuan_kerusakan_foto_grid"] = new ctemuan_kerusakan_foto_grid;
						$GLOBALS["temuan_kerusakan_foto_grid"]->Page_Init();
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
		global $EW_EXPORT, $temuan_kerusakan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($temuan_kerusakan);
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
					if ($pageName == "temuan_kerusakanview.php")
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
					$this->Page_Terminate("temuan_kerusakanlist.php"); // No matching record, return to list
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
					if (ew_GetPageName($sReturnUrl) == "temuan_kerusakanlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "temuan_kerusakanview.php")
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
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->nik->CurrentValue = NULL;
		$this->nik->OldValue = $this->nik->CurrentValue;
		$this->d_temuan->CurrentValue = NULL;
		$this->d_temuan->OldValue = $this->d_temuan->CurrentValue;
		$this->kerusakan->CurrentValue = NULL;
		$this->kerusakan->OldValue = $this->kerusakan->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nik->FldIsDetailKey) {
			$this->nik->setFormValue($objForm->GetValue("x_nik"));
		}
		if (!$this->d_temuan->FldIsDetailKey) {
			$this->d_temuan->setFormValue($objForm->GetValue("x_d_temuan"));
			$this->d_temuan->CurrentValue = ew_UnFormatDateTime($this->d_temuan->CurrentValue, 1);
		}
		if (!$this->kerusakan->FldIsDetailKey) {
			$this->kerusakan->setFormValue($objForm->GetValue("x_kerusakan"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nik->CurrentValue = $this->nik->FormValue;
		$this->d_temuan->CurrentValue = $this->d_temuan->FormValue;
		$this->d_temuan->CurrentValue = ew_UnFormatDateTime($this->d_temuan->CurrentValue, 1);
		$this->kerusakan->CurrentValue = $this->kerusakan->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
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
		$this->id->setDbValue($row['id']);
		$this->nik->setDbValue($row['nik']);
		$this->d_temuan->setDbValue($row['d_temuan']);
		$this->kerusakan->setDbValue($row['kerusakan']);
		$this->keterangan->setDbValue($row['keterangan']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['id'] = $this->id->CurrentValue;
		$row['nik'] = $this->nik->CurrentValue;
		$row['d_temuan'] = $this->d_temuan->CurrentValue;
		$row['kerusakan'] = $this->kerusakan->CurrentValue;
		$row['keterangan'] = $this->keterangan->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->nik->DbValue = $row['nik'];
		$this->d_temuan->DbValue = $row['d_temuan'];
		$this->kerusakan->DbValue = $row['kerusakan'];
		$this->keterangan->DbValue = $row['keterangan'];
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
		// id
		// nik
		// d_temuan
		// kerusakan
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// nik
		if (strval($this->nik->CurrentValue) <> "") {
			$sFilterWrk = "`nik`" . ew_SearchString("=", $this->nik->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `nik`, `nik` AS `DispFld`, `nama` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `karyawan`";
		$sWhereWrk = "";
		$this->nik->LookupFilters = array("dx1" => '`nik`', "dx2" => '`nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->nik->ViewValue = $this->nik->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nik->ViewValue = $this->nik->CurrentValue;
			}
		} else {
			$this->nik->ViewValue = NULL;
		}
		$this->nik->ViewCustomAttributes = "";

		// d_temuan
		$this->d_temuan->ViewValue = $this->d_temuan->CurrentValue;
		$this->d_temuan->ViewValue = ew_FormatDateTime($this->d_temuan->ViewValue, 1);
		$this->d_temuan->ViewCustomAttributes = "";

		// kerusakan
		if (strval($this->kerusakan->CurrentValue) <> "") {
			$arwrk = explode(",", $this->kerusakan->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`c_kerusakan`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_STRING, "");
			}
		$sSqlWrk = "SELECT `c_kerusakan`, `c_kerusakan` AS `DispFld`, `n_kerusakan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `kerusakan`";
		$sWhereWrk = "";
		$this->kerusakan->LookupFilters = array("dx1" => '`c_kerusakan`', "dx2" => '`n_kerusakan`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->kerusakan, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->kerusakan->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$arwrk[2] = $rswrk->fields('Disp2Fld');
					$this->kerusakan->ViewValue .= $this->kerusakan->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->kerusakan->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->kerusakan->ViewValue = $this->kerusakan->CurrentValue;
			}
		} else {
			$this->kerusakan->ViewValue = NULL;
		}
		$this->kerusakan->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

			// nik
			$this->nik->LinkCustomAttributes = "";
			$this->nik->HrefValue = "";
			$this->nik->TooltipValue = "";

			// d_temuan
			$this->d_temuan->LinkCustomAttributes = "";
			$this->d_temuan->HrefValue = "";
			$this->d_temuan->TooltipValue = "";

			// kerusakan
			$this->kerusakan->LinkCustomAttributes = "";
			$this->kerusakan->HrefValue = "";
			$this->kerusakan->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nik
			$this->nik->EditCustomAttributes = "";
			if (trim(strval($this->nik->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`nik`" . ew_SearchString("=", $this->nik->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `nik`, `nik` AS `DispFld`, `nama` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `karyawan`";
			$sWhereWrk = "";
			$this->nik->LookupFilters = array("dx1" => '`nik`', "dx2" => '`nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->nik->ViewValue = $this->nik->DisplayValue($arwrk);
			} else {
				$this->nik->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->nik->EditValue = $arwrk;

			// d_temuan
			$this->d_temuan->EditAttrs["class"] = "form-control";
			$this->d_temuan->EditCustomAttributes = "";
			$this->d_temuan->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->d_temuan->CurrentValue, 8));
			$this->d_temuan->PlaceHolder = ew_RemoveHtml($this->d_temuan->FldCaption());

			// kerusakan
			$this->kerusakan->EditCustomAttributes = "";
			if (trim(strval($this->kerusakan->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->kerusakan->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`c_kerusakan`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_STRING, "");
				}
			}
			$sSqlWrk = "SELECT `c_kerusakan`, `c_kerusakan` AS `DispFld`, `n_kerusakan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `kerusakan`";
			$sWhereWrk = "";
			$this->kerusakan->LookupFilters = array("dx1" => '`c_kerusakan`', "dx2" => '`n_kerusakan`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->kerusakan, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->kerusakan->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->kerusakan->ViewValue .= $this->kerusakan->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->kerusakan->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->kerusakan->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->kerusakan->EditValue = $arwrk;

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// Add refer script
			// nik

			$this->nik->LinkCustomAttributes = "";
			$this->nik->HrefValue = "";

			// d_temuan
			$this->d_temuan->LinkCustomAttributes = "";
			$this->d_temuan->HrefValue = "";

			// kerusakan
			$this->kerusakan->LinkCustomAttributes = "";
			$this->kerusakan->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
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
		if (!$this->nik->FldIsDetailKey && !is_null($this->nik->FormValue) && $this->nik->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nik->FldCaption(), $this->nik->ReqErrMsg));
		}
		if (!$this->d_temuan->FldIsDetailKey && !is_null($this->d_temuan->FormValue) && $this->d_temuan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->d_temuan->FldCaption(), $this->d_temuan->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->d_temuan->FormValue)) {
			ew_AddMessage($gsFormError, $this->d_temuan->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("temuan_kerusakan_foto", $DetailTblVar) && $GLOBALS["temuan_kerusakan_foto"]->DetailAdd) {
			if (!isset($GLOBALS["temuan_kerusakan_foto_grid"])) $GLOBALS["temuan_kerusakan_foto_grid"] = new ctemuan_kerusakan_foto_grid(); // get detail page object
			$GLOBALS["temuan_kerusakan_foto_grid"]->ValidateGridForm();
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

		// nik
		$this->nik->SetDbValueDef($rsnew, $this->nik->CurrentValue, "", FALSE);

		// d_temuan
		$this->d_temuan->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->d_temuan->CurrentValue, 1), ew_CurrentDate(), FALSE);

		// kerusakan
		$this->kerusakan->SetDbValueDef($rsnew, $this->kerusakan->CurrentValue, NULL, FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, FALSE);

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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("temuan_kerusakan_foto", $DetailTblVar) && $GLOBALS["temuan_kerusakan_foto"]->DetailAdd) {
				$GLOBALS["temuan_kerusakan_foto"]->temuan_kerusakan_id->setSessionValue($this->id->CurrentValue); // Set master key
				if (!isset($GLOBALS["temuan_kerusakan_foto_grid"])) $GLOBALS["temuan_kerusakan_foto_grid"] = new ctemuan_kerusakan_foto_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "temuan_kerusakan_foto"); // Load user level of detail table
				$AddRow = $GLOBALS["temuan_kerusakan_foto_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["temuan_kerusakan_foto"]->temuan_kerusakan_id->setSessionValue(""); // Clear master key if insert failed
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
			if (in_array("temuan_kerusakan_foto", $DetailTblVar)) {
				if (!isset($GLOBALS["temuan_kerusakan_foto_grid"]))
					$GLOBALS["temuan_kerusakan_foto_grid"] = new ctemuan_kerusakan_foto_grid;
				if ($GLOBALS["temuan_kerusakan_foto_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["temuan_kerusakan_foto_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["temuan_kerusakan_foto_grid"]->CurrentMode = "add";
					$GLOBALS["temuan_kerusakan_foto_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["temuan_kerusakan_foto_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["temuan_kerusakan_foto_grid"]->setStartRecordNumber(1);
					$GLOBALS["temuan_kerusakan_foto_grid"]->temuan_kerusakan_id->FldIsDetailKey = TRUE;
					$GLOBALS["temuan_kerusakan_foto_grid"]->temuan_kerusakan_id->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["temuan_kerusakan_foto_grid"]->temuan_kerusakan_id->setSessionValue($GLOBALS["temuan_kerusakan_foto_grid"]->temuan_kerusakan_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("temuan_kerusakanlist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk = "SELECT `nik` AS `LinkFld`, `nik` AS `DispFld`, `nama` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `karyawan`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`nik`', "dx2" => '`nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`nik` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->nik, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_kerusakan":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `c_kerusakan` AS `LinkFld`, `c_kerusakan` AS `DispFld`, `n_kerusakan` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `kerusakan`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`c_kerusakan`', "dx2" => '`n_kerusakan`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`c_kerusakan` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->kerusakan, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($temuan_kerusakan_add)) $temuan_kerusakan_add = new ctemuan_kerusakan_add();

// Page init
$temuan_kerusakan_add->Page_Init();

// Page main
$temuan_kerusakan_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$temuan_kerusakan_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftemuan_kerusakanadd = new ew_Form("ftemuan_kerusakanadd", "add");

// Validate form
ftemuan_kerusakanadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nik");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $temuan_kerusakan->nik->FldCaption(), $temuan_kerusakan->nik->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_d_temuan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $temuan_kerusakan->d_temuan->FldCaption(), $temuan_kerusakan->d_temuan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_d_temuan");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($temuan_kerusakan->d_temuan->FldErrMsg()) ?>");

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
ftemuan_kerusakanadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftemuan_kerusakanadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
ftemuan_kerusakanadd.Lists["x_nik"] = {"LinkField":"x_nik","Ajax":true,"AutoFill":false,"DisplayFields":["x_nik","x_nama","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"karyawan"};
ftemuan_kerusakanadd.Lists["x_nik"].Data = "<?php echo $temuan_kerusakan_add->nik->LookupFilterQuery(FALSE, "add") ?>";
ftemuan_kerusakanadd.Lists["x_kerusakan[]"] = {"LinkField":"x_c_kerusakan","Ajax":true,"AutoFill":false,"DisplayFields":["x_c_kerusakan","x_n_kerusakan","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"kerusakan"};
ftemuan_kerusakanadd.Lists["x_kerusakan[]"].Data = "<?php echo $temuan_kerusakan_add->kerusakan->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $temuan_kerusakan_add->ShowPageHeader(); ?>
<?php
$temuan_kerusakan_add->ShowMessage();
?>
<form name="ftemuan_kerusakanadd" id="ftemuan_kerusakanadd" class="<?php echo $temuan_kerusakan_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($temuan_kerusakan_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $temuan_kerusakan_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="temuan_kerusakan">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($temuan_kerusakan_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($temuan_kerusakan->nik->Visible) { // nik ?>
	<div id="r_nik" class="form-group">
		<label id="elh_temuan_kerusakan_nik" for="x_nik" class="<?php echo $temuan_kerusakan_add->LeftColumnClass ?>"><?php echo $temuan_kerusakan->nik->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $temuan_kerusakan_add->RightColumnClass ?>"><div<?php echo $temuan_kerusakan->nik->CellAttributes() ?>>
<span id="el_temuan_kerusakan_nik">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_nik"><?php echo (strval($temuan_kerusakan->nik->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $temuan_kerusakan->nik->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($temuan_kerusakan->nik->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_nik',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($temuan_kerusakan->nik->ReadOnly || $temuan_kerusakan->nik->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="temuan_kerusakan" data-field="x_nik" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $temuan_kerusakan->nik->DisplayValueSeparatorAttribute() ?>" name="x_nik" id="x_nik" value="<?php echo $temuan_kerusakan->nik->CurrentValue ?>"<?php echo $temuan_kerusakan->nik->EditAttributes() ?>>
</span>
<?php echo $temuan_kerusakan->nik->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($temuan_kerusakan->d_temuan->Visible) { // d_temuan ?>
	<div id="r_d_temuan" class="form-group">
		<label id="elh_temuan_kerusakan_d_temuan" for="x_d_temuan" class="<?php echo $temuan_kerusakan_add->LeftColumnClass ?>"><?php echo $temuan_kerusakan->d_temuan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $temuan_kerusakan_add->RightColumnClass ?>"><div<?php echo $temuan_kerusakan->d_temuan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_d_temuan">
<input type="text" data-table="temuan_kerusakan" data-field="x_d_temuan" data-format="1" name="x_d_temuan" id="x_d_temuan" placeholder="<?php echo ew_HtmlEncode($temuan_kerusakan->d_temuan->getPlaceHolder()) ?>" value="<?php echo $temuan_kerusakan->d_temuan->EditValue ?>"<?php echo $temuan_kerusakan->d_temuan->EditAttributes() ?>>
<?php if (!$temuan_kerusakan->d_temuan->ReadOnly && !$temuan_kerusakan->d_temuan->Disabled && !isset($temuan_kerusakan->d_temuan->EditAttrs["readonly"]) && !isset($temuan_kerusakan->d_temuan->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("ftemuan_kerusakanadd", "x_d_temuan", {"ignoreReadonly":true,"useCurrent":false,"format":1});
</script>
<?php } ?>
</span>
<?php echo $temuan_kerusakan->d_temuan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($temuan_kerusakan->kerusakan->Visible) { // kerusakan ?>
	<div id="r_kerusakan" class="form-group">
		<label id="elh_temuan_kerusakan_kerusakan" for="x_kerusakan" class="<?php echo $temuan_kerusakan_add->LeftColumnClass ?>"><?php echo $temuan_kerusakan->kerusakan->FldCaption() ?></label>
		<div class="<?php echo $temuan_kerusakan_add->RightColumnClass ?>"><div<?php echo $temuan_kerusakan->kerusakan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_kerusakan">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_kerusakan"><?php echo (strval($temuan_kerusakan->kerusakan->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $temuan_kerusakan->kerusakan->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($temuan_kerusakan->kerusakan->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_kerusakan[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($temuan_kerusakan->kerusakan->ReadOnly || $temuan_kerusakan->kerusakan->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="temuan_kerusakan" data-field="x_kerusakan" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $temuan_kerusakan->kerusakan->DisplayValueSeparatorAttribute() ?>" name="x_kerusakan[]" id="x_kerusakan[]" value="<?php echo $temuan_kerusakan->kerusakan->CurrentValue ?>"<?php echo $temuan_kerusakan->kerusakan->EditAttributes() ?>>
</span>
<?php echo $temuan_kerusakan->kerusakan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($temuan_kerusakan->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_temuan_kerusakan_keterangan" for="x_keterangan" class="<?php echo $temuan_kerusakan_add->LeftColumnClass ?>"><?php echo $temuan_kerusakan->keterangan->FldCaption() ?></label>
		<div class="<?php echo $temuan_kerusakan_add->RightColumnClass ?>"><div<?php echo $temuan_kerusakan->keterangan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_keterangan">
<input type="text" data-table="temuan_kerusakan" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($temuan_kerusakan->keterangan->getPlaceHolder()) ?>" value="<?php echo $temuan_kerusakan->keterangan->EditValue ?>"<?php echo $temuan_kerusakan->keterangan->EditAttributes() ?>>
</span>
<?php echo $temuan_kerusakan->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("temuan_kerusakan_foto", explode(",", $temuan_kerusakan->getCurrentDetailTable())) && $temuan_kerusakan_foto->DetailAdd) {
?>
<?php if ($temuan_kerusakan->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("temuan_kerusakan_foto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "temuan_kerusakan_fotogrid.php" ?>
<?php } ?>
<?php if (!$temuan_kerusakan_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $temuan_kerusakan_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $temuan_kerusakan_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
ftemuan_kerusakanadd.Init();
</script>
<?php
$temuan_kerusakan_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$temuan_kerusakan_add->Page_Terminate();
?>
