<?php 

	include 'config.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$nik = $_POST['nik'];

		$response  = [];

		$sql = "select * from jalur_detil where nik='$nik'";
		$query = mysqli_query($con, $sql);

		$response['data'] = [];
		
		while ($data = mysqli_fetch_assoc($query)) {

			$cBank = $data['c_bank'];
			$atmId = $data['atmid'];

			$sql2   = "select * from atm where c_bank='$cBank' and atmid='$atmId'";
			$query2 = mysqli_query($con, $sql2);
			$data2  = mysqli_fetch_assoc($query2);

			if ($data2['latitude'] != "" and $data2['lontitude'] != "") {
				array_push($response['data'], [
					'n_atm' => $data2['n_atm'],
					'latitude'  => $data2['latitude'],
					'longitude' => $data2['lontitude'],
				]);
			}
		}

		echo json_encode($response);
	}

 ?>