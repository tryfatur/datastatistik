<?php
	// header("content-type: application/json"); 
	include 'Open_data.php';
	
	$open_data = new Open_data();

	$url = $open_data->set_action('package_search?q=organization:dinas-kependudukan-dan-pencatatan-sipil&start=0&rows=100');
	$ahay = $open_data->process_api($url)->result;

	for ($i=0; $i < count($ahay->results); $i++)
	{ 
		for ($j=0; $j < count($ahay->results[$i]); $j++)
		{
			$created = split('T', $ahay->results[$i]->resources[$j]->created);

			$data_created[$i]['name'] = $ahay->results[$i]->resources[$j]->name;
			$data_created[$i]['date_created'] = $created[0];
			$data_created[$i]['time_created'] = $created[1];
		}
	}

	echo "<pre>";
	print_r ($data_created);
	echo "</pre>";


	/*$top_ten = array('dinas-kependudukan-dan-pencatatan-sipil' => 69,
				'dinas-kesehatan' => 39,
				'badan-perencanaan-pembangunan-daerah' => 33,
				'sekretariat-daerah' => 28,
				'dinas-kebudayaan-dan-pariwisata' => 25,
				'dinas-pendidikan' => 25,
				'badan-kepegawaian-daerah' => 23,
				'badan-pusat-statistik' => 22,
				'dinas-perhubungan' => 22);*/

	/*$chart_groups = array("kependudukan-dan-ketenagakerjaan" => 107,
						"ekonomi-dan-keuangan" => 75,
						"lingkungan" => 52,
						"infrastruktur" => 40,
						"sosial" => 38,
						"kesehatan" => 34,
						"pendidikan" => 29,
						"pariwisata-dan-kebudayaan" => 26,
						"perhubungan" => 11,
						"kebencanaan" => 8);*/
?>