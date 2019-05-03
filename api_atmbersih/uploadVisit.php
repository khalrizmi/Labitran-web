<?php 

	include 'config.php';

	date_default_timezone_set('Asia/Jakarta');

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$response = [];

		$nik   = $_POST['nik'];
		$atmid = $_POST['atmid'];
		$date  = date('Y-m-d H:i:s');
		$count = $_POST['count'];

		$sql = "insert into kunjungan values(null, '$nik', '$atmid', '$date')";
		$con->query($sql);
    	$insert_id =  $con->insert_id;

		$name = uniqid().".jpg";
		$folder = "upload/";

		move_uploaded_file($_FILES['file']['tmp_name'], $folder.$name);

		$sql = "insert into kunjungan_foto values(null, '$insert_id', '$name', '$date')";
		$con->query($sql);

		if ($count > 0) {
			for ($i=0; $i < $count ; $i++) { 
				$name = uniqid().".jpg";

				$sql = "insert into kunjungan_foto values(null, '$insert_id', '$name', '$date')";
				$con->query($sql);
				move_uploaded_file($_FILES['file'.$i]['tmp_name'], $folder.$name);	
			}
		}

		$response['success'] = true;
		$response['message']  = "Data berhasil disimpan";


		echo json_encode($response);
			
		

	}


 ?>