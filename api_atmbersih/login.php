<?php 

	include 'config.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$user = $_POST['nik'];
		$pass = $_POST['telepon'];

		$sql   = "select * from karyawan where nik='$user' and telp='$pass'";
		$query = mysqli_query($con, $sql);
		$data  = mysqli_fetch_assoc($query);
		
		$response = [];

		if (mysqli_num_rows($query) > 0) {

			$nik = $data['nik'];
			$response['login'] = true;
			$response['nik']   = $data['nik'];
			$response['nama']  = $data['nama'];
			$response['telepon'] = $data['telp'];

		} else {
			$response['login'] = false;
		}

		echo json_encode($response);
	}

 ?>