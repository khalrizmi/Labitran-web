<?php

// nik
// d_temuan
// kerusakan
// keterangan

?>
<?php if ($temuan_kerusakan->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_temuan_kerusakanmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($temuan_kerusakan->nik->Visible) { // nik ?>
		<tr id="r_nik">
			<td class="col-sm-2"><?php echo $temuan_kerusakan->nik->FldCaption() ?></td>
			<td<?php echo $temuan_kerusakan->nik->CellAttributes() ?>>
<span id="el_temuan_kerusakan_nik">
<span<?php echo $temuan_kerusakan->nik->ViewAttributes() ?>>
<?php echo $temuan_kerusakan->nik->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($temuan_kerusakan->d_temuan->Visible) { // d_temuan ?>
		<tr id="r_d_temuan">
			<td class="col-sm-2"><?php echo $temuan_kerusakan->d_temuan->FldCaption() ?></td>
			<td<?php echo $temuan_kerusakan->d_temuan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_d_temuan">
<span<?php echo $temuan_kerusakan->d_temuan->ViewAttributes() ?>>
<?php echo $temuan_kerusakan->d_temuan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($temuan_kerusakan->kerusakan->Visible) { // kerusakan ?>
		<tr id="r_kerusakan">
			<td class="col-sm-2"><?php echo $temuan_kerusakan->kerusakan->FldCaption() ?></td>
			<td<?php echo $temuan_kerusakan->kerusakan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_kerusakan">
<span<?php echo $temuan_kerusakan->kerusakan->ViewAttributes() ?>>
<?php echo $temuan_kerusakan->kerusakan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($temuan_kerusakan->keterangan->Visible) { // keterangan ?>
		<tr id="r_keterangan">
			<td class="col-sm-2"><?php echo $temuan_kerusakan->keterangan->FldCaption() ?></td>
			<td<?php echo $temuan_kerusakan->keterangan->CellAttributes() ?>>
<span id="el_temuan_kerusakan_keterangan">
<span<?php echo $temuan_kerusakan->keterangan->ViewAttributes() ?>>
<?php echo $temuan_kerusakan->keterangan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
