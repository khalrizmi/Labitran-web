<?php include_once "userinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($temuan_kerusakan_foto_grid)) $temuan_kerusakan_foto_grid = new ctemuan_kerusakan_foto_grid();

// Page init
$temuan_kerusakan_foto_grid->Page_Init();

// Page main
$temuan_kerusakan_foto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$temuan_kerusakan_foto_grid->Page_Render();
?>
<?php if ($temuan_kerusakan_foto->Export == "") { ?>
<script type="text/javascript">

// Form object
var ftemuan_kerusakan_fotogrid = new ew_Form("ftemuan_kerusakan_fotogrid", "grid");
ftemuan_kerusakan_fotogrid.FormKeyCountName = '<?php echo $temuan_kerusakan_foto_grid->FormKeyCountName ?>';

// Validate form
ftemuan_kerusakan_fotogrid.Validate = function() {
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
ftemuan_kerusakan_fotogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "foto", false)) return false;
	return true;
}

// Form_CustomValidate event
ftemuan_kerusakan_fotogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
ftemuan_kerusakan_fotogrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($temuan_kerusakan_foto->CurrentAction == "gridadd") {
	if ($temuan_kerusakan_foto->CurrentMode == "copy") {
		$bSelectLimit = $temuan_kerusakan_foto_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$temuan_kerusakan_foto_grid->TotalRecs = $temuan_kerusakan_foto->ListRecordCount();
			$temuan_kerusakan_foto_grid->Recordset = $temuan_kerusakan_foto_grid->LoadRecordset($temuan_kerusakan_foto_grid->StartRec-1, $temuan_kerusakan_foto_grid->DisplayRecs);
		} else {
			if ($temuan_kerusakan_foto_grid->Recordset = $temuan_kerusakan_foto_grid->LoadRecordset())
				$temuan_kerusakan_foto_grid->TotalRecs = $temuan_kerusakan_foto_grid->Recordset->RecordCount();
		}
		$temuan_kerusakan_foto_grid->StartRec = 1;
		$temuan_kerusakan_foto_grid->DisplayRecs = $temuan_kerusakan_foto_grid->TotalRecs;
	} else {
		$temuan_kerusakan_foto->CurrentFilter = "0=1";
		$temuan_kerusakan_foto_grid->StartRec = 1;
		$temuan_kerusakan_foto_grid->DisplayRecs = $temuan_kerusakan_foto->GridAddRowCount;
	}
	$temuan_kerusakan_foto_grid->TotalRecs = $temuan_kerusakan_foto_grid->DisplayRecs;
	$temuan_kerusakan_foto_grid->StopRec = $temuan_kerusakan_foto_grid->DisplayRecs;
} else {
	$bSelectLimit = $temuan_kerusakan_foto_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($temuan_kerusakan_foto_grid->TotalRecs <= 0)
			$temuan_kerusakan_foto_grid->TotalRecs = $temuan_kerusakan_foto->ListRecordCount();
	} else {
		if (!$temuan_kerusakan_foto_grid->Recordset && ($temuan_kerusakan_foto_grid->Recordset = $temuan_kerusakan_foto_grid->LoadRecordset()))
			$temuan_kerusakan_foto_grid->TotalRecs = $temuan_kerusakan_foto_grid->Recordset->RecordCount();
	}
	$temuan_kerusakan_foto_grid->StartRec = 1;
	$temuan_kerusakan_foto_grid->DisplayRecs = $temuan_kerusakan_foto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$temuan_kerusakan_foto_grid->Recordset = $temuan_kerusakan_foto_grid->LoadRecordset($temuan_kerusakan_foto_grid->StartRec-1, $temuan_kerusakan_foto_grid->DisplayRecs);

	// Set no record found message
	if ($temuan_kerusakan_foto->CurrentAction == "" && $temuan_kerusakan_foto_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$temuan_kerusakan_foto_grid->setWarningMessage(ew_DeniedMsg());
		if ($temuan_kerusakan_foto_grid->SearchWhere == "0=101")
			$temuan_kerusakan_foto_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$temuan_kerusakan_foto_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$temuan_kerusakan_foto_grid->RenderOtherOptions();
?>
<?php $temuan_kerusakan_foto_grid->ShowPageHeader(); ?>
<?php
$temuan_kerusakan_foto_grid->ShowMessage();
?>
<?php if ($temuan_kerusakan_foto_grid->TotalRecs > 0 || $temuan_kerusakan_foto->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($temuan_kerusakan_foto_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> temuan_kerusakan_foto">
<div id="ftemuan_kerusakan_fotogrid" class="ewForm ewListForm form-inline">
<?php if ($temuan_kerusakan_foto_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($temuan_kerusakan_foto_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_temuan_kerusakan_foto" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_temuan_kerusakan_fotogrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$temuan_kerusakan_foto_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$temuan_kerusakan_foto_grid->RenderListOptions();

// Render list options (header, left)
$temuan_kerusakan_foto_grid->ListOptions->Render("header", "left");
?>
<?php if ($temuan_kerusakan_foto->foto->Visible) { // foto ?>
	<?php if ($temuan_kerusakan_foto->SortUrl($temuan_kerusakan_foto->foto) == "") { ?>
		<th data-name="foto" class="<?php echo $temuan_kerusakan_foto->foto->HeaderCellClass() ?>"><div id="elh_temuan_kerusakan_foto_foto" class="temuan_kerusakan_foto_foto"><div class="ewTableHeaderCaption"><?php echo $temuan_kerusakan_foto->foto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="foto" class="<?php echo $temuan_kerusakan_foto->foto->HeaderCellClass() ?>"><div><div id="elh_temuan_kerusakan_foto_foto" class="temuan_kerusakan_foto_foto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $temuan_kerusakan_foto->foto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($temuan_kerusakan_foto->foto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($temuan_kerusakan_foto->foto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$temuan_kerusakan_foto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$temuan_kerusakan_foto_grid->StartRec = 1;
$temuan_kerusakan_foto_grid->StopRec = $temuan_kerusakan_foto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($temuan_kerusakan_foto_grid->FormKeyCountName) && ($temuan_kerusakan_foto->CurrentAction == "gridadd" || $temuan_kerusakan_foto->CurrentAction == "gridedit" || $temuan_kerusakan_foto->CurrentAction == "F")) {
		$temuan_kerusakan_foto_grid->KeyCount = $objForm->GetValue($temuan_kerusakan_foto_grid->FormKeyCountName);
		$temuan_kerusakan_foto_grid->StopRec = $temuan_kerusakan_foto_grid->StartRec + $temuan_kerusakan_foto_grid->KeyCount - 1;
	}
}
$temuan_kerusakan_foto_grid->RecCnt = $temuan_kerusakan_foto_grid->StartRec - 1;
if ($temuan_kerusakan_foto_grid->Recordset && !$temuan_kerusakan_foto_grid->Recordset->EOF) {
	$temuan_kerusakan_foto_grid->Recordset->MoveFirst();
	$bSelectLimit = $temuan_kerusakan_foto_grid->UseSelectLimit;
	if (!$bSelectLimit && $temuan_kerusakan_foto_grid->StartRec > 1)
		$temuan_kerusakan_foto_grid->Recordset->Move($temuan_kerusakan_foto_grid->StartRec - 1);
} elseif (!$temuan_kerusakan_foto->AllowAddDeleteRow && $temuan_kerusakan_foto_grid->StopRec == 0) {
	$temuan_kerusakan_foto_grid->StopRec = $temuan_kerusakan_foto->GridAddRowCount;
}

// Initialize aggregate
$temuan_kerusakan_foto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$temuan_kerusakan_foto->ResetAttrs();
$temuan_kerusakan_foto_grid->RenderRow();
if ($temuan_kerusakan_foto->CurrentAction == "gridadd")
	$temuan_kerusakan_foto_grid->RowIndex = 0;
if ($temuan_kerusakan_foto->CurrentAction == "gridedit")
	$temuan_kerusakan_foto_grid->RowIndex = 0;
while ($temuan_kerusakan_foto_grid->RecCnt < $temuan_kerusakan_foto_grid->StopRec) {
	$temuan_kerusakan_foto_grid->RecCnt++;
	if (intval($temuan_kerusakan_foto_grid->RecCnt) >= intval($temuan_kerusakan_foto_grid->StartRec)) {
		$temuan_kerusakan_foto_grid->RowCnt++;
		if ($temuan_kerusakan_foto->CurrentAction == "gridadd" || $temuan_kerusakan_foto->CurrentAction == "gridedit" || $temuan_kerusakan_foto->CurrentAction == "F") {
			$temuan_kerusakan_foto_grid->RowIndex++;
			$objForm->Index = $temuan_kerusakan_foto_grid->RowIndex;
			if ($objForm->HasValue($temuan_kerusakan_foto_grid->FormActionName))
				$temuan_kerusakan_foto_grid->RowAction = strval($objForm->GetValue($temuan_kerusakan_foto_grid->FormActionName));
			elseif ($temuan_kerusakan_foto->CurrentAction == "gridadd")
				$temuan_kerusakan_foto_grid->RowAction = "insert";
			else
				$temuan_kerusakan_foto_grid->RowAction = "";
		}

		// Set up key count
		$temuan_kerusakan_foto_grid->KeyCount = $temuan_kerusakan_foto_grid->RowIndex;

		// Init row class and style
		$temuan_kerusakan_foto->ResetAttrs();
		$temuan_kerusakan_foto->CssClass = "";
		if ($temuan_kerusakan_foto->CurrentAction == "gridadd") {
			if ($temuan_kerusakan_foto->CurrentMode == "copy") {
				$temuan_kerusakan_foto_grid->LoadRowValues($temuan_kerusakan_foto_grid->Recordset); // Load row values
				$temuan_kerusakan_foto_grid->SetRecordKey($temuan_kerusakan_foto_grid->RowOldKey, $temuan_kerusakan_foto_grid->Recordset); // Set old record key
			} else {
				$temuan_kerusakan_foto_grid->LoadRowValues(); // Load default values
				$temuan_kerusakan_foto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$temuan_kerusakan_foto_grid->LoadRowValues($temuan_kerusakan_foto_grid->Recordset); // Load row values
		}
		$temuan_kerusakan_foto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($temuan_kerusakan_foto->CurrentAction == "gridadd") // Grid add
			$temuan_kerusakan_foto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($temuan_kerusakan_foto->CurrentAction == "gridadd" && $temuan_kerusakan_foto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$temuan_kerusakan_foto_grid->RestoreCurrentRowFormValues($temuan_kerusakan_foto_grid->RowIndex); // Restore form values
		if ($temuan_kerusakan_foto->CurrentAction == "gridedit") { // Grid edit
			if ($temuan_kerusakan_foto->EventCancelled) {
				$temuan_kerusakan_foto_grid->RestoreCurrentRowFormValues($temuan_kerusakan_foto_grid->RowIndex); // Restore form values
			}
			if ($temuan_kerusakan_foto_grid->RowAction == "insert")
				$temuan_kerusakan_foto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$temuan_kerusakan_foto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($temuan_kerusakan_foto->CurrentAction == "gridedit" && ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_EDIT || $temuan_kerusakan_foto->RowType == EW_ROWTYPE_ADD) && $temuan_kerusakan_foto->EventCancelled) // Update failed
			$temuan_kerusakan_foto_grid->RestoreCurrentRowFormValues($temuan_kerusakan_foto_grid->RowIndex); // Restore form values
		if ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$temuan_kerusakan_foto_grid->EditRowCnt++;
		if ($temuan_kerusakan_foto->CurrentAction == "F") // Confirm row
			$temuan_kerusakan_foto_grid->RestoreCurrentRowFormValues($temuan_kerusakan_foto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$temuan_kerusakan_foto->RowAttrs = array_merge($temuan_kerusakan_foto->RowAttrs, array('data-rowindex'=>$temuan_kerusakan_foto_grid->RowCnt, 'id'=>'r' . $temuan_kerusakan_foto_grid->RowCnt . '_temuan_kerusakan_foto', 'data-rowtype'=>$temuan_kerusakan_foto->RowType));

		// Render row
		$temuan_kerusakan_foto_grid->RenderRow();

		// Render list options
		$temuan_kerusakan_foto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($temuan_kerusakan_foto_grid->RowAction <> "delete" && $temuan_kerusakan_foto_grid->RowAction <> "insertdelete" && !($temuan_kerusakan_foto_grid->RowAction == "insert" && $temuan_kerusakan_foto->CurrentAction == "F" && $temuan_kerusakan_foto_grid->EmptyRow())) {
?>
	<tr<?php echo $temuan_kerusakan_foto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$temuan_kerusakan_foto_grid->ListOptions->Render("body", "left", $temuan_kerusakan_foto_grid->RowCnt);
?>
	<?php if ($temuan_kerusakan_foto->foto->Visible) { // foto ?>
		<td data-name="foto"<?php echo $temuan_kerusakan_foto->foto->CellAttributes() ?>>
<?php if ($temuan_kerusakan_foto_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_temuan_kerusakan_foto_foto" class="form-group temuan_kerusakan_foto_foto">
<div id="fd_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $temuan_kerusakan_foto->foto->FldTitle() ? $temuan_kerusakan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($temuan_kerusakan_foto->foto->ReadOnly || $temuan_kerusakan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="temuan_kerusakan_foto" data-field="x_foto" name="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto"<?php echo $temuan_kerusakan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="0">
<input type="hidden" name="fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="temuan_kerusakan_foto" data-field="x_foto" name="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($temuan_kerusakan_foto->foto->OldValue) ?>">
<?php } elseif ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $temuan_kerusakan_foto_grid->RowCnt ?>_temuan_kerusakan_foto_foto" class="temuan_kerusakan_foto_foto">
<span<?php echo $temuan_kerusakan_foto->foto->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($temuan_kerusakan_foto->foto, $temuan_kerusakan_foto->foto->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $temuan_kerusakan_foto_grid->RowCnt ?>_temuan_kerusakan_foto_foto" class="form-group temuan_kerusakan_foto_foto">
<div id="fd_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $temuan_kerusakan_foto->foto->FldTitle() ? $temuan_kerusakan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($temuan_kerusakan_foto->foto->ReadOnly || $temuan_kerusakan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="temuan_kerusakan_foto" data-field="x_foto" name="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto"<?php echo $temuan_kerusakan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php if ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="temuan_kerusakan_foto" data-field="x_id" name="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" id="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($temuan_kerusakan_foto->id->CurrentValue) ?>">
<input type="hidden" data-table="temuan_kerusakan_foto" data-field="x_id" name="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" id="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($temuan_kerusakan_foto->id->OldValue) ?>">
<?php } ?>
<?php if ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_EDIT || $temuan_kerusakan_foto->CurrentMode == "edit") { ?>
<input type="hidden" data-table="temuan_kerusakan_foto" data-field="x_id" name="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" id="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($temuan_kerusakan_foto->id->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$temuan_kerusakan_foto_grid->ListOptions->Render("body", "right", $temuan_kerusakan_foto_grid->RowCnt);
?>
	</tr>
<?php if ($temuan_kerusakan_foto->RowType == EW_ROWTYPE_ADD || $temuan_kerusakan_foto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftemuan_kerusakan_fotogrid.UpdateOpts(<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($temuan_kerusakan_foto->CurrentAction <> "gridadd" || $temuan_kerusakan_foto->CurrentMode == "copy")
		if (!$temuan_kerusakan_foto_grid->Recordset->EOF) $temuan_kerusakan_foto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($temuan_kerusakan_foto->CurrentMode == "add" || $temuan_kerusakan_foto->CurrentMode == "copy" || $temuan_kerusakan_foto->CurrentMode == "edit") {
		$temuan_kerusakan_foto_grid->RowIndex = '$rowindex$';
		$temuan_kerusakan_foto_grid->LoadRowValues();

		// Set row properties
		$temuan_kerusakan_foto->ResetAttrs();
		$temuan_kerusakan_foto->RowAttrs = array_merge($temuan_kerusakan_foto->RowAttrs, array('data-rowindex'=>$temuan_kerusakan_foto_grid->RowIndex, 'id'=>'r0_temuan_kerusakan_foto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($temuan_kerusakan_foto->RowAttrs["class"], "ewTemplate");
		$temuan_kerusakan_foto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$temuan_kerusakan_foto_grid->RenderRow();

		// Render list options
		$temuan_kerusakan_foto_grid->RenderListOptions();
		$temuan_kerusakan_foto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $temuan_kerusakan_foto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$temuan_kerusakan_foto_grid->ListOptions->Render("body", "left", $temuan_kerusakan_foto_grid->RowIndex);
?>
	<?php if ($temuan_kerusakan_foto->foto->Visible) { // foto ?>
		<td data-name="foto">
<span id="el$rowindex$_temuan_kerusakan_foto_foto" class="form-group temuan_kerusakan_foto_foto">
<div id="fd_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $temuan_kerusakan_foto->foto->FldTitle() ? $temuan_kerusakan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($temuan_kerusakan_foto->foto->ReadOnly || $temuan_kerusakan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="temuan_kerusakan_foto" data-field="x_foto" name="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id="x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto"<?php echo $temuan_kerusakan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="0">
<input type="hidden" name="fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo $temuan_kerusakan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="temuan_kerusakan_foto" data-field="x_foto" name="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" id="o<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($temuan_kerusakan_foto->foto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$temuan_kerusakan_foto_grid->ListOptions->Render("body", "right", $temuan_kerusakan_foto_grid->RowIndex);
?>
<script type="text/javascript">
ftemuan_kerusakan_fotogrid.UpdateOpts(<?php echo $temuan_kerusakan_foto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($temuan_kerusakan_foto->CurrentMode == "add" || $temuan_kerusakan_foto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $temuan_kerusakan_foto_grid->FormKeyCountName ?>" id="<?php echo $temuan_kerusakan_foto_grid->FormKeyCountName ?>" value="<?php echo $temuan_kerusakan_foto_grid->KeyCount ?>">
<?php echo $temuan_kerusakan_foto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($temuan_kerusakan_foto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $temuan_kerusakan_foto_grid->FormKeyCountName ?>" id="<?php echo $temuan_kerusakan_foto_grid->FormKeyCountName ?>" value="<?php echo $temuan_kerusakan_foto_grid->KeyCount ?>">
<?php echo $temuan_kerusakan_foto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($temuan_kerusakan_foto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftemuan_kerusakan_fotogrid">
</div>
<?php

// Close recordset
if ($temuan_kerusakan_foto_grid->Recordset)
	$temuan_kerusakan_foto_grid->Recordset->Close();
?>
<?php if ($temuan_kerusakan_foto_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($temuan_kerusakan_foto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($temuan_kerusakan_foto_grid->TotalRecs == 0 && $temuan_kerusakan_foto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($temuan_kerusakan_foto_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($temuan_kerusakan_foto->Export == "") { ?>
<script type="text/javascript">
ftemuan_kerusakan_fotogrid.Init();
</script>
<?php } ?>
<?php
$temuan_kerusakan_foto_grid->Page_Terminate();
?>
