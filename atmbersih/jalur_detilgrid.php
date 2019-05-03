<?php include_once "userinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jalur_detil_grid)) $jalur_detil_grid = new cjalur_detil_grid();

// Page init
$jalur_detil_grid->Page_Init();

// Page main
$jalur_detil_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jalur_detil_grid->Page_Render();
?>
<?php if ($jalur_detil->Export == "") { ?>
<script type="text/javascript">

// Form object
var fjalur_detilgrid = new ew_Form("fjalur_detilgrid", "grid");
fjalur_detilgrid.FormKeyCountName = '<?php echo $jalur_detil_grid->FormKeyCountName ?>';

// Validate form
fjalur_detilgrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fjalur_detilgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "c_bank", false)) return false;
	if (ew_ValueChanged(fobj, infix, "atmid", false)) return false;
	return true;
}

// Form_CustomValidate event
fjalur_detilgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fjalur_detilgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fjalur_detilgrid.Lists["x_c_bank"] = {"LinkField":"x_c_bank","Ajax":true,"AutoFill":false,"DisplayFields":["x_n_bank","","",""],"ParentFields":[],"ChildFields":["x_atmid"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"bank"};
fjalur_detilgrid.Lists["x_c_bank"].Data = "<?php echo $jalur_detil_grid->c_bank->LookupFilterQuery(FALSE, "grid") ?>";
fjalur_detilgrid.Lists["x_atmid"] = {"LinkField":"x_atmid","Ajax":true,"AutoFill":false,"DisplayFields":["x_atmid","x_n_atm","x_lokasi",""],"ParentFields":["x_c_bank"],"ChildFields":[],"FilterFields":["x_c_bank"],"Options":[],"Template":"","LinkTable":"atm"};
fjalur_detilgrid.Lists["x_atmid"].Data = "<?php echo $jalur_detil_grid->atmid->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($jalur_detil->CurrentAction == "gridadd") {
	if ($jalur_detil->CurrentMode == "copy") {
		$bSelectLimit = $jalur_detil_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$jalur_detil_grid->TotalRecs = $jalur_detil->ListRecordCount();
			$jalur_detil_grid->Recordset = $jalur_detil_grid->LoadRecordset($jalur_detil_grid->StartRec-1, $jalur_detil_grid->DisplayRecs);
		} else {
			if ($jalur_detil_grid->Recordset = $jalur_detil_grid->LoadRecordset())
				$jalur_detil_grid->TotalRecs = $jalur_detil_grid->Recordset->RecordCount();
		}
		$jalur_detil_grid->StartRec = 1;
		$jalur_detil_grid->DisplayRecs = $jalur_detil_grid->TotalRecs;
	} else {
		$jalur_detil->CurrentFilter = "0=1";
		$jalur_detil_grid->StartRec = 1;
		$jalur_detil_grid->DisplayRecs = $jalur_detil->GridAddRowCount;
	}
	$jalur_detil_grid->TotalRecs = $jalur_detil_grid->DisplayRecs;
	$jalur_detil_grid->StopRec = $jalur_detil_grid->DisplayRecs;
} else {
	$bSelectLimit = $jalur_detil_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($jalur_detil_grid->TotalRecs <= 0)
			$jalur_detil_grid->TotalRecs = $jalur_detil->ListRecordCount();
	} else {
		if (!$jalur_detil_grid->Recordset && ($jalur_detil_grid->Recordset = $jalur_detil_grid->LoadRecordset()))
			$jalur_detil_grid->TotalRecs = $jalur_detil_grid->Recordset->RecordCount();
	}
	$jalur_detil_grid->StartRec = 1;
	$jalur_detil_grid->DisplayRecs = $jalur_detil_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$jalur_detil_grid->Recordset = $jalur_detil_grid->LoadRecordset($jalur_detil_grid->StartRec-1, $jalur_detil_grid->DisplayRecs);

	// Set no record found message
	if ($jalur_detil->CurrentAction == "" && $jalur_detil_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$jalur_detil_grid->setWarningMessage(ew_DeniedMsg());
		if ($jalur_detil_grid->SearchWhere == "0=101")
			$jalur_detil_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$jalur_detil_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$jalur_detil_grid->RenderOtherOptions();
?>
<?php $jalur_detil_grid->ShowPageHeader(); ?>
<?php
$jalur_detil_grid->ShowMessage();
?>
<?php if ($jalur_detil_grid->TotalRecs > 0 || $jalur_detil->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($jalur_detil_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> jalur_detil">
<div id="fjalur_detilgrid" class="ewForm ewListForm form-inline">
<?php if ($jalur_detil_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($jalur_detil_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_jalur_detil" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_jalur_detilgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$jalur_detil_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$jalur_detil_grid->RenderListOptions();

// Render list options (header, left)
$jalur_detil_grid->ListOptions->Render("header", "left");
?>
<?php if ($jalur_detil->c_bank->Visible) { // c_bank ?>
	<?php if ($jalur_detil->SortUrl($jalur_detil->c_bank) == "") { ?>
		<th data-name="c_bank" class="<?php echo $jalur_detil->c_bank->HeaderCellClass() ?>"><div id="elh_jalur_detil_c_bank" class="jalur_detil_c_bank"><div class="ewTableHeaderCaption"><?php echo $jalur_detil->c_bank->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="c_bank" class="<?php echo $jalur_detil->c_bank->HeaderCellClass() ?>"><div><div id="elh_jalur_detil_c_bank" class="jalur_detil_c_bank">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jalur_detil->c_bank->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jalur_detil->c_bank->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jalur_detil->c_bank->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($jalur_detil->atmid->Visible) { // atmid ?>
	<?php if ($jalur_detil->SortUrl($jalur_detil->atmid) == "") { ?>
		<th data-name="atmid" class="<?php echo $jalur_detil->atmid->HeaderCellClass() ?>"><div id="elh_jalur_detil_atmid" class="jalur_detil_atmid"><div class="ewTableHeaderCaption"><?php echo $jalur_detil->atmid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atmid" class="<?php echo $jalur_detil->atmid->HeaderCellClass() ?>"><div><div id="elh_jalur_detil_atmid" class="jalur_detil_atmid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jalur_detil->atmid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jalur_detil->atmid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jalur_detil->atmid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$jalur_detil_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$jalur_detil_grid->StartRec = 1;
$jalur_detil_grid->StopRec = $jalur_detil_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($jalur_detil_grid->FormKeyCountName) && ($jalur_detil->CurrentAction == "gridadd" || $jalur_detil->CurrentAction == "gridedit" || $jalur_detil->CurrentAction == "F")) {
		$jalur_detil_grid->KeyCount = $objForm->GetValue($jalur_detil_grid->FormKeyCountName);
		$jalur_detil_grid->StopRec = $jalur_detil_grid->StartRec + $jalur_detil_grid->KeyCount - 1;
	}
}
$jalur_detil_grid->RecCnt = $jalur_detil_grid->StartRec - 1;
if ($jalur_detil_grid->Recordset && !$jalur_detil_grid->Recordset->EOF) {
	$jalur_detil_grid->Recordset->MoveFirst();
	$bSelectLimit = $jalur_detil_grid->UseSelectLimit;
	if (!$bSelectLimit && $jalur_detil_grid->StartRec > 1)
		$jalur_detil_grid->Recordset->Move($jalur_detil_grid->StartRec - 1);
} elseif (!$jalur_detil->AllowAddDeleteRow && $jalur_detil_grid->StopRec == 0) {
	$jalur_detil_grid->StopRec = $jalur_detil->GridAddRowCount;
}

// Initialize aggregate
$jalur_detil->RowType = EW_ROWTYPE_AGGREGATEINIT;
$jalur_detil->ResetAttrs();
$jalur_detil_grid->RenderRow();
if ($jalur_detil->CurrentAction == "gridadd")
	$jalur_detil_grid->RowIndex = 0;
if ($jalur_detil->CurrentAction == "gridedit")
	$jalur_detil_grid->RowIndex = 0;
while ($jalur_detil_grid->RecCnt < $jalur_detil_grid->StopRec) {
	$jalur_detil_grid->RecCnt++;
	if (intval($jalur_detil_grid->RecCnt) >= intval($jalur_detil_grid->StartRec)) {
		$jalur_detil_grid->RowCnt++;
		if ($jalur_detil->CurrentAction == "gridadd" || $jalur_detil->CurrentAction == "gridedit" || $jalur_detil->CurrentAction == "F") {
			$jalur_detil_grid->RowIndex++;
			$objForm->Index = $jalur_detil_grid->RowIndex;
			if ($objForm->HasValue($jalur_detil_grid->FormActionName))
				$jalur_detil_grid->RowAction = strval($objForm->GetValue($jalur_detil_grid->FormActionName));
			elseif ($jalur_detil->CurrentAction == "gridadd")
				$jalur_detil_grid->RowAction = "insert";
			else
				$jalur_detil_grid->RowAction = "";
		}

		// Set up key count
		$jalur_detil_grid->KeyCount = $jalur_detil_grid->RowIndex;

		// Init row class and style
		$jalur_detil->ResetAttrs();
		$jalur_detil->CssClass = "";
		if ($jalur_detil->CurrentAction == "gridadd") {
			if ($jalur_detil->CurrentMode == "copy") {
				$jalur_detil_grid->LoadRowValues($jalur_detil_grid->Recordset); // Load row values
				$jalur_detil_grid->SetRecordKey($jalur_detil_grid->RowOldKey, $jalur_detil_grid->Recordset); // Set old record key
			} else {
				$jalur_detil_grid->LoadRowValues(); // Load default values
				$jalur_detil_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$jalur_detil_grid->LoadRowValues($jalur_detil_grid->Recordset); // Load row values
		}
		$jalur_detil->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($jalur_detil->CurrentAction == "gridadd") // Grid add
			$jalur_detil->RowType = EW_ROWTYPE_ADD; // Render add
		if ($jalur_detil->CurrentAction == "gridadd" && $jalur_detil->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$jalur_detil_grid->RestoreCurrentRowFormValues($jalur_detil_grid->RowIndex); // Restore form values
		if ($jalur_detil->CurrentAction == "gridedit") { // Grid edit
			if ($jalur_detil->EventCancelled) {
				$jalur_detil_grid->RestoreCurrentRowFormValues($jalur_detil_grid->RowIndex); // Restore form values
			}
			if ($jalur_detil_grid->RowAction == "insert")
				$jalur_detil->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$jalur_detil->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($jalur_detil->CurrentAction == "gridedit" && ($jalur_detil->RowType == EW_ROWTYPE_EDIT || $jalur_detil->RowType == EW_ROWTYPE_ADD) && $jalur_detil->EventCancelled) // Update failed
			$jalur_detil_grid->RestoreCurrentRowFormValues($jalur_detil_grid->RowIndex); // Restore form values
		if ($jalur_detil->RowType == EW_ROWTYPE_EDIT) // Edit row
			$jalur_detil_grid->EditRowCnt++;
		if ($jalur_detil->CurrentAction == "F") // Confirm row
			$jalur_detil_grid->RestoreCurrentRowFormValues($jalur_detil_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$jalur_detil->RowAttrs = array_merge($jalur_detil->RowAttrs, array('data-rowindex'=>$jalur_detil_grid->RowCnt, 'id'=>'r' . $jalur_detil_grid->RowCnt . '_jalur_detil', 'data-rowtype'=>$jalur_detil->RowType));

		// Render row
		$jalur_detil_grid->RenderRow();

		// Render list options
		$jalur_detil_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($jalur_detil_grid->RowAction <> "delete" && $jalur_detil_grid->RowAction <> "insertdelete" && !($jalur_detil_grid->RowAction == "insert" && $jalur_detil->CurrentAction == "F" && $jalur_detil_grid->EmptyRow())) {
?>
	<tr<?php echo $jalur_detil->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jalur_detil_grid->ListOptions->Render("body", "left", $jalur_detil_grid->RowCnt);
?>
	<?php if ($jalur_detil->c_bank->Visible) { // c_bank ?>
		<td data-name="c_bank"<?php echo $jalur_detil->c_bank->CellAttributes() ?>>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_c_bank" class="form-group jalur_detil_c_bank">
<?php $jalur_detil->c_bank->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$jalur_detil->c_bank->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank"><?php echo (strval($jalur_detil->c_bank->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->c_bank->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->c_bank->ReadOnly || $jalur_detil->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->c_bank->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo $jalur_detil->c_bank->CurrentValue ?>"<?php echo $jalur_detil->c_bank->EditAttributes() ?>>
</span>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->OldValue) ?>">
<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_c_bank" class="form-group jalur_detil_c_bank">
<?php $jalur_detil->c_bank->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$jalur_detil->c_bank->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank"><?php echo (strval($jalur_detil->c_bank->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->c_bank->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->c_bank->ReadOnly || $jalur_detil->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->c_bank->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo $jalur_detil->c_bank->CurrentValue ?>"<?php echo $jalur_detil->c_bank->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_c_bank" class="jalur_detil_c_bank">
<span<?php echo $jalur_detil->c_bank->ViewAttributes() ?>>
<?php echo $jalur_detil->c_bank->ListViewValue() ?></span>
</span>
<?php if ($jalur_detil->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->FormValue) ?>">
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="fjalur_detilgrid$x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="fjalur_detilgrid$x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->FormValue) ?>">
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="fjalur_detilgrid$o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="fjalur_detilgrid$o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="jalur_detil" data-field="x_id" name="x<?php echo $jalur_detil_grid->RowIndex ?>_id" id="x<?php echo $jalur_detil_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jalur_detil->id->CurrentValue) ?>">
<input type="hidden" data-table="jalur_detil" data-field="x_id" name="o<?php echo $jalur_detil_grid->RowIndex ?>_id" id="o<?php echo $jalur_detil_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jalur_detil->id->OldValue) ?>">
<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_EDIT || $jalur_detil->CurrentMode == "edit") { ?>
<input type="hidden" data-table="jalur_detil" data-field="x_id" name="x<?php echo $jalur_detil_grid->RowIndex ?>_id" id="x<?php echo $jalur_detil_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jalur_detil->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($jalur_detil->atmid->Visible) { // atmid ?>
		<td data-name="atmid"<?php echo $jalur_detil->atmid->CellAttributes() ?>>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_atmid" class="form-group jalur_detil_atmid">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_atmid"><?php echo (strval($jalur_detil->atmid->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->atmid->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->atmid->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_atmid',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->atmid->ReadOnly || $jalur_detil->atmid->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->atmid->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo $jalur_detil->atmid->CurrentValue ?>"<?php echo $jalur_detil->atmid->EditAttributes() ?>>
</span>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->OldValue) ?>">
<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_atmid" class="form-group jalur_detil_atmid">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_atmid"><?php echo (strval($jalur_detil->atmid->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->atmid->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->atmid->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_atmid',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->atmid->ReadOnly || $jalur_detil->atmid->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->atmid->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo $jalur_detil->atmid->CurrentValue ?>"<?php echo $jalur_detil->atmid->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jalur_detil_grid->RowCnt ?>_jalur_detil_atmid" class="jalur_detil_atmid">
<span<?php echo $jalur_detil->atmid->ViewAttributes() ?>>
<?php echo $jalur_detil->atmid->ListViewValue() ?></span>
</span>
<?php if ($jalur_detil->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->FormValue) ?>">
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="fjalur_detilgrid$x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="fjalur_detilgrid$x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->FormValue) ?>">
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="fjalur_detilgrid$o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="fjalur_detilgrid$o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$jalur_detil_grid->ListOptions->Render("body", "right", $jalur_detil_grid->RowCnt);
?>
	</tr>
<?php if ($jalur_detil->RowType == EW_ROWTYPE_ADD || $jalur_detil->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fjalur_detilgrid.UpdateOpts(<?php echo $jalur_detil_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($jalur_detil->CurrentAction <> "gridadd" || $jalur_detil->CurrentMode == "copy")
		if (!$jalur_detil_grid->Recordset->EOF) $jalur_detil_grid->Recordset->MoveNext();
}
?>
<?php
	if ($jalur_detil->CurrentMode == "add" || $jalur_detil->CurrentMode == "copy" || $jalur_detil->CurrentMode == "edit") {
		$jalur_detil_grid->RowIndex = '$rowindex$';
		$jalur_detil_grid->LoadRowValues();

		// Set row properties
		$jalur_detil->ResetAttrs();
		$jalur_detil->RowAttrs = array_merge($jalur_detil->RowAttrs, array('data-rowindex'=>$jalur_detil_grid->RowIndex, 'id'=>'r0_jalur_detil', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($jalur_detil->RowAttrs["class"], "ewTemplate");
		$jalur_detil->RowType = EW_ROWTYPE_ADD;

		// Render row
		$jalur_detil_grid->RenderRow();

		// Render list options
		$jalur_detil_grid->RenderListOptions();
		$jalur_detil_grid->StartRowCnt = 0;
?>
	<tr<?php echo $jalur_detil->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jalur_detil_grid->ListOptions->Render("body", "left", $jalur_detil_grid->RowIndex);
?>
	<?php if ($jalur_detil->c_bank->Visible) { // c_bank ?>
		<td data-name="c_bank">
<?php if ($jalur_detil->CurrentAction <> "F") { ?>
<span id="el$rowindex$_jalur_detil_c_bank" class="form-group jalur_detil_c_bank">
<?php $jalur_detil->c_bank->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$jalur_detil->c_bank->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank"><?php echo (strval($jalur_detil->c_bank->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->c_bank->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->c_bank->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->c_bank->ReadOnly || $jalur_detil->c_bank->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->c_bank->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo $jalur_detil->c_bank->CurrentValue ?>"<?php echo $jalur_detil->c_bank->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_jalur_detil_c_bank" class="form-group jalur_detil_c_bank">
<span<?php echo $jalur_detil->c_bank->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jalur_detil->c_bank->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="x<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jalur_detil" data-field="x_c_bank" name="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" id="o<?php echo $jalur_detil_grid->RowIndex ?>_c_bank" value="<?php echo ew_HtmlEncode($jalur_detil->c_bank->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($jalur_detil->atmid->Visible) { // atmid ?>
		<td data-name="atmid">
<?php if ($jalur_detil->CurrentAction <> "F") { ?>
<span id="el$rowindex$_jalur_detil_atmid" class="form-group jalur_detil_atmid">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next(":not([disabled])").click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $jalur_detil_grid->RowIndex ?>_atmid"><?php echo (strval($jalur_detil->atmid->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $jalur_detil->atmid->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($jalur_detil->atmid->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $jalur_detil_grid->RowIndex ?>_atmid',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($jalur_detil->atmid->ReadOnly || $jalur_detil->atmid->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $jalur_detil->atmid->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo $jalur_detil->atmid->CurrentValue ?>"<?php echo $jalur_detil->atmid->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_jalur_detil_atmid" class="form-group jalur_detil_atmid">
<span<?php echo $jalur_detil->atmid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jalur_detil->atmid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="x<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jalur_detil" data-field="x_atmid" name="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" id="o<?php echo $jalur_detil_grid->RowIndex ?>_atmid" value="<?php echo ew_HtmlEncode($jalur_detil->atmid->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$jalur_detil_grid->ListOptions->Render("body", "right", $jalur_detil_grid->RowIndex);
?>
<script type="text/javascript">
fjalur_detilgrid.UpdateOpts(<?php echo $jalur_detil_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($jalur_detil->CurrentMode == "add" || $jalur_detil->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $jalur_detil_grid->FormKeyCountName ?>" id="<?php echo $jalur_detil_grid->FormKeyCountName ?>" value="<?php echo $jalur_detil_grid->KeyCount ?>">
<?php echo $jalur_detil_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($jalur_detil->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $jalur_detil_grid->FormKeyCountName ?>" id="<?php echo $jalur_detil_grid->FormKeyCountName ?>" value="<?php echo $jalur_detil_grid->KeyCount ?>">
<?php echo $jalur_detil_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($jalur_detil->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fjalur_detilgrid">
</div>
<?php

// Close recordset
if ($jalur_detil_grid->Recordset)
	$jalur_detil_grid->Recordset->Close();
?>
<?php if ($jalur_detil_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($jalur_detil_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($jalur_detil_grid->TotalRecs == 0 && $jalur_detil->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($jalur_detil_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($jalur_detil->Export == "") { ?>
<script type="text/javascript">
fjalur_detilgrid.Init();
</script>
<?php } ?>
<?php
$jalur_detil_grid->Page_Terminate();
?>
