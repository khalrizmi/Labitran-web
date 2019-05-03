<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(22, "mi_maps1_php", $Language->MenuPhrase("22", "MenuText"), "maps1.php", -1, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}maps1.php'), FALSE, TRUE, "glyphicon glyphicon-asterisk");
$RootMenu->AddMenuItem(8, "mi_kunjungan", $Language->MenuPhrase("8", "MenuText"), "kunjunganlist.php", -1, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}kunjungan'), FALSE, FALSE, "glyphicon glyphicon-asterisk");
$RootMenu->AddMenuItem(19, "mi_temuan_kerusakan", $Language->MenuPhrase("19", "MenuText"), "temuan_kerusakanlist.php", -1, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}temuan_kerusakan'), FALSE, FALSE, "glyphicon glyphicon-asterisk");
$RootMenu->AddMenuItem(11, "mci_KONFIGURASI", $Language->MenuPhrase("11", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE, "glyphicon glyphicon-th");
$RootMenu->AddMenuItem(2, "mi_bank", $Language->MenuPhrase("2", "MenuText"), "banklist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}bank'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(1, "mi_atm", $Language->MenuPhrase("1", "MenuText"), "atmlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}atm'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(6, "mi_kabupaten", $Language->MenuPhrase("6", "MenuText"), "kabupatenlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}kabupaten'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(18, "mi_kerusakan", $Language->MenuPhrase("18", "MenuText"), "kerusakanlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}kerusakan'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(7, "mi_karyawan", $Language->MenuPhrase("7", "MenuText"), "karyawanlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}karyawan'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(3, "mi_jabatan", $Language->MenuPhrase("3", "MenuText"), "jabatanlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}jabatan'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(4, "mi_jalur", $Language->MenuPhrase("4", "MenuText"), "jalurlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}jalur'), FALSE, FALSE, "glyphicon glyphicon-cog");
$RootMenu->AddMenuItem(10, "mi_user", $Language->MenuPhrase("10", "MenuText"), "userlist.php", 11, "", IsLoggedIn() || AllowListMenu('{BEE67E43-43CB-4F70-9480-D7A4451BD8C8}user'), FALSE, FALSE, "glyphicon glyphicon-cog");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
