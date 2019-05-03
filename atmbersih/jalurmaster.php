<?php

// c_jalur
// nik

?>
<?php if ($jalur->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_jalurmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($jalur->c_jalur->Visible) { // c_jalur ?>
		<tr id="r_c_jalur">
			<td class="col-sm-2"><?php echo $jalur->c_jalur->FldCaption() ?></td>
			<td<?php echo $jalur->c_jalur->CellAttributes() ?>>
<span id="el_jalur_c_jalur">
<span<?php echo $jalur->c_jalur->ViewAttributes() ?>>
<?php echo $jalur->c_jalur->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jalur->nik->Visible) { // nik ?>
		<tr id="r_nik">
			<td class="col-sm-2"><?php echo $jalur->nik->FldCaption() ?></td>
			<td<?php echo $jalur->nik->CellAttributes() ?>>
<span id="el_jalur_nik">
<span<?php echo $jalur->nik->ViewAttributes() ?>>
<?php echo $jalur->nik->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
