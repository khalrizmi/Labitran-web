<?php 

	include 'config.php';

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {		

		$response  = [];

		$sql = "SELECT * FROM kerusakan";
		$query = mysqli_query($con, $sql);

		$response['data'] = [];
		

		$cBank = "";

		while ($data = mysqli_fetch_assoc($query)) {

				array_push($response['data'], [
					'c_kerusakan' => $data['c_kerusakan'],
					'n_kerusakan'  => $data['n_kerusakan'],
				]);				
			

		}

		echo json_encode($response);
	}

 ?>