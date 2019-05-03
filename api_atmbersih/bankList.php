<?php 

	include 'config.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$nik = $_POST['nik'];

		$response  = [];

		$sql = "SELECT bank.c_bank, bank.n_bank FROM `jalur_detil` INNER JOIN bank ON jalur_detil.c_bank = bank.c_bank WHERE nik = '$nik' ORDER BY c_bank ASC";
		$query = mysqli_query($con, $sql);

		$response['data'] = [];
		

		$cBank = "";

		while ($data = mysqli_fetch_assoc($query)) {

			if ($cBank != $data['c_bank']) {
				array_push($response['data'], [
					'c_bank' => $data['c_bank'],
					'n_bank'  => $data['n_bank'],
				]);				
			}

			$cBank = $data['c_bank'];
		}

		echo json_encode($response);
	}

 ?>