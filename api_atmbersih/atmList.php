<?php 

	include 'config.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$nik = $_POST['nik'];
		$bankId = $_POST['cBank'];

		$response  = [];

		$sql = "select * from jalur_detil where nik='$nik' and c_bank='$bankId'";
		$query = mysqli_query($con, $sql);

		$response['data'] = [];
		
		while ($data = mysqli_fetch_assoc($query)) {

			$cBank = $data['c_bank'];
			$atmId = $data['atmid'];

			$sql2   = "select * from atm where c_bank='$cBank' and atmid='$atmId'";
			$query2 = mysqli_query($con, $sql2);
			$data2  = mysqli_fetch_assoc($query2);


			if ($data2['lokasi'] != "" and $data2['atmid'] != "") {
				// echo $data2['atmid']." - ".$data2['lokasi']."<br>";
				$string = $data2['lokasi'];
				$lokasi = preg_replace('/[^A-Za-z0-9\  ]/', '', $string);
				array_push($response['data'], [
					'atmid' => $data2['atmid'],
					'location'  => $lokasi,
				]);
			}
		}

		echo json_encode($response);
	}

 ?>