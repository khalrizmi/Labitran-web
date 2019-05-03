<?php

// nik
// atmid
// d_kunjungan

?>
<?php if ($kunjungan->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_kunjunganmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($kunjungan->nik->Visible) { // nik ?>
		<tr id="r_nik">
			<td class="col-sm-2"><?php echo $kunjungan->nik->FldCaption() ?></td>
			<td<?php echo $kunjungan->nik->CellAttributes() ?>>
<span id="el_kunjungan_nik">
<span<?php echo $kunjungan->nik->ViewAttributes() ?>>
<?php echo $kunjungan->nik->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($kunjungan->atmid->Visible) { // atmid ?>
		<tr id="r_atmid">
			<td class="col-sm-2"><?php echo $kunjungan->atmid->FldCaption() ?></td>
			<td<?php echo $kunjungan->atmid->CellAttributes() ?>>
<span id="el_kunjungan_atmid">
<span<?php echo $kunjungan->atmid->ViewAttributes() ?>>
<?php echo $kunjungan->atmid->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($kunjungan->d_kunjungan->Visible) { // d_kunjungan ?>
		<tr id="r_d_kunjungan">
			<td class="col-sm-2"><?php echo $kunjungan->d_kunjungan->FldCaption() ?></td>
			<td<?php echo $kunjungan->d_kunjungan->CellAttributes() ?>>
<span id="el_kunjungan_d_kunjungan">
<span<?php echo $kunjungan->d_kunjungan->ViewAttributes() ?>>
<?php echo $kunjungan->d_kunjungan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
