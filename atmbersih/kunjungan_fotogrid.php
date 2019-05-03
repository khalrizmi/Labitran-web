<?php include_once "userinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($kunjungan_foto_grid)) $kunjungan_foto_grid = new ckunjungan_foto_grid();

// Page init
$kunjungan_foto_grid->Page_Init();

// Page main
$kunjungan_foto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$kunjungan_foto_grid->Page_Render();
?>
<?php if ($kunjungan_foto->Export == "") { ?>
<script type="text/javascript">

// Form object
var fkunjungan_fotogrid = new ew_Form("fkunjungan_fotogrid", "grid");
fkunjungan_fotogrid.FormKeyCountName = '<?php echo $kunjungan_foto_grid->FormKeyCountName ?>';

// Validate form
fkunjungan_fotogrid.Validate = function() {
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
fkunjungan_fotogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "foto", false)) return false;
	return true;
}

// Form_CustomValidate event
fkunjungan_fotogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fkunjungan_fotogrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($kunjungan_foto->CurrentAction == "gridadd") {
	if ($kunjungan_foto->CurrentMode == "copy") {
		$bSelectLimit = $kunjungan_foto_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$kunjungan_foto_grid->TotalRecs = $kunjungan_foto->ListRecordCount();
			$kunjungan_foto_grid->Recordset = $kunjungan_foto_grid->LoadRecordset($kunjungan_foto_grid->StartRec-1, $kunjungan_foto_grid->DisplayRecs);
		} else {
			if ($kunjungan_foto_grid->Recordset = $kunjungan_foto_grid->LoadRecordset())
				$kunjungan_foto_grid->TotalRecs = $kunjungan_foto_grid->Recordset->RecordCount();
		}
		$kunjungan_foto_grid->StartRec = 1;
		$kunjungan_foto_grid->DisplayRecs = $kunjungan_foto_grid->TotalRecs;
	} else {
		$kunjungan_foto->CurrentFilter = "0=1";
		$kunjungan_foto_grid->StartRec = 1;
		$kunjungan_foto_grid->DisplayRecs = $kunjungan_foto->GridAddRowCount;
	}
	$kunjungan_foto_grid->TotalRecs = $kunjungan_foto_grid->DisplayRecs;
	$kunjungan_foto_grid->StopRec = $kunjungan_foto_grid->DisplayRecs;
} else {
	$bSelectLimit = $kunjungan_foto_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($kunjungan_foto_grid->TotalRecs <= 0)
			$kunjungan_foto_grid->TotalRecs = $kunjungan_foto->ListRecordCount();
	} else {
		if (!$kunjungan_foto_grid->Recordset && ($kunjungan_foto_grid->Recordset = $kunjungan_foto_grid->LoadRecordset()))
			$kunjungan_foto_grid->TotalRecs = $kunjungan_foto_grid->Recordset->RecordCount();
	}
	$kunjungan_foto_grid->StartRec = 1;
	$kunjungan_foto_grid->DisplayRecs = $kunjungan_foto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$kunjungan_foto_grid->Recordset = $kunjungan_foto_grid->LoadRecordset($kunjungan_foto_grid->StartRec-1, $kunjungan_foto_grid->DisplayRecs);

	// Set no record found message
	if ($kunjungan_foto->CurrentAction == "" && $kunjungan_foto_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$kunjungan_foto_grid->setWarningMessage(ew_DeniedMsg());
		if ($kunjungan_foto_grid->SearchWhere == "0=101")
			$kunjungan_foto_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$kunjungan_foto_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$kunjungan_foto_grid->RenderOtherOptions();
?>
<?php $kunjungan_foto_grid->ShowPageHeader(); ?>
<?php
$kunjungan_foto_grid->ShowMessage();
?>
<?php if ($kunjungan_foto_grid->TotalRecs > 0 || $kunjungan_foto->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($kunjungan_foto_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> kunjungan_foto">
<div id="fkunjungan_fotogrid" class="ewForm ewListForm form-inline">
<?php if ($kunjungan_foto_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($kunjungan_foto_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_kunjungan_foto" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_kunjungan_fotogrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$kunjungan_foto_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$kunjungan_foto_grid->RenderListOptions();

// Render list options (header, left)
$kunjungan_foto_grid->ListOptions->Render("header", "left");
?>
<?php if ($kunjungan_foto->foto->Visible) { // foto ?>
	<?php if ($kunjungan_foto->SortUrl($kunjungan_foto->foto) == "") { ?>
		<th data-name="foto" class="<?php echo $kunjungan_foto->foto->HeaderCellClass() ?>"><div id="elh_kunjungan_foto_foto" class="kunjungan_foto_foto"><div class="ewTableHeaderCaption"><?php echo $kunjungan_foto->foto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="foto" class="<?php echo $kunjungan_foto->foto->HeaderCellClass() ?>"><div><div id="elh_kunjungan_foto_foto" class="kunjungan_foto_foto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $kunjungan_foto->foto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($kunjungan_foto->foto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($kunjungan_foto->foto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$kunjungan_foto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$kunjungan_foto_grid->StartRec = 1;
$kunjungan_foto_grid->StopRec = $kunjungan_foto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($kunjungan_foto_grid->FormKeyCountName) && ($kunjungan_foto->CurrentAction == "gridadd" || $kunjungan_foto->CurrentAction == "gridedit" || $kunjungan_foto->CurrentAction == "F")) {
		$kunjungan_foto_grid->KeyCount = $objForm->GetValue($kunjungan_foto_grid->FormKeyCountName);
		$kunjungan_foto_grid->StopRec = $kunjungan_foto_grid->StartRec + $kunjungan_foto_grid->KeyCount - 1;
	}
}
$kunjungan_foto_grid->RecCnt = $kunjungan_foto_grid->StartRec - 1;
if ($kunjungan_foto_grid->Recordset && !$kunjungan_foto_grid->Recordset->EOF) {
	$kunjungan_foto_grid->Recordset->MoveFirst();
	$bSelectLimit = $kunjungan_foto_grid->UseSelectLimit;
	if (!$bSelectLimit && $kunjungan_foto_grid->StartRec > 1)
		$kunjungan_foto_grid->Recordset->Move($kunjungan_foto_grid->StartRec - 1);
} elseif (!$kunjungan_foto->AllowAddDeleteRow && $kunjungan_foto_grid->StopRec == 0) {
	$kunjungan_foto_grid->StopRec = $kunjungan_foto->GridAddRowCount;
}

// Initialize aggregate
$kunjungan_foto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$kunjungan_foto->ResetAttrs();
$kunjungan_foto_grid->RenderRow();
if ($kunjungan_foto->CurrentAction == "gridadd")
	$kunjungan_foto_grid->RowIndex = 0;
if ($kunjungan_foto->CurrentAction == "gridedit")
	$kunjungan_foto_grid->RowIndex = 0;
while ($kunjungan_foto_grid->RecCnt < $kunjungan_foto_grid->StopRec) {
	$kunjungan_foto_grid->RecCnt++;
	if (intval($kunjungan_foto_grid->RecCnt) >= intval($kunjungan_foto_grid->StartRec)) {
		$kunjungan_foto_grid->RowCnt++;
		if ($kunjungan_foto->CurrentAction == "gridadd" || $kunjungan_foto->CurrentAction == "gridedit" || $kunjungan_foto->CurrentAction == "F") {
			$kunjungan_foto_grid->RowIndex++;
			$objForm->Index = $kunjungan_foto_grid->RowIndex;
			if ($objForm->HasValue($kunjungan_foto_grid->FormActionName))
				$kunjungan_foto_grid->RowAction = strval($objForm->GetValue($kunjungan_foto_grid->FormActionName));
			elseif ($kunjungan_foto->CurrentAction == "gridadd")
				$kunjungan_foto_grid->RowAction = "insert";
			else
				$kunjungan_foto_grid->RowAction = "";
		}

		// Set up key count
		$kunjungan_foto_grid->KeyCount = $kunjungan_foto_grid->RowIndex;

		// Init row class and style
		$kunjungan_foto->ResetAttrs();
		$kunjungan_foto->CssClass = "";
		if ($kunjungan_foto->CurrentAction == "gridadd") {
			if ($kunjungan_foto->CurrentMode == "copy") {
				$kunjungan_foto_grid->LoadRowValues($kunjungan_foto_grid->Recordset); // Load row values
				$kunjungan_foto_grid->SetRecordKey($kunjungan_foto_grid->RowOldKey, $kunjungan_foto_grid->Recordset); // Set old record key
			} else {
				$kunjungan_foto_grid->LoadRowValues(); // Load default values
				$kunjungan_foto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$kunjungan_foto_grid->LoadRowValues($kunjungan_foto_grid->Recordset); // Load row values
		}
		$kunjungan_foto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($kunjungan_foto->CurrentAction == "gridadd") // Grid add
			$kunjungan_foto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($kunjungan_foto->CurrentAction == "gridadd" && $kunjungan_foto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$kunjungan_foto_grid->RestoreCurrentRowFormValues($kunjungan_foto_grid->RowIndex); // Restore form values
		if ($kunjungan_foto->CurrentAction == "gridedit") { // Grid edit
			if ($kunjungan_foto->EventCancelled) {
				$kunjungan_foto_grid->RestoreCurrentRowFormValues($kunjungan_foto_grid->RowIndex); // Restore form values
			}
			if ($kunjungan_foto_grid->RowAction == "insert")
				$kunjungan_foto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$kunjungan_foto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($kunjungan_foto->CurrentAction == "gridedit" && ($kunjungan_foto->RowType == EW_ROWTYPE_EDIT || $kunjungan_foto->RowType == EW_ROWTYPE_ADD) && $kunjungan_foto->EventCancelled) // Update failed
			$kunjungan_foto_grid->RestoreCurrentRowFormValues($kunjungan_foto_grid->RowIndex); // Restore form values
		if ($kunjungan_foto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$kunjungan_foto_grid->EditRowCnt++;
		if ($kunjungan_foto->CurrentAction == "F") // Confirm row
			$kunjungan_foto_grid->RestoreCurrentRowFormValues($kunjungan_foto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$kunjungan_foto->RowAttrs = array_merge($kunjungan_foto->RowAttrs, array('data-rowindex'=>$kunjungan_foto_grid->RowCnt, 'id'=>'r' . $kunjungan_foto_grid->RowCnt . '_kunjungan_foto', 'data-rowtype'=>$kunjungan_foto->RowType));

		// Render row
		$kunjungan_foto_grid->RenderRow();

		// Render list options
		$kunjungan_foto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($kunjungan_foto_grid->RowAction <> "delete" && $kunjungan_foto_grid->RowAction <> "insertdelete" && !($kunjungan_foto_grid->RowAction == "insert" && $kunjungan_foto->CurrentAction == "F" && $kunjungan_foto_grid->EmptyRow())) {
?>
	<tr<?php echo $kunjungan_foto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$kunjungan_foto_grid->ListOptions->Render("body", "left", $kunjungan_foto_grid->RowCnt);
?>
	<?php if ($kunjungan_foto->foto->Visible) { // foto ?>
		<td data-name="foto"<?php echo $kunjungan_foto->foto->CellAttributes() ?>>
<?php if ($kunjungan_foto_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_kunjungan_foto_foto" class="form-group kunjungan_foto_foto">
<div id="fd_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $kunjungan_foto->foto->FldTitle() ? $kunjungan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($kunjungan_foto->foto->ReadOnly || $kunjungan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="kunjungan_foto" data-field="x_foto" name="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto"<?php echo $kunjungan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="0">
<input type="hidden" name="fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="kunjungan_foto" data-field="x_foto" name="o<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id="o<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($kunjungan_foto->foto->OldValue) ?>">
<?php } elseif ($kunjungan_foto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $kunjungan_foto_grid->RowCnt ?>_kunjungan_foto_foto" class="kunjungan_foto_foto">
<span<?php echo $kunjungan_foto->foto->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($kunjungan_foto->foto, $kunjungan_foto->foto->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $kunjungan_foto_grid->RowCnt ?>_kunjungan_foto_foto" class="form-group kunjungan_foto_foto">
<div id="fd_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $kunjungan_foto->foto->FldTitle() ? $kunjungan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($kunjungan_foto->foto->ReadOnly || $kunjungan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="kunjungan_foto" data-field="x_foto" name="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto"<?php echo $kunjungan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php if ($kunjungan_foto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="kunjungan_foto" data-field="x_id" name="x<?php echo $kunjungan_foto_grid->RowIndex ?>_id" id="x<?php echo $kunjungan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($kunjungan_foto->id->CurrentValue) ?>">
<input type="hidden" data-table="kunjungan_foto" data-field="x_id" name="o<?php echo $kunjungan_foto_grid->RowIndex ?>_id" id="o<?php echo $kunjungan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($kunjungan_foto->id->OldValue) ?>">
<?php } ?>
<?php if ($kunjungan_foto->RowType == EW_ROWTYPE_EDIT || $kunjungan_foto->CurrentMode == "edit") { ?>
<input type="hidden" data-table="kunjungan_foto" data-field="x_id" name="x<?php echo $kunjungan_foto_grid->RowIndex ?>_id" id="x<?php echo $kunjungan_foto_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($kunjungan_foto->id->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$kunjungan_foto_grid->ListOptions->Render("body", "right", $kunjungan_foto_grid->RowCnt);
?>
	</tr>
<?php if ($kunjungan_foto->RowType == EW_ROWTYPE_ADD || $kunjungan_foto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fkunjungan_fotogrid.UpdateOpts(<?php echo $kunjungan_foto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($kunjungan_foto->CurrentAction <> "gridadd" || $kunjungan_foto->CurrentMode == "copy")
		if (!$kunjungan_foto_grid->Recordset->EOF) $kunjungan_foto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($kunjungan_foto->CurrentMode == "add" || $kunjungan_foto->CurrentMode == "copy" || $kunjungan_foto->CurrentMode == "edit") {
		$kunjungan_foto_grid->RowIndex = '$rowindex$';
		$kunjungan_foto_grid->LoadRowValues();

		// Set row properties
		$kunjungan_foto->ResetAttrs();
		$kunjungan_foto->RowAttrs = array_merge($kunjungan_foto->RowAttrs, array('data-rowindex'=>$kunjungan_foto_grid->RowIndex, 'id'=>'r0_kunjungan_foto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($kunjungan_foto->RowAttrs["class"], "ewTemplate");
		$kunjungan_foto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$kunjungan_foto_grid->RenderRow();

		// Render list options
		$kunjungan_foto_grid->RenderListOptions();
		$kunjungan_foto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $kunjungan_foto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$kunjungan_foto_grid->ListOptions->Render("body", "left", $kunjungan_foto_grid->RowIndex);
?>
	<?php if ($kunjungan_foto->foto->Visible) { // foto ?>
		<td data-name="foto">
<span id="el$rowindex$_kunjungan_foto_foto" class="form-group kunjungan_foto_foto">
<div id="fd_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto">
<span title="<?php echo $kunjungan_foto->foto->FldTitle() ? $kunjungan_foto->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($kunjungan_foto->foto->ReadOnly || $kunjungan_foto->foto->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="kunjungan_foto" data-field="x_foto" name="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id="x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto"<?php echo $kunjungan_foto->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fn_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fa_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="0">
<input type="hidden" name="fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fs_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="255">
<input type="hidden" name="fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fx_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id= "fm_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo $kunjungan_foto->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="kunjungan_foto" data-field="x_foto" name="o<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" id="o<?php echo $kunjungan_foto_grid->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($kunjungan_foto->foto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$kunjungan_foto_grid->ListOptions->Render("body", "right", $kunjungan_foto_grid->RowIndex);
?>
<script type="text/javascript">
fkunjungan_fotogrid.UpdateOpts(<?php echo $kunjungan_foto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($kunjungan_foto->CurrentMode == "add" || $kunjungan_foto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $kunjungan_foto_grid->FormKeyCountName ?>" id="<?php echo $kunjungan_foto_grid->FormKeyCountName ?>" value="<?php echo $kunjungan_foto_grid->KeyCount ?>">
<?php echo $kunjungan_foto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($kunjungan_foto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $kunjungan_foto_grid->FormKeyCountName ?>" id="<?php echo $kunjungan_foto_grid->FormKeyCountName ?>" value="<?php echo $kunjungan_foto_grid->KeyCount ?>">
<?php echo $kunjungan_foto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($kunjungan_foto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fkunjungan_fotogrid">
</div>
<?php

// Close recordset
if ($kunjungan_foto_grid->Recordset)
	$kunjungan_foto_grid->Recordset->Close();
?>
<?php if ($kunjungan_foto_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($kunjungan_foto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($kunjungan_foto_grid->TotalRecs == 0 && $kunjungan_foto->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($kunjungan_foto_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($kunjungan_foto->Export == "") { ?>
<script type="text/javascript">
fkunjungan_fotogrid.Init();
</script>
<?php } ?>
<?php
$kunjungan_foto_grid->Page_Terminate();
?>
